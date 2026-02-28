<x-admin-layout title="Chat Room VN+" :noPadding="true">
    @push('styles')
    <style>
    /* Animated Mesh Gradient Background */
    @keyframes mesh-gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    .futuristic-bg {
        background: linear-gradient(-45deg, #f8fafc, #f1f5f9, #e2e8f0, #f8fafc);
        background-size: 400% 400%;
        animation: mesh-gradient 20s ease infinite;
        position: absolute;
        inset: 0;
        opacity: 0.5;
        pointer-events: none;
    }
    .dark .futuristic-bg {
        background: linear-gradient(-45deg, #0f172a, #1e293b, #0f172a, #111827);
        opacity: 0.8;
    }

    /* Premium Glass Effect */
    .glass-surface {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .dark .glass-surface {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Role Based Glows */
    .glow-super-admin { box-shadow: 0 0 15px rgba(168, 85, 247, 0.3); border-color: rgba(168, 85, 247, 0.4) !important; }
    .glow-admin { box-shadow: 0 0 15px rgba(59, 130, 246, 0.3); border-color: rgba(59, 130, 246, 0.4) !important; }
    .glow-pengurus { box-shadow: 0 0 15px rgba(34, 197, 94, 0.3); border-color: rgba(34, 197, 94, 0.4) !important; }

    /* Bouncy Message Entry */
    .message-bounce {
        animation: message-bounce 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes message-bounce {
        from { opacity: 0; transform: translateY(20px) scale(0.9); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .message-bubble {
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .message-bubble:hover {
        transform: translateY(-1px);
    }

    /* Animations */
    @keyframes slide-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }

    /* Custom Audio Player Style */
    audio::-webkit-media-controls-enclosure {
        background-color: rgba(255, 255, 255, 0.2);
    }
    .dark audio::-webkit-media-controls-enclosure {
        background-color: rgba(30, 41, 59, 0.4);
    }

    /* Custom Scrollbar - Hidden by default, shows on hover */
    .chat-scroll {
        scrollbar-width: thin;
        scrollbar-color: transparent transparent;
        transition: scrollbar-color 0.3s;
    }
    .chat-scroll:hover {
        scrollbar-color: rgba(156, 163, 175, 0.3) transparent;
    }
    .chat-scroll::-webkit-scrollbar { width: 5px; }
    .chat-scroll::-webkit-scrollbar-track { background: transparent; }
    .chat-scroll::-webkit-scrollbar-thumb { 
        background-color: transparent; 
        border-radius: 20px;
        transition: background-color 0.3s;
    }
    .chat-scroll:hover::-webkit-scrollbar-thumb { 
        background-color: rgba(156, 163, 175, 0.3); 
    }

    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .animate-bounce-slow {
        animation: bounce-slow 2s ease-in-out infinite;
    }
</style>
@endpush

    <div class="h-full w-full flex flex-col items-center justify-center p-2 lg:p-4 relative overflow-hidden" x-data="vibrantChat()" x-init="init()">
    {{-- Animated BG Mesh - Stays Full Screen --}}
    <div class="futuristic-bg"></div>

    {{-- Main Chat Container (Narrow & Centered) --}}
    <div class="w-full max-w-[1100px] h-full flex flex-col glass-surface rounded-[40px] shadow-[0_30px_100px_rgba(0,0,0,0.25)] dark:shadow-[0_30px_100px_rgba(0,0,0,0.4)] overflow-hidden relative z-10 border border-white/40 dark:border-white/5 transition-all duration-500">
        
        {{-- Main Header inside Container --}}
        <div class="flex items-center justify-between p-3 lg:p-4 shrink-0 relative z-20 bg-white/10 dark:bg-black/20 border-b border-white/20 dark:border-white/5 backdrop-blur-md">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-700 flex items-center justify-center text-white shadow-xl shadow-blue-500/20 group hover:rotate-6 transition-all duration-300">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/></svg>
                </div>
                <div>
                    <h1 class="text-lg lg:text-2xl font-black text-slate-900 dark:text-white tracking-tighter">
                        CHAT ROOM <span class="text-blue-600 dark:text-blue-400">VN+</span>
                    </h1>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span class="inline-block w-2 h-2 rounded-full bg-green-500 animate-pulse border-2 border-white dark:border-slate-900"></span>
                        <p class="text-[9px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]" x-text="onlineUsers.length + ' ONLINE'"></p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2">
                 {{-- Removed sidebar toggle button --}}
            </div>
        </div>

        <div class="flex-1 min-h-0 flex relative">
            {{-- Chat Main Card (Internal proportions) --}}
            <div class="flex-1 flex flex-col relative z-10 min-w-0">
            {{-- Messages Area --}}
            <div class="flex-1 overflow-y-auto px-4 lg:px-10 py-10 flex flex-col gap-10 chat-scroll relative" x-ref="messageContainer" @click="activeMsgId = null">
                <template x-for="(group, date) in groupedMessages" :key="date">
                    <div class="flex flex-col gap-10">
                        {{-- Date Separator --}}
                        <div class="flex justify-center sticky top-0 z-30 py-4 pointer-events-none">
                            <div class="px-5 py-1.5 glass-surface rounded-full shadow-lg border-blue-500/10 dark:border-white/5 pointer-events-auto bg-white/20 dark:bg-black/40 backdrop-blur-md">
                                <span class="text-[9px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em]" x-text="formatDateHeader(date)"></span>
                            </div>
                        </div>

                        <template x-for="msg in group" :key="msg.id">
                            <div class="message-bubble animate-slide-up relative group/item" :class="msg.user_id === {{ auth()->id() }} ? 'text-right' : 'text-left'">
                                <div class="flex items-start gap-2.5" :class="msg.user_id === {{ auth()->id() }} ? 'flex-row-reverse' : 'flex-row'">
                                    
                                    {{-- Avatar (for other users) --}}
                                    <template x-if="msg.user_id !== {{ auth()->id() }}">
                                        <div class="relative group/avatar shrink-0 mt-0.5">
                                            <template x-if="msg.user.foto_profil">
                                                <img :src="'{{ asset('storage') }}/' + msg.user.foto_profil" class="w-8 h-8 object-cover rounded-full shadow border border-white/10">
                                            </template>
                                            <template x-if="!msg.user.foto_profil">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br flex items-center justify-center text-white text-[11px] font-black uppercase shrink-0 shadow border border-white/10"
                                                    :class="getRoleStyles(msg.user.jabatan).avatar">
                                                    <span x-text="msg.user.name.substring(0,1)"></span>
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <div class="max-w-[80%] lg:max-w-[65%] relative group/bubble flex flex-col cursor-pointer shrink-0 min-w-0"
                                         @click.stop="activeMsgId = activeMsgId === msg.id ? null : msg.id"
                                         :class="msg.user_id === {{ auth()->id() }} ? 'items-end' : 'items-start'">
                                        
                                        {{-- Actions Overlay --}}
                                        <div class="absolute -top-3 transition-all duration-300 flex items-center glass-surface rounded-2xl p-1 z-[60] shadow-2xl scale-95"
                                            :class="{
                                                'opacity-100 translate-y-0 scale-100 visible': activeMsgId === msg.id,
                                                'opacity-0 invisible lg:group-hover/bubble:opacity-100 lg:group-hover/bubble:visible lg:group-hover/bubble:translate-y-0 lg:group-hover/bubble:scale-100': activeMsgId !== msg.id,
                                                '-right-2': msg.user_id === {{ auth()->id() }},
                                                '-left-2': msg.user_id !== {{ auth()->id() }}
                                            }"
                                            @click.stop>
                                            <button @click.stop="setReply(msg); activeMsgId = null" class="p-2 text-slate-500 hover:text-blue-500 hover:bg-black/5 dark:hover:bg-white/5 rounded-xl transition-all"><svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h10a8 8 0 018 8v2M3 10l5 5m-5-5l5-5"/></svg></button>
                                            
                                            <template x-if="msg.user_id === {{ auth()->id() }}">
                                                <div class="flex items-center">
                                                    <template x-if="msg.type === 'text'">
                                                        <button @click.stop="startEdit(msg); activeMsgId = null" class="p-2 text-slate-500 hover:text-indigo-500 hover:bg-black/5 dark:hover:bg-white/5 rounded-xl transition-all"><svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                                                    </template>
                                                    <button @click.stop="deleteMessage(msg.id); activeMsgId = null" class="p-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-xl hover:from-rose-600 hover:to-pink-600 shadow-lg transition-colors"><svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- Bubble Content --}}
                                        <div :id="'chat-message-' + msg.id" x-show="!msg.isEditing" 
                                            class="rounded-[24px] text-[15px] leading-relaxed transition-all relative group/text w-fit max-w-full shadow-lg border border-transparent"
                                            :class="{
                                                'px-4 py-3': msg.type !== 'sticker',
                                                'bg-blue-600 text-white rounded-tr-none shadow-blue-600/30': msg.user_id === {{ auth()->id() }} && msg.type !== 'sticker',
                                                'glass-surface dark:text-white rounded-tl-none border-white/10 dark:border-white/5': msg.user_id !== {{ auth()->id() }} && msg.type !== 'sticker',
                                                'bg-transparent p-0 shadow-none border-none': msg.type === 'sticker'
                                            }">
                                            
                                            {{-- Name inside bubble (for other users) --}}
                                            <template x-if="msg.user_id !== {{ auth()->id() }} && msg.type !== 'sticker'">
                                                <div class="flex items-center gap-1.5 mb-1 -mt-0.5" :class="getRoleStyles(msg.user.jabatan).text">
                                                    <span class="text-[12px] font-black uppercase tracking-widest leading-none shrink-0" x-text="msg.user.name"></span>
                                                    <template x-if="msg.user.jabatan">
                                                        <div class="w-1 h-1 rounded-full shrink-0" :class="getRoleStyles(msg.user.jabatan).badge"></div>
                                                    </template>
                                                </div>
                                            </template>
                                            
                                            {{-- Quoted Message --}}
                                            <template x-if="msg.parent">
                                                <div @click.stop="jumpToMessage(msg.parent_id)" 
                                                    class="mb-3 p-3 bg-black/10 dark:bg-white/5 rounded-2xl border-l-[4px] border-blue-400/50 text-[12px] opacity-90 cursor-pointer hover:bg-black/20 dark:hover:bg-white/10 transition-colors group/quote">
                                                    <p class="font-black uppercase tracking-tighter text-[10px] mb-1 group-hover/quote:text-blue-400 transition-colors" x-text="msg.parent.user.name"></p>
                                                    <template x-if="msg.parent.type === 'image'"><p>📷 Foto</p></template>
                                                    <template x-if="msg.parent.type === 'video'"><p>🎥 Video</p></template>
                                                    <template x-if="msg.parent.type === 'document'"><p>📄 Dokumen</p></template>
                                                    <template x-if="msg.parent.type === 'text'"><p class="truncate italic" x-text="msg.parent.message"></p></template>
                                                    <template x-if="msg.parent.type === 'voice'"><p>🎤 VN</p></template>
                                                    <template x-if="msg.parent.type === 'poll'"><p>📊 Polling</p></template>
                                                </div>
                                            </template>

                                            {{-- TEXT --}}
                                            <template x-if="msg.type === 'text'">
                                                <div x-html="msg.renderedMessage" class="whitespace-pre-wrap break-words [word-break:break-word] relative z-10 drop-shadow-sm font-medium"></div>
                                            </template>

                                            {{-- IMAGE --}}
                                            <template x-if="msg.type === 'image'">
                                                <div class="space-y-3">
                                                    <img :src="msg.file_path" class="rounded-2xl w-full max-w-[280px] border border-white/10 hover:opacity-90 transition-opacity cursor-pointer shadow-lg" @click.stop="window.open(msg.file_path, '_blank')">
                                                    <div x-show="msg.message && msg.message !== '[Foto]'" x-html="msg.renderedMessage" class="px-1 text-sm font-medium"></div>
                                                </div>
                                            </template>

                                            {{-- VIDEO --}}
                                            <template x-if="msg.type === 'video'">
                                                <div class="space-y-3">
                                                    <video :src="msg.file_path" controls class="rounded-2xl max-w-[280px] w-full border border-white/10 shadow-lg"></video>
                                                    <div x-show="msg.message && msg.message !== '[Video]'" x-html="msg.renderedMessage" class="px-1 text-sm font-medium"></div>
                                                </div>
                                            </template>

                                            {{-- DOCUMENT --}}
                                            <template x-if="msg.type === 'document'">
                                                <div class="flex items-center gap-4 min-w-[200px] lg:min-w-[240px] p-2 bg-black/10 dark:bg-white/5 rounded-2xl border border-white/5">
                                                    <div class="w-12 h-12 rounded-xl bg-purple-600 flex items-center justify-center text-white shrink-0">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-xs font-black truncate text-white" x-text="msg.message"></p>
                                                        <p class="text-[9px] font-bold opacity-50 uppercase tracking-widest mt-0.5" x-text="msg.file_path.split('.').pop() + ' FILE'"></p>
                                                    </div>
                                                    <a :href="msg.file_path" download class="p-2 hover:bg-white/10 rounded-lg transition-all text-white/50 hover:text-white">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                    </a>
                                                </div>
                                            </template>

                                            {{-- STICKER --}}
                                            <template x-if="msg.type === 'sticker'">
                                                <div class="p-0">
                                                    <img :src="msg.file_path" class="w-32 h-32 object-contain drop-shadow-xl animate-message-bounce">
                                                </div>
                                            </template>

                                            {{-- POLL --}}
                                            <template x-if="msg.type === 'poll' && msg.poll">
                                                <div class="min-w-[220px] lg:min-w-[280px] space-y-4 py-2">
                                                    <h4 class="text-sm font-black text-white px-1 leading-tight" x-text="msg.poll.question"></h4>
                                                    <div class="space-y-2">
                                                        <template x-for="option in msg.poll.options" :key="option.id">
                                                            <button @click.stop="votePoll(msg.poll, option.id)" 
                                                                class="w-full relative h-10 rounded-xl overflow-hidden group/opt transition-all active:scale-95"
                                                                :class="hasVoted(msg.poll, option.id) ? 'bg-cyan-500/20 ring-1 ring-cyan-500/50' : 'bg-white/5 hover:bg-white/10'">
                                                                
                                                                {{-- Progress Bar --}}
                                                                <div class="absolute inset-y-0 left-0 bg-cyan-400/30 transition-all duration-500"
                                                                    :style="`width: ${calculatePollPercentage(msg.poll, option.id)}%`"></div>
                                                                
                                                                <div class="absolute inset-0 px-4 flex items-center justify-between pointer-events-none">
                                                                    <div class="flex items-center gap-2">
                                                                        <template x-if="hasVoted(msg.poll, option.id)">
                                                                            <svg class="w-3.5 h-3.5 text-cyan-400 drop-shadow-[0_0_8px_rgba(34,211,238,0.5)]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                                        </template>
                                                                        <span class="text-xs font-bold text-white/90" x-text="option.option_text"></span>
                                                                    </div>
                                                                    <span class="text-[10px] font-black text-white/40 group-hover/opt:text-white transition-colors" x-text="calculatePollPercentage(msg.poll, option.id) + '%'"></span>
                                                                </div>
                                                            </button>
                                                        </template>
                                                    </div>
                                                    <div class="flex justify-between items-center px-1">
                                                        <p class="text-[9px] font-black opacity-50 uppercase tracking-widest" x-text="msg.poll.votes.length + ' VOTES'"></p>
                                                        <template x-if="msg.poll.multiple_choice">
                                                            <span class="text-[8px] font-black bg-white/10 px-2 py-0.5 rounded-full uppercase">Multiple</span>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                            {{-- VOICE --}}
                                            <template x-if="msg.type === 'voice'">
                                                <div x-data="waveformPlayer(msg.file_path)" class="flex items-center gap-3 lg:gap-4 py-2 min-w-[200px] lg:min-w-[240px]">
                                                    <button @click.stop="togglePlay" class="w-10 h-10 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center shrink-0 transition-all duration-300">
                                                        <template x-if="!isPlaying"><svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></template>
                                                        <template x-if="isPlaying"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/></svg></template>
                                                    </button>
                                                    <div class="flex-1 flex flex-col gap-1.5">
                                                        <div class="h-8 flex items-center gap-[3px] relative cursor-pointer" @click="seek($event)">
                                                            <template x-for="i in 28" :key="i">
                                                                <div class="w-[3px] rounded-full transition-all duration-300" :class="i/28 <= progress ? 'bg-white' : 'bg-white/30'" :style="`height: ${[40,70,30,80,50,90,40,60,80,30,70,40,90,50,40,70,30,80,50,90,40,60,80,30,70,40,90,50][i-1]}%`" ></div>
                                                            </template>
                                                            <div class="absolute top-1/2 -translate-y-1/2 w-3.5 h-3.5 bg-green-400 rounded-full shadow-[0_0_12px_rgba(74,222,128,1)] transition-all duration-75 pointer-events-none" :style="`left: calc(${progress * 100}% - 7px)`"></div>
                                                        </div>
                                                        <div class="flex justify-between items-center px-0.5">
                                                            <span class="text-[9px] font-black opacity-80" x-text="formatTime(currentTime)"></span>
                                                            <span class="text-[9px] font-black opacity-80" x-text="formatTime(duration)"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                            
                                            <div class="flex items-center justify-end gap-2 mt-2 pt-1 border-t border-white/10 opacity-70" :class="msg.type === 'sticker' ? 'hidden' : ''">
                                                <template x-if="msg.updated_at !== msg.created_at">
                                                    <span class="text-[8px] font-bold italic lowercase">edited</span>
                                                </template>
                                                <span class="text-[9px] font-bold tracking-tighter" x-text="formatTime(msg.created_at)"></span>
                                            </div>
                                        </div>

                                        {{-- Edit Area --}}
                                        <div x-show="msg.isEditing" class="glass-floating p-4 rounded-[28px] border-2 border-blue-500/50 shadow-2xl min-w-[280px]">
                                            <textarea x-model="msg.editText" class="w-full bg-slate-100 dark:bg-slate-900/80 border-none rounded-2xl text-sm p-3 text-slate-900 dark:text-white focus:ring-2 focus:ring-blue-500 custom-scrollbar mb-3 transition-all" rows="2"></textarea>
                                            <div class="flex justify-end gap-3">
                                                <button @click="cancelEdit(msg)" class="px-3 py-1.5 text-xs text-slate-500 hover:text-slate-800 dark:hover:text-white font-black uppercase transition-colors">Discard</button>
                                                <button @click="saveEdit(msg)" class="px-5 py-1.5 bg-blue-600 text-white text-xs font-black rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all uppercase">Apply Update</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </template>

                {{-- Floating Scroll to Bottom Button --}}
                <div x-show="showScrollToBottom" x-cloak
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="translate-y-10 opacity-0 scale-50"
                    x-transition:enter-end="translate-y-0 opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200 transform"
                    x-transition:leave-start="translate-y-0 opacity-100 scale-100"
                    x-transition:leave-end="translate-y-10 opacity-0 scale-50"
                    class="absolute bottom-6 right-6 lg:right-10 z-30">
                    <button @click="scrollToBottom(true)" 
                        class="w-14 h-14 glass-surface text-slate-600 dark:text-white rounded-full flex items-center justify-center shadow-2xl border-white/40 dark:border-white/10 hover:scale-110 active:scale-95 transition-all group relative">
                        <svg class="w-6 h-6 group-hover:translate-y-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                        
                        {{-- Unread Badge --}}
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 bg-blue-600 text-white text-[10px] font-black w-6 h-6 rounded-full flex items-center justify-center shadow-lg border-2 border-white dark:border-[#0b0f19] animate-bounce" x-text="unreadCount"></span>
                        </template>
                    </button>
                </div>

                {{-- Typing Indicator --}}
                <div x-show="typingUsers.length > 0" x-cloak class="absolute bottom-6 left-6 lg:left-10 z-30 animate-slide-up">
                    <div class="px-5 py-3 glass-surface rounded-full flex items-center gap-3 border-blue-500/20 shadow-2xl">
                        <div class="flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce [animation-delay:-0.3s]"></span>
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce [animation-delay:-0.15s]"></span>
                            <span class="w-1.5 h-1.5 bg-blue-600 rounded-full animate-bounce"></span>
                        </div>
                        <span class="text-[11px] font-black text-slate-600 dark:text-blue-100 italic tracking-tight">
                            <span x-text="typingUsers.length ? typingUsers[0].name : ''"></span>
                            <template x-if="typingUsers.length > 1">
                                <span x-text="' & ' + (typingUsers.length - 1) + ' others'"></span>
                            </template>
                            is thinking...
                        </span>
                    </div>
                </div>
            </div>

            {{-- Floating Input Area --}}
            <div class="p-3 lg:p-6 shrink-0 relative z-30">
                {{-- Mention List --}}
                <div x-show="mentionOpen" x-cloak
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-20 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="absolute bottom-[calc(100%+20px)] left-0 w-72 glass-surface rounded-[32px] shadow-2xl overflow-hidden z-50 border-blue-500/20 px-2 py-2">
                    <div class="px-4 py-3 border-b border-white/20 dark:border-white/5 mb-1">
                        <span class="text-[10px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-[0.2em]">Mention Squadron</span>
                    </div>
                    <div class="max-h-60 overflow-y-auto chat-scroll space-y-1">
                        <template x-for="(user, index) in filteredMentions" :key="user.id">
                            <button @click="selectMention(user)"
                                class="w-full flex items-center gap-3 px-4 py-3.5 cursor-pointer transition-all rounded-2xl group text-left"
                                :class="mentionIndex === index ? 'bg-blue-600 text-white shadow-lg scale-[1.02]' : 'text-slate-800 dark:text-white hover:bg-slate-100/50 dark:hover:bg-white/5'">
                                <div class="w-9 h-9 rounded-xl flex items-center justify-center font-black text-xs uppercase shrink-0 shadow-sm transition-transform group-hover:rotate-6"
                                    :class="mentionIndex === index ? 'bg-white/20' : 'bg-gradient-to-tr from-slate-200 to-slate-300 dark:from-white/5 dark:to-white/10 text-slate-600 dark:text-slate-400'">
                                    <span x-text="user.id === 'everyone' ? '@' : user.name.substring(0,1)"></span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black truncate" x-text="user.name"></p>
                                    <p class="text-[10px] font-black opacity-50 uppercase tracking-tighter" x-text="user.jabatan || 'ANGGOTA'"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                {{-- Unified Picker (Emoji & Sticker) --}}
                <div x-show="emojiPickerOpen" @click.away="emojiPickerOpen = false" x-cloak
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-20 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="absolute bottom-[calc(100%+20px)] left-0 w-[340px] sm:w-[380px] glass-surface rounded-[40px] shadow-2xl z-[60] overflow-hidden border-white/20 flex flex-col h-[420px]">
                    
                    {{-- Header Tabs & Search --}}
                    <div class="p-6 pb-4 bg-white/5 border-b border-white/10">
                        <div class="flex gap-2 p-1 glass-surface rounded-2xl mb-4 border-white/10 dark:bg-black/20">
                            <button @click="pickerTab = 'emoji'" class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all"
                                :class="pickerTab === 'emoji' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:text-blue-500'">Emojis</button>
                            <button @click="pickerTab = 'sticker'" class="flex-1 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.2em] transition-all"
                                :class="pickerTab === 'sticker' ? 'bg-blue-600 text-white shadow-xl shadow-blue-600/20' : 'text-slate-400 hover:text-blue-500'">Stickers</button>
                        </div>
                        <div class="relative">
                            <input type="text" x-model="pickerSearch" placeholder="Find the vibe..." 
                                class="w-full bg-slate-100/50 dark:bg-white/5 border-none rounded-2xl py-3 pl-12 text-sm font-bold text-slate-800 dark:text-white placeholder-slate-400 focus:ring-4 focus:ring-blue-500/10 transition-all">
                            <svg class="w-5 h-5 absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-6 chat-scroll">
                        {{-- Emoji Grid --}}
                        <div x-show="pickerTab === 'emoji'" class="grid grid-cols-6 sm:grid-cols-7 gap-3">
                            <template x-for="(emoji, index) in filteredEmojis" :key="index">
                                <button @click="addEmoji(emoji)" class="text-3xl hover:scale-150 transition-all hover:rotate-6 p-1 active:scale-95 text-center drop-shadow-sm">
                                    <span x-text="emoji"></span>
                                </button>
                            </template>
                        </div>

                        {{-- Sticker Grid --}}
                        <div x-show="pickerTab === 'sticker'" class="grid grid-cols-3 gap-4">
                            <button @click="triggerFile('sticker')" class="aspect-square rounded-[24px] border-3 border-dashed border-slate-200 dark:border-white/10 flex flex-col items-center justify-center gap-2 hover:bg-blue-600/5 hover:border-blue-500/50 transition-all group">
                                <div class="w-10 h-10 rounded-2xl bg-blue-600/10 text-blue-500 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-500">Custom</span>
                            </button>

                            <template x-for="sticker in stickers" :key="'def-' + sticker.id">
                                <button @click="sendSticker(sticker.url)" class="aspect-square p-2 hover:bg-blue-500/5 rounded-[24px] transition-all group border border-transparent hover:border-blue-500/20">
                                    <img :src="sticker.url" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                                </button>
                            </template>

                            <template x-for="sticker in userStickers" :key="'user-' + sticker.id">
                                <button @click="sendSticker(sticker.sticker_url)" class="aspect-square p-2 hover:bg-blue-500/5 rounded-[24px] transition-all group border border-transparent hover:border-blue-500/20 shadow-sm">
                                    <img :src="sticker.sticker_url" class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300">
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                {{-- Polling Modal --}}
                <div x-show="pollModalOpen" x-cloak
                    class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-slate-900/40 dark:bg-[#0b0f19]/80 backdrop-blur-sm"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100">
                    
                    <div class="bg-white dark:bg-slate-900 w-full max-w-md rounded-[40px] shadow-2xl border border-slate-200 dark:border-white/10 overflow-hidden relative"
                        @click.away="pollModalOpen = false"
                        x-transition:enter="transition ease-out duration-400 transform"
                        x-transition:enter-start="opacity-0 scale-90 translate-y-20"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                        
                        <div class="futuristic-bg opacity-20"></div>

                        <div class="p-8 relative z-10">
                            <!-- Close Button -->
                            <button @click="pollModalOpen = false" class="absolute top-6 right-6 p-2 text-slate-400 hover:bg-slate-100 dark:hover:bg-white/10 hover:text-red-500 rounded-full transition-all z-20">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>

                            <h3 class="text-2xl font-black text-slate-800 dark:text-white mb-8 flex items-center gap-4 tracking-tighter uppercase relative">
                                <div class="w-14 h-14 rounded-[20px] bg-orange-600 flex items-center justify-center text-white shadow-xl shadow-orange-600/30">
                                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                </div>
                                Vote
                            </h3>

                            <div class="space-y-8">
                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Topic / Question</label>
                                    <input type="text" x-model="pollQuestion" placeholder="What's our move?" 
                                        class="w-full bg-slate-100/50 dark:bg-white/5 border-none focus:ring-4 focus:ring-orange-500/20 rounded-3xl py-4.5 px-8 text-slate-900 dark:text-white text-base font-bold placeholder-slate-400 transition-all">
                                </div>

                                <div>
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3 block">Strategy Options</label>
                                    <div class="flex flex-col gap-3 max-h-52 overflow-y-auto pr-2 chat-scroll">
                                        <template x-for="(option, index) in pollOptions" :key="index">
                                            <div class="flex items-center gap-3 group animate-slide-up">
                                                <input type="text" x-model="pollOptions[index]" :placeholder="'Option ' + (index + 1)" 
                                                    class="flex-1 bg-slate-100/50 dark:bg-white/5 border-none focus:ring-4 focus:ring-orange-500/10 rounded-2xl py-3.5 px-6 text-slate-700 dark:text-slate-200 text-sm font-bold placeholder-slate-400/50 transition-all">
                                                <button @click="removePollOption(index)" x-show="pollOptions.length > 2" 
                                                    class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-500/5 rounded-xl transition-all active:scale-90">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    <button @click="addPollOption" x-show="pollOptions.length < 10" 
                                        class="mt-6 flex items-center gap-2 text-orange-600 hover:text-orange-500 font-black text-[10px] uppercase tracking-widest transition-all hover:translate-x-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                        Add New Target
                                    </button>
                                </div>
                            </div>

                            <div class="mt-8">
                                <button @click="sendPoll" :disabled="!pollQuestion.trim() || pollOptions.filter(o => o.trim()).length < 2" 
                                    class="w-full py-4.5 bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-500 hover:to-red-500 disabled:opacity-30 disabled:grayscale text-white rounded-[24px] text-[12px] font-black uppercase tracking-[0.2em] shadow-2xl shadow-orange-600/30 transition-all active:scale-95">
                                    Initiate Poll
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="glass-surface rounded-[40px] shadow-[0_20px_50px_rgba(0,0,0,0.1)] dark:shadow-[0_20px_50px_rgba(0,0,0,0.3)] border-white/40 dark:border-white/5 transition-all duration-500 overflow-visible">
                    {{-- Reply Preview --}}
                    <div x-show="replyMessage" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="-translate-y-4 opacity-0" x-transition:enter-end="translate-y-0 opacity-100" class="px-8 py-4 border-b border-white/20 dark:border-white/5 bg-blue-500/5 relative rounded-t-[40px]">
                        <div class="flex items-center gap-4">
                            <div class="w-1 h-10 bg-blue-500 rounded-full shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[12px] font-black text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-0.5" x-text="replyMessage?.user?.name"></p>
                                <p class="text-[13px] text-slate-500 dark:text-slate-400 truncate pr-10" x-text="replyMessage?.message || '[Voice Note]'"></p>
                            </div>
                            <button @click="cancelReply" class="p-2 text-slate-400 hover:text-red-500 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div class="p-3 lg:p-4 flex items-center gap-2 lg:gap-4 relative">

                        {{-- Voice UI Overlay --}}
                        <div x-show="isRecording" x-cloak class="absolute inset-x-2 inset-y-2 bg-blue-600 flex items-center px-4 lg:px-8 gap-4 z-40 rounded-[32px] shadow-2xl shadow-blue-600/30">
                            <div class="flex items-center gap-4 flex-1">
                                <div class="flex items-center gap-1">
                                    <span class="w-1.5 h-6 bg-white animate-soundwave [animation-delay:0.1s]"></span>
                                    <span class="w-1.5 h-4 bg-white/60 animate-soundwave [animation-delay:0.2s]"></span>
                                    <span class="w-1.5 h-8 bg-white animate-soundwave [animation-delay:0.3s]"></span>
                                </div>
                                <span class="text-white font-black text-xs lg:text-sm tracking-widest uppercase truncate" x-text="'RECORDING... ' + formattedTime"></span>
                            </div>
                            <div class="flex items-center gap-4 lg:gap-8">
                                <button @click="stopRecording(false)" class="text-white/70 hover:text-white font-black text-[10px] lg:text-xs uppercase tracking-widest transition-colors">Abort</button>
                                <button @click="stopRecording(true)" class="w-10 h-10 lg:w-12 lg:h-12 bg-white text-blue-600 rounded-2xl flex items-center justify-center shadow-xl hover:scale-110 active:scale-95 transition-all">
                                    <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="flex items-center gap-1 shrink-0">
                            {{-- Hidden File Input --}}
                            <input type="file" x-ref="fileInput" class="hidden" @change="handleFile">

                            {{-- Attachment Menu --}}
                            <div class="relative" @click.away="attachmentOpen = false">
                                <button type="button" @click="attachmentOpen = !attachmentOpen" 
                                    class="p-2.5 lg:p-3.5 text-slate-400 hover:text-blue-500 hover:bg-blue-500/5 dark:hover:bg-blue-500/10 rounded-2xl transition-all active:scale-90"
                                    :class="attachmentOpen ? 'rotate-45 !text-blue-500 bg-blue-500/10' : ''">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                </button>

                                <div x-show="attachmentOpen" x-cloak
                                    x-transition:enter="transition ease-out duration-300 transform"
                                    x-transition:enter-start="opacity-0 translate-y-12 scale-90"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    class="absolute bottom-[calc(100%+12px)] left-0 w-56 glass-surface rounded-[32px] shadow-2xl p-4 z-50 grid grid-cols-1 gap-2 border-white/20">
                                    
                                    <button @click="triggerFile('document')" class="flex items-center gap-4 p-3.5 hover:bg-slate-100/50 dark:hover:bg-white/5 rounded-2xl transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-purple-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        </div>
                                        <span class="text-sm font-black text-slate-600 dark:text-slate-300">Docs</span>
                                    </button>

                                    <button @click="triggerFile('text')" class="flex items-center gap-4 p-3.5 hover:bg-slate-100/50 dark:hover:bg-white/5 rounded-2xl transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        </div>
                                        <span class="text-sm font-black text-slate-600 dark:text-slate-300">Media</span>
                                    </button>

                                    <button @click="openPoll" class="flex items-center gap-4 p-3.5 hover:bg-slate-100/50 dark:hover:bg-white/5 rounded-2xl transition-all group">
                                        <div class="w-10 h-10 rounded-xl bg-orange-600 flex items-center justify-center text-white shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        </div>
                                        <span class="text-sm font-black text-slate-600 dark:text-slate-300">Poll</span>
                                    </button>
                                </div>
                            </div>

                            <button type="button" @click="emojiPickerOpen = !emojiPickerOpen" 
                                class="p-2.5 lg:p-3.5 text-slate-400 hover:text-blue-500 hover:bg-blue-500/5 dark:hover:bg-blue-500/10 rounded-2xl transition-all active:scale-90">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </button>
                        </div>
                        
                        <form @submit.prevent="sendMessage" class="flex-1 flex items-center gap-4">
                                <input 
                                    x-ref="chatInput"
                                    type="text" 
                                    x-model="newMessageContent"
                                    @input="handleTyping"
                                    @keydown="handleMentionKeys"
                                    placeholder="Type a message..." 
                                    class="flex-1 w-full min-w-0 bg-transparent border-none focus:ring-0 text-[15px] font-bold text-slate-900 dark:text-white placeholder-slate-400 py-3 transition-all"
                                    :disabled="loading"
                                    autocomplete="off"
                                >
                            
                            <div class="flex items-center gap-2 shrink-0">
                                <template x-if="!newMessageContent.trim()">
                                    <button type="button" @click="startRecording()" 
                                        class="p-2.5 lg:p-3.5 text-slate-400 hover:text-red-500 hover:bg-red-500/5 rounded-2xl transition-all hover:scale-110 active:scale-90">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                    </button>
                                </template>

                                <button 
                                    type="submit" 
                                    x-show="newMessageContent.trim().length > 0"
                                    class="bg-blue-600 hover:bg-blue-700 text-white p-3 lg:p-3.5 rounded-2xl transition-all duration-300 disabled:opacity-50 shadow-xl shadow-blue-600/20 active:scale-95 flex items-center justify-center min-w-[48px]"
                                    :disabled="loading"
                                >
                                    <svg x-show="!loading" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 12h14M12 5l7 7-7 7"/></svg>
                                    <svg x-show="loading" class="animate-spin h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function vibrantChat() {
        return {
            newMessageContent: '',
            replyMessage: null,
            emojiPickerOpen: false,
            pickerTab: 'emoji', // emoji, sticker
            pickerSearch: '',
            emojis: ['😊', '😂', '🤣', '❤️', '👍', '🙏', '🔥', '🚀', '✨', '🙌', '🎉', '💡', '✅', '❌', '👀', '🤫', '😎', '😜', '💪', '🤝', '🎈', '🎁', '🍕', '🍔', '🍺', '🍟', '🍦', '🍰', '🍩', '🍪', '🍫', '🍬', '🍭', '🍓', '🍒', '🍑', '🍇', '🍉', '☀️', '🌈', '⭐', '🍀', '💎'],
            stickers: [
                { id: 1, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271062.png', default: true },
                { id: 2, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271040.png', default: true },
                { id: 3, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271068.png', default: true },
                { id: 4, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271036.png', default: true },
                { id: 5, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271060.png', default: true },
                { id: 6, url: 'https://cdn-icons-png.flaticon.com/512/2271/2271058.png', default: true },
            ],
            userStickers: @json($userStickers),
            allMessages: @json($messages),
            users: @json($users),
            userSearch: '',
            onlineUsers: [],
            typingUsers: [],
            toggleSidebar: window.innerWidth >= 1024,
            loading: false,
            showScrollToBottom: false,
            unreadCount: 0,
            mentionOpen: false,
            mentionFilter: '',
            mentionIndex: 0,
            activeMsgId: null,

            // Attachment & Poll States
            attachmentOpen: false,
            pollModalOpen: false,
            pollQuestion: '',
            pollOptions: ['', ''],
            isUploading: false,
            uploadType: 'text',
            uploadPreview: null,
            selectedFile: null,

            // Voice States
            isRecording: false,
            mediaRecorder: null,
            audioChunks: [],
            timer: 0,
            timerInterval: null,

            init() {
                this.allMessages = this.allMessages.map(m => this.decorateMessage(m));
                this.scrollToBottom();
                
                let attempts = 0;
                const maxAttempts = 10;
                
                this.audioNotification = new Audio('/sounds/notif chat.mp3');
                
                const initEcho = () => {
                    if (typeof Echo !== 'undefined') {
                        Echo.join('chat')
                            .here(u => {
                                console.log('Online users:', u);
                                this.onlineUsers = u;
                            })
                            .joining(u => {
                                console.log('User joining:', u);
                                this.onlineUsers.push(u);
                            })
                            .leaving(u => {
                                console.log('User leaving:', u);
                                this.onlineUsers = this.onlineUsers.filter(ou => ou.id !== u.id);
                            })
                            .listen('MessageSent', e => {
                                if (e.message.user_id !== {{ auth()->id() }}) {
                                    this.allMessages.push(this.decorateMessage(e.message));
                                    
                                    // Play Sound Notification
                                    this.audioNotification.play().catch(err => console.log('Audio play failed:', err));

                                    if (this.showScrollToBottom) {
                                        this.unreadCount++;
                                    } else {
                                        this.scrollToBottom();
                                    }
                                }
                            })
                            .listen('MessageDeleted', e => this.allMessages = this.allMessages.filter(m => m.id !== e.messageId))
                            .listen('PollVoted', e => {
                                console.log('Poll updated:', e.poll);
                                const msg = this.allMessages.find(m => m.poll_id === e.poll.id);
                                if (msg) msg.poll = e.poll;
                            })
                            .listenForWhisper('typing', user => {
                                if (!this.typingUsers.find(tu => tu.id === user.id) && user.id !== {{ auth()->id() }}) {
                                     this.typingUsers.push(user);
                                }
                                
                                // Reset timeout for this user
                                if (this.typingTimeouts[user.id]) clearTimeout(this.typingTimeouts[user.id]);
                                
                                this.typingTimeouts[user.id] = setTimeout(() => {
                                    this.typingUsers = this.typingUsers.filter(tu => tu.id !== user.id);
                                    delete this.typingTimeouts[user.id];
                                }, 3000);
                            });
                    } else if (attempts < maxAttempts) {
                        attempts++;
                        setTimeout(initEcho, 500);
                    }
                };

                initEcho();

                // Scroll detection for "Scroll to Bottom" button
                if (this.$refs.messageContainer) {
                    this.$refs.messageContainer.addEventListener('scroll', () => {
                        const el = this.$refs.messageContainer;
                        this.showScrollToBottom = (el.scrollHeight - el.scrollTop - el.clientHeight) > 300;
                        if (!this.showScrollToBottom) {
                            this.unreadCount = 0;
                        }
                    });
                }
            },

            get formattedTime() {
                const mins = Math.floor(this.timer / 60);
                const secs = this.timer % 60;
                return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
            },
            
            // Typing Logic
            typingTimeouts: {},
            lastTypingTime: 0,
            
            handleTyping() {
                const now = Date.now();
                if (now - this.lastTypingTime > 2000) { 
                    if (typeof Echo !== 'undefined') {
                        Echo.join('chat').whisper('typing', {
                            id: {{ auth()->id() }},
                            name: {{ \Illuminate\Support\Js::from(auth()->user()->name) }}
                        });
                        this.lastTypingTime = now;
                    }
                }

                // Mention Logic
                const val = this.newMessageContent;
                const lastWord = val.split(' ').pop();
                if (lastWord.startsWith('@')) {
                    this.mentionOpen = true;
                    this.mentionFilter = lastWord.substring(1).toLowerCase();
                    this.mentionIndex = 0;
                } else {
                    this.mentionOpen = false;
                }
            },

            async startRecording() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    this.mediaRecorder = new MediaRecorder(stream);
                    this.audioChunks = [];
                    this.isRecording = true;
                    this.timer = 0;
                    this.timerInterval = setInterval(() => this.timer++, 1000);

                    this.mediaRecorder.ondataavailable = e => this.audioChunks.push(e.data);
                    this.mediaRecorder.onstop = () => this.handleRecordingStop();
                    this.mediaRecorder.start();
                } catch (err) {
                    alert('Microphone access denied or error: ' + err.message);
                }
            },

            stopRecording(send = true) {
                if (!this.isRecording) return;
                clearInterval(this.timerInterval);
                this.isRecording = false;
                this.shouldSendRecording = send;
                this.mediaRecorder.stop();
                this.mediaRecorder.stream.getTracks().forEach(t => t.stop());
            },

            handleRecordingStop() {
                if (!this.shouldSendRecording) return;
                const blob = new Blob(this.audioChunks, { type: 'audio/webm' });
                this.uploadVoiceNote(blob);
            },

            uploadVoiceNote(blob) {
                this.loading = true;
                const formData = new FormData();
                formData.append('audio', blob, 'vn.webm');

                fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        this.allMessages.push(this.decorateMessage(d.message));
                        this.scrollToBottom();
                    }
                })
                .catch(e => console.error(e))
                .finally(() => this.loading = false);
            },

            get filteredEmojis() {
                if (!this.pickerSearch) return this.emojis;
                return this.emojis.filter(e => e.includes(this.pickerSearch));
            },
            get filteredUsers() { return this.users.filter(u => u.name.toLowerCase().includes(this.userSearch.toLowerCase())); },
            get groupedMessages() {
                const groups = {};
                this.allMessages.forEach(m => {
                    const d = new Date(m.created_at).toDateString();
                    if (!groups[d]) groups[d] = [];
                    groups[d].push(m);
                });
                return groups;
            },
            decorateMessage(m) { return { ...m, isEditing: false, editText: m.message, renderedMessage: this.renderMessage(m.message) }; },
            renderMessage(text) {
                if (!text) return '';
                // Highlight @everyone and @UserNames with premium glows
                let rendered = text.replace(/@everyone/gi, '<span class="px-2 py-0.5 rounded-lg bg-orange-500/10 text-orange-500 font-black ring-1 ring-orange-500/30 animate-pulse tracking-tight shadow-[0_0_15px_rgba(249,115,22,0.2)] hover:shadow-[0_0_20px_rgba(249,115,22,0.4)] transition-all cursor-default">@everyone</span>');
                
                this.users.forEach(u => {
                    const regex = new RegExp('@' + u.name.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'gi');
                    rendered = rendered.replace(regex, `<span class="px-2 py-0.5 rounded-lg bg-blue-500/10 text-blue-500 font-black ring-1 ring-blue-500/30 tracking-tight shadow-[0_0_15px_rgba(59,130,246,0.2)] hover:shadow-[0_0_20px_rgba(59,130,246,0.4)] transition-all cursor-pointer">@${u.name}</span>`);
                });
                return rendered;
            },
            formatTime(s) { return new Date(s).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}); },
            formatDateHeader(s) {
                const d = new Date(s);
                const t = new Date().toDateString();
                const y = new Date(Date.now() - 86400000).toDateString();
                if (s === t) return 'TODAY';
                if (s === y) return 'YESTERDAY';
                return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }).toUpperCase();
            },
            getRoleStyles(role) {
                const r = role ? role.toLowerCase() : '';
                if (r === 'super admin') return { badge: 'bg-gradient-to-r from-purple-600 to-pink-600', avatar: 'from-purple-600 to-pink-600', glow: 'shadow-[0_0_20px_rgba(168,85,247,0.4)]', text: 'text-purple-400' };
                if (r === 'admin' || r === 'ketua') return { badge: 'bg-gradient-to-r from-blue-600 to-cyan-500', avatar: 'from-blue-600 to-cyan-500', glow: 'shadow-[0_0_20px_rgba(37,99,235,0.4)]', text: 'text-blue-400' };
                if (r === 'pengurus' || r === 'wakil') return { badge: 'bg-gradient-to-r from-emerald-500 to-teal-500', avatar: 'from-emerald-500 to-teal-500', glow: 'shadow-[0_0_20px_rgba(16,185,129,0.4)]', text: 'text-emerald-400' };
                return { badge: 'bg-slate-500', avatar: 'from-slate-500 to-slate-700', glow: '', text: 'text-slate-400' };
            },
            isOnline(id) { return this.onlineUsers.some(u => u.id === id); },
            addEmoji(emoji) { this.newMessageContent += emoji; this.emojiPickerOpen = false; },
            setReply(msg) {
                this.replyMessage = msg;
                this.$nextTick(() => this.$refs.chatInput?.focus());
            },
            cancelReply() {
                this.replyMessage = null;
            },
            jumpToMessage(id) {
                const el = document.getElementById('chat-message-' + id);
                if (el) {
                    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    el.classList.add('ring-4', 'ring-blue-500', 'ring-opacity-70', 'scale-105');
                    setTimeout(() => {
                        el.classList.remove('ring-4', 'ring-blue-500', 'ring-opacity-70', 'scale-105');
                    }, 2000);
                }
            },
            // Removed duplicate handleTyping
            
            get filteredMentions() {
                let list = [{ id: 'everyone', name: 'everyone', jabatan: 'SYSTEM' }, ...this.users];
                if (this.mentionFilter) {
                    list = list.filter(u => u.name.toLowerCase().includes(this.mentionFilter));
                }
                return list.slice(0, 8); // Limit to 8
            },

            selectMention(user) {
                const words = this.newMessageContent.split(' ');
                words.pop(); // Remove the @part
                words.push('@' + user.name + ' ');
                this.newMessageContent = words.join(' ');
                this.mentionOpen = false;
                this.$refs.chatInput.focus();
            },

            handleMentionKeys(e) {
                if (!this.mentionOpen) return;
                
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    this.mentionIndex = (this.mentionIndex + 1) % this.filteredMentions.length;
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    this.mentionIndex = (this.mentionIndex - 1 + this.filteredMentions.length) % this.filteredMentions.length;
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    this.selectMention(this.filteredMentions[this.mentionIndex]);
                } else if (e.key === 'Escape') {
                    this.mentionOpen = false;
                }
            },
            
            sendMessage() {
                if (!this.newMessageContent.trim() || this.loading) return;
                this.loading = true;
                const parentId = this.replyMessage ? this.replyMessage.id : null;
                
                const formData = new FormData();
                formData.append('message', this.newMessageContent);
                formData.append('parent_id', parentId || '');
                formData.append('type', 'text');

                fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                }).then(r => r.json()).then(d => {
                    if (d.status === 'success') { 
                        this.allMessages.push(this.decorateMessage(d.message)); 
                        this.scrollToBottom(); 
                        this.newMessageContent = '';
                        this.replyMessage = null;
                        this.attachmentOpen = false;
                    }
                }).finally(() => this.loading = false);
            },

            // New Attachment & Poll Functions
            triggerFile(type) {
                this.uploadType = type;
                this.$refs.fileInput.click();
                this.attachmentOpen = false;
            },

            handleFile(e) {
                const file = e.target.files[0];
                if (!file) return;

                this.selectedFile = file;
                this.isUploading = true;

                const formData = new FormData();
                formData.append('file', file);
                formData.append('type', this.uploadType);
                formData.append('parent_id', this.replyMessage ? this.replyMessage.id : '');

                // Different route for permanent sticker creation
                const url = this.uploadType === 'sticker' ? "{{ route('chat.sticker.upload') }}" : "{{ route('chat.send') }}";

                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        if (this.uploadType === 'sticker') {
                            // Add to user stickers collection instead of messaging
                            this.userStickers.unshift(d.sticker);
                        } else {
                            this.allMessages.push(this.decorateMessage(d.message));
                            this.scrollToBottom();
                            this.replyMessage = null;
                        }
                    }
                })
                .finally(() => {
                    this.isUploading = false;
                    this.selectedFile = null;
                    e.target.value = '';
                });
            },

            openPoll() {
                this.pollModalOpen = true;
                this.attachmentOpen = false;
                this.pollQuestion = '';
                this.pollOptions = ['', ''];
            },

            addPollOption() {
                if (this.pollOptions.length < 10) this.pollOptions.push('');
            },

            removePollOption(index) {
                if (this.pollOptions.length > 2) this.pollOptions.splice(index, 1);
            },

            sendPoll() {
                if (!this.pollQuestion.trim() || this.pollOptions.filter(o => o.trim()).length < 2) return;

                this.loading = true;
                const formData = new FormData();
                formData.append('type', 'poll');
                formData.append('poll_question', this.pollQuestion);
                this.pollOptions.filter(o => o.trim()).forEach(opt => formData.append('poll_options[]', opt));

                fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        this.allMessages.push(this.decorateMessage(d.message));
                        this.scrollToBottom();
                        this.pollModalOpen = false;
                    }
                })
                .finally(() => this.loading = false);
            },

            votePoll(poll, optionId) {
                fetch(`/dashboard/chat/poll/${poll.id}/vote`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ option_id: optionId })
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        const msg = this.allMessages.find(m => m.poll_id === poll.id);
                        if (msg) msg.poll = d.poll;
                    }
                });
            },

            calculatePollPercentage(poll, optionId) {
                if (!poll || !poll.votes || poll.votes.length === 0) return 0;
                const optionVotes = poll.votes.filter(v => v.poll_option_id === optionId).length;
                return Math.round((optionVotes / poll.votes.length) * 100);
            },

            getOptionVoteCount(poll, optionId) {
                if (!poll || !poll.votes) return 0;
                return poll.votes.filter(v => v.poll_option_id === optionId).length;
            },

            hasVoted(poll, optionId) {
                if (!poll || !poll.votes) return false;
                return poll.votes.some(v => v.poll_option_id === optionId && v.user_id == {{ auth()->id() }});
            },

            sendSticker(url) {
                this.loading = true;
                const formData = new FormData();
                formData.append('type', 'sticker');
                formData.append('sticker_url', url); // Backend will handle this as a path
                // Note: ChatController sendMessage expects 'file' for uploads, 
                // but if we send a 'sticker_url' it might need a small tweak 
                // or we just reuse the 'file' logic if it's already uploaded.
                // For now, I'll send it as a message content or file_path via a dedicated field.
                
                // Let's assume we send 'message' as the URL for stickers if it's pre-defined
                formData.append('message', '[Stiker]');
                formData.append('sticker_url', url);

                fetch("{{ route('chat.send') }}", {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: formData
                })
                .then(r => r.json())
                .then(d => {
                    if (d.status === 'success') {
                        this.allMessages.push(this.decorateMessage(d.message));
                        this.scrollToBottom();
                        this.emojiPickerOpen = false;
                    }
                })
                .finally(() => this.loading = false);
            },

            startEdit(m) { m.isEditing = true; m.editText = m.message; },
            cancelEdit(m) { m.isEditing = false; },
            saveEdit(m) {
                if (!m.editText.trim() || m.editText === m.message) return this.cancelEdit(m);
                const old = m.message; m.message = m.editText; m.isEditing = false;
                fetch(`/dashboard/chat/${m.id}/edit`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ _method: 'PUT', message: m.editText })
                }).catch(() => m.message = old);
            },
            deleteMessage(id) {
                if (!confirm('Delete this message?')) return;
                const old = [...this.allMessages]; this.allMessages = this.allMessages.filter(m => m.id !== id);
                fetch(`/dashboard/chat/${id}/delete`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ _method: 'DELETE' })
                }).catch(() => this.allMessages = old);
            },
            scrollToBottom(smooth = false) {
                this.unreadCount = 0;
                this.$nextTick(() => {
                    const c = this.$refs.messageContainer;
                    if (c) {
                        c.scrollTo({
                            top: c.scrollHeight,
                            behavior: smooth ? 'smooth' : 'auto'
                        });
                    }
                });
            }
        }
    }

    function waveformPlayer(src) {
        return {
            audio: null,
            isPlaying: false,
            duration: 0,
            currentTime: 0,
            progress: 0,
            init() {
                this.audio = new Audio(src);
                this.audio.addEventListener('loadedmetadata', () => {
                    this.duration = this.audio.duration;
                });
                this.audio.addEventListener('timeupdate', () => {
                    this.currentTime = this.audio.currentTime;
                    this.progress = this.duration > 0 ? this.currentTime / this.duration : 0;
                });
                this.audio.addEventListener('ended', () => {
                    this.isPlaying = false;
                    this.currentTime = 0;
                    this.progress = 0;
                });
            },
            togglePlay() {
                if (this.isPlaying) {
                    this.audio.pause();
                    this.isPlaying = false;
                } else {
                    this.audio.play();
                    this.isPlaying = true;
                }
            },
            seek(e) {
                if (!this.duration) return;
                const rect = e.currentTarget.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const percentage = Math.max(0, Math.min(1, x / rect.width));
                this.audio.currentTime = percentage * this.duration;
            },
            formatTime(sec) {
                if (!sec || isNaN(sec)) return "0:00";
                const m = Math.floor(sec / 60);
                const s = Math.floor(sec % 60);
                return `${m}:${s.toString().padStart(2, '0')}`;
            }
        }
    }
</script>
@endpush
</x-admin-layout>
