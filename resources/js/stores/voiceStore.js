import { Room, RoomEvent, AudioPresets, Track } from 'livekit-client';

let livekitRoom = null;
let silenceActive = true;
let isRestoringSession = false;
let listenersAttached = false; // ✅ prevent double listener

export default {

    participants: {},
    connectedChannel: null,
    isVoicePage: window.location.pathname.includes('/dashboard/voice'),

    isConnecting: false,
    initialized: false,
    muted: false,
    cameraEnabled: false,
    deafened: false,

    userId: window.userData?.id || null,

    joinSound: new Audio('/sounds/join.mp3'),
    leaveSound: new Audio('/sounds/leave.mp3'),
    lastSoundTime: 0,

    init() {
        if (this.initialized) return;
        this.initialized = true;

        if (window.userData) {
            this.userId = String(window.userData.id);
        }

        silenceActive = true;
        setTimeout(() => { silenceActive = false; }, 2000);

        if (window.currentVoiceParticipants) {
            this._populateParticipants(window.currentVoiceParticipants);
        }

        // Echo: update daftar peserta dari broadcast server
        // NO SOUND di sini — suara hanya dari LiveKit events agar tidak double
        if (window.Echo) {
            window.Echo.channel('voice-status').listen('.VoiceStateUpdated', (e) => {
                if (this.isMe(e.user?.id)) return;
                this.handleGlobalStateUpdate(e);
            });
        }

        this._quickRestore();

        // Pantau navigasi Turbo untuk update status halaman voice
        document.addEventListener('turbo:load', () => {
            this.isVoicePage = window.location.pathname.includes('/dashboard/voice');
        });

        // DISCONNECT OTOMATIS (Graceful): Saat refresh/tutup tab, lapor ke server tapi jangan hapus permanen (Grace period)
        window.addEventListener('beforeunload', () => {
            if (this.connectedChannel) {
                // Gunakan navigator.sendBeacon atau async tanpa await agar tidak terhambat penutupan tab
                const url = '/dashboard/voice/sync-state';
                const data = JSON.stringify({
                    channel_id: this.connectedChannel.id,
                    action: 'leave',
                    manual: false,
                    _token: document.querySelector('meta[name="csrf-token"]')?.content
                });
                const blob = new Blob([data], { type: 'application/json' });
                navigator.sendBeacon(url, blob);
            }
        });

        // ✅ ATASI Autoplay Policy: Browser blokir audio sebelum ada klik.
        // Coba resume setiap ada klik sampai berhasil 'running'
        const resumeAudio = async () => {
            if (livekitRoom?.engine?.client?.audioContext?.state === 'suspended') {
                await livekitRoom.engine.client.audioContext.resume();

            }
            if (livekitRoom?.engine?.client?.audioContext?.state === 'running') {
                document.removeEventListener('click', resumeAudio);
            }
        };
        document.addEventListener('click', resumeAudio);

        // Silenced in production: VoiceStore initialized
    },

    _quickRestore() {
        const saved = localStorage.getItem('voice_connected_channel');
        if (!saved) return;

        try {
            const chan = JSON.parse(saved);
            this.connectedChannel = chan;
            isRestoringSession = true;

            setTimeout(() => {
                this.joinChannel(chan, true).finally(() => {
                    isRestoringSession = false;
                });
            }, 50);
        } catch {
            localStorage.removeItem('voice_connected_channel');
        }
    },

    async joinChannel(channel, restoring = false) {

        if (this.isConnecting) return;

        if (!restoring && livekitRoom && this.connectedChannel?.id === channel.id) return;

        this.isConnecting = true;
        this.connectedChannel = channel;

        localStorage.setItem(
            'voice_connected_channel',
            JSON.stringify({ id: String(channel.id), name: channel.name })
        );

        try {

            // ✅ CLEANUP OLD ROOM
            if (livekitRoom) {
                try {
                    livekitRoom.removeAllListeners();
                    await livekitRoom.disconnect();
                } catch { }
                livekitRoom = null;
                listenersAttached = false;
            }

            const response = await axios.post('/dashboard/voice/token', {
                room_name: `channel-${channel.id}`
            });

            const { token, url } = response.data;

            livekitRoom = new Room({
                adaptiveStream: true,
                audioCaptureDefaults: {
                    autoGainControl: true,
                    echoCancellation: true,
                    noiseSuppression: true,
                },
                publishDefaults: {
                    audioPreset: AudioPresets.speech,
                    dtx: true
                }
            });

            await livekitRoom.connect(url, token);

            // ✅ OFFICIAL WAY: Resume audio context via LiveKit helper
            await livekitRoom.startAudio();


            this._setupListeners(livekitRoom);

            await axios.post('/dashboard/voice/sync-state', {
                channel_id: channel.id,
                action: 'join'
            });

            try {
                await livekitRoom.localParticipant.setMicrophoneEnabled(true);
                this.muted = false;

            } catch (err) {
                console.error('VoiceStore: Gagal aktifkan mic:', err);
                this.muted = true;
            }

            if (!restoring) {
                this.tryPlaySound(this.joinSound, true);
            }

            this.updateParticipantsFromRoom(livekitRoom);

        } catch (error) {
            console.error('Voice connection failed:', error);
            this.connectedChannel = null;
            localStorage.removeItem('voice_connected_channel');
        } finally {
            this.isConnecting = false;
        }
    },

    _setupListeners(room) {

        if (listenersAttached) return;
        listenersAttached = true;

        room.on(RoomEvent.ParticipantConnected, (p) => {
            this.updateParticipantsFromRoom(room);
            // SUARA dipindah ke Echo (handleGlobalStateUpdate) agar menaati Grace Period
        });

        room.on(RoomEvent.ParticipantDisconnected, (p) => {
            this.updateParticipantsFromRoom(room);
            // SUARA dipindah ke Echo (handleGlobalStateUpdate) agar menaati Grace Period
        });

        // Detect active speakers across the entire room (More reliable than IsSpeakingChanged)
        room.on(RoomEvent.ActiveSpeakersChanged, (speakers) => {
            const speakerIds = speakers.map(s => String(s.identity).split('-')[0]);


            Object.keys(this.participants).forEach(chanId => {
                this.participants[chanId] = (this.participants[chanId] || []).map(u => ({
                    ...u,
                    speaking: speakerIds.includes(String(u.id))
                }));
            });
            this.participants = { ...this.participants };
        });

        room.on(RoomEvent.TrackSubscribed, (track) => {
            if (track.kind === Track.Kind.Audio) {
                const el = track.attach();
                document.getElementById('voice-track-sink')?.appendChild(el);
            }
            this.updateParticipantsFromRoom(room);
        });
    },

    updateParticipantsFromRoom(room) {
        if (!room || !this.connectedChannel) return;

        const chanId = this.connectedChannel.id;

        const allParticipants = [
            room.localParticipant,
            ...Array.from(room.remoteParticipants.values())
        ];

        this.participants[chanId] = allParticipants.map(p => {
            const pureId = String(p.identity).split('-')[0];
            const existing = (this.participants[chanId] || []).find(u => String(u.id) === pureId);



            return {
                id: pureId,
                uid: p.sid || pureId,
                name: p.name || pureId,
                speaking: p.isSpeaking, // Source of truth
                muted: !p.isMicrophoneEnabled,
                cameraActive: p.isCameraEnabled,
                isLocal: p === room.localParticipant,
                avatar: existing?.avatar || window.userAvatars?.[pureId] || null,
            };
        });

        this.participants = { ...this.participants };

    },

    isMe(identity) {
        if (!identity || !this.userId) return false;
        return String(identity).split('-')[0] === String(this.userId);
    },

    async disconnect(manual = true) {
        if (livekitRoom) {
            try {
                livekitRoom.removeAllListeners();
                await livekitRoom.disconnect();
            } catch (e) { }
            livekitRoom = null;
            listenersAttached = false;
        }

        if (this.connectedChannel) {
            const chanId = this.connectedChannel.id;
            // Lapor ke server (manual: benar-benar keluar, !manual: cuma refresh)
            axios.post('/dashboard/voice/sync-state', {
                channel_id: chanId,
                action: 'leave',
                manual: manual
            });

            if (manual) {
                this.clearUI(chanId);
                this.connectedChannel = null;
                localStorage.removeItem('voice_connected_channel');
                this.tryPlaySound(this.leaveSound, true);
            }
        }
    },

    clearUI(channelId) {
        if (this.participants[channelId]) {
            delete this.participants[channelId];
            this.participants = { ...this.participants };
        }
    },

    tryPlaySound(audio, force = false) {
        if (!force && silenceActive) return;
        const now = Date.now();
        if (now - this.lastSoundTime < 1500) return;
        this.lastSoundTime = now;
        audio.currentTime = 0;
        audio.play().catch(() => { });
    },

    handleGlobalStateUpdate(e) {
        const { channelId, user, action } = e;
        if (this.isMe(user.id)) return;

        Object.keys(this.participants).forEach(cid => {
            this.participants[cid] = (this.participants[cid] || []).filter(u => String(u.id) !== String(user.id));
        });

        if (action === 'join') {
            if (!this.participants[channelId]) this.participants[channelId] = [];
            this.participants[channelId].push({
                id: String(user.id),
                uid: `global-${user.id}-${Date.now()}`,
                name: user.name,
                avatar: user.avatar,
                speaking: false,
                muted: false,
            });

            // BUNYI: Hanya jika real join (bukan transisi)
            if (!silenceActive) this.tryPlaySound(this.joinSound);

        } else if (action === 'leave') {
            // BUNYI: Hanya jika benar-benar keluar permanen
            if (!silenceActive) this.tryPlaySound(this.leaveSound);
        }

        this.participants = { ...this.participants };
    },

    _populateParticipants(data) {
        const normalized = {};
        Object.keys(data).forEach(cid => {
            normalized[cid] = (data[cid] || []).map(u => ({
                ...u,
                id: String(u.id),
                uid: u.uid || `cache-${u.id}`,
            }));
        });
        this.participants = normalized;
    },

    setParticipantSpeaking(identity, isSpeaking) {
        // Fallback method if needed, but ActiveSpeakersChanged is preferred
        const id = String(identity).split('-')[0];
        let changed = false;

        Object.keys(this.participants).forEach(chanId => {
            this.participants[chanId] = (this.participants[chanId] || []).map(u => {
                if (String(u.id) === id) {
                    if (u.speaking !== isSpeaking) changed = true;
                    return { ...u, speaking: isSpeaking };
                }
                return u;
            });
        });

        if (changed) {
            this.participants = { ...this.participants };
        }
    },

    async toggleMute() {
        if (!livekitRoom) return;
        const targetState = !this.muted; // Kalau sekarang false (nyala), target true (mati)
        try {
            // LiveKit: setMicrophoneEnabled(true) = AKTIF, setMicrophoneEnabled(false) = MATI
            await livekitRoom.localParticipant.setMicrophoneEnabled(!targetState);
            this.muted = targetState;
            this.updateParticipantsFromRoom(livekitRoom);
        } catch (e) {
            console.error('VoiceStore: Gagal toggle mute', e);
        }
    },

    async toggleCamera() {
        if (!livekitRoom) return;
        const targetActive = !this.cameraEnabled;
        try {
            await livekitRoom.localParticipant.setCameraEnabled(targetActive);
            this.cameraEnabled = targetActive;
            this.updateParticipantsFromRoom(livekitRoom);
        } catch (e) {
            console.error('VoiceStore: Gagal toggle camera', e);
        }
    },

    attachVideo(user, el) {
        if (!livekitRoom || !el) return;

        let participant;
        if (user.isLocal) {
            participant = livekitRoom.localParticipant;
        } else {
            participant = livekitRoom.remoteParticipants.get(user.uid);
            // Fallback cari by identity jika sid berbeda
            if (!participant) {
                participant = Array.from(livekitRoom.remoteParticipants.values())
                    .find(p => String(p.identity).startsWith(user.id));
            }
        }

        if (!participant) return;

        const trackPub = Array.from(participant.videoTrackPublications.values()).find(pub => pub.track);
        if (trackPub && trackPub.track) {
            trackPub.track.attach(el);
            console.log(`VoiceStore: Video attached for ${user.name}`);
        }
    },

    async toggleDeaf() {
        if (!livekitRoom) return;
        this.deafened = !this.deafened;

        // LiveKit deaf logic: Mute all remote tracks
        livekitRoom.remoteParticipants.forEach(p => {
            p.audioTrackPublications.forEach(pub => {
                if (pub.track) pub.track.setMuted(this.deafened);
            });
        });

        this.updateParticipantsFromRoom(livekitRoom);
    }
};