<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\SoundPlayed;

use Agence104\LiveKit\AccessToken;
use Agence104\LiveKit\AccessTokenOptions;
use Agence104\LiveKit\VideoGrant;

class VoiceController extends Controller
{
    public function index()
    {
        $channels = \App\Models\VoiceChannel::orderBy('sort_order')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $livekitUrl = config('services.livekit.url');

        // Get current participants from cache and normalize
        $rawParticipants = \Cache::get('voice_participants', []);
        $currentParticipants = [];
        foreach ($rawParticipants as $cid => $uList) {
            $currentParticipants[$cid] = array_values($uList);
        }

        return view('dashboard.voice.index', compact('channels', 'users', 'livekitUrl', 'currentParticipants'));
    }

    public function syncState(Request $request)
    {
        $request->validate([
            'channel_id' => 'required',
            'action' => 'required|in:join,leave',
            'manual' => 'nullable|boolean',
        ]);

        $user = auth()->user();
        $channelId = $request->channel_id;
        $action = $request->action;
        $isManual = $request->input('manual', false);

        $session = \App\Models\VoiceSession::firstOrNew(['user_id' => $user->id]);
        $wasDisconnecting = ($session->status === 'disconnecting');
        $isNewJoin = !$session->exists || ($session->voice_channel_id != $channelId);

        if ($action === 'join') {
            $session->voice_channel_id = $channelId;
            $session->status = 'connected';
            $session->last_seen_at = now();
            $session->save();

            // Cache update logic
            $participants = \Cache::get('voice_participants', []);
            
            // Remove from other channels
            foreach ($participants as $cid => $uList) {
                $participants[$cid] = array_values(array_filter($uList, fn($u) => (string)$u['id'] !== (string)$user->id));
            }

            // Add to current channel
            if (!isset($participants[$channelId])) $participants[$channelId] = [];
            
            $participants[$channelId][] = [
                'id' => (string)$user->id,
                'name' => $user->name,
                'avatar' => $user->foto_profil ? asset('storage/' . $user->foto_profil) : null,
            ];

            \Cache::put('voice_participants', $participants, now()->addHours(2));

            // BROADCAST JOIN: Hanya jika benar-benar baru masuk, bukan reconnect/refresh
            if ($isNewJoin && !$wasDisconnecting) {
                broadcast(new \App\Events\VoiceStateUpdated($channelId, [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->foto_profil ? asset('storage/' . $user->foto_profil) : null,
                ], 'join'))->toOthers();
            }

        } else {
            // ACTION: LEAVE
            if ($isManual) {
                // Real leave: hapus langsung
                $session->delete();

                $participants = \Cache::get('voice_participants', []);
                if (isset($participants[$channelId])) {
                    $participants[$channelId] = array_values(array_filter($participants[$channelId], fn($u) => (string)$u['id'] !== (string)$user->id));
                    \Cache::put('voice_participants', $participants, now()->addHours(2));
                }

                broadcast(new \App\Events\VoiceStateUpdated($channelId, [
                    'id' => $user->id,
                    'name' => $user->name,
                    'avatar' => $user->foto_profil ? asset('storage/' . $user->foto_profil) : null,
                ], 'leave'))->toOthers();
            } else {
                // Graceful leave: tandai disconnecting, tunggu 5 detik
                $session->status = 'disconnecting';
                $session->save();

                \App\Jobs\ProcessGracefulLeave::dispatch($user->id, $channelId)->delay(now()->addSeconds(5));
            }
        }

        return response()->json(['success' => true]);
    }

    public function getToken(Request $request)
    {
        $request->validate([
            'room_name' => 'required|string',
        ]);

        $user = auth()->user();
        $roomName = $request->room_name;

        // Use user ID + name (slugified) + small salt for uniqueness in LiveKit sessions
        $participantName = $user->name;
        // Identity must be alphanumeric/dashes for best compatibility
        $safeName = preg_replace('/[^a-zA-Z0-9]/', '', $user->name);
        $participantIdentity = $user->id . '-' . $safeName . '-' . substr(uniqid(), -4);

        $tokenOptions = (new AccessTokenOptions())
            ->setIdentity($participantIdentity)
            ->setName($participantName);

        $videoGrant = (new VideoGrant())
            ->setRoomJoin(true)
            ->setRoomName($roomName);

        $token = (new AccessToken(
            config('services.livekit.api_key'),
            config('services.livekit.api_secret')
        ))
            ->init($tokenOptions)
            ->setGrant($videoGrant)
            ->toJwt();

        return response()->json([
            'token' => $token,
            'url' => config('services.livekit.url')
        ]);
    }

    public function broadcastSound(Request $request)
    {
        $url = $request->url;
        $channelId = $request->channel_id;

        broadcast(new SoundPlayed($url, $channelId, auth()->id()))->toOthers();

        return response()->json(['success' => true]);
    }
}
