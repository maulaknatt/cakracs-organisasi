<x-admin-layout title="Social Lounge" :noPadding="true">
    <div x-data="{ micEnabled: true }" 
    class="flex-1 h-full bg-slate-50 dark:bg-slate-950 flex flex-col overflow-hidden transition-colors duration-300 relative">
        
        <div class="flex-1 flex flex-col lg:flex-row p-4 lg:p-6 gap-6 overflow-y-auto lg:overflow-hidden max-h-full">
            
            {{-- SIDEBAR: CHANNEL LIST (Discord Style) --}}
            <div class="w-full lg:w-[320px] flex flex-col bg-white dark:bg-[#11131D] rounded-[32px] border border-slate-200 dark:border-white/5 shadow-xl relative flex-shrink-0 min-h-[400px] lg:max-h-full overflow-hidden transition-all duration-300 z-10">
                
                {{-- Header --}}
                <div class="p-6 flex items-center justify-between border-b border-slate-100 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-xl bg-blue-100 dark:bg-blue-600/20 flex items-center justify-center text-blue-600 dark:text-blue-500 shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>
                        <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-widest">Saluran Suara</h2>
                    </div>
                </div>

                {{-- List --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-3 custom-scrollbar pb-24 lg:pb-3">
                    @foreach($channels as $channel)
                    <div class="flex flex-col gap-2">
                        {{-- Channel Button --}}
                        <div class="group/channel" @click="$store.voice.joinChannel({ id: {{ $channel->id }}, name: '{{ $channel->name }}' })">
                            <div class="flex items-center gap-3 px-4 py-4 rounded-[22px] cursor-pointer transition-all duration-500 relative border-2 border-transparent"
                                :class="$store.voice.connectedChannel?.id == {{ $channel->id }} ? 'bg-blue-600 text-white shadow-[0_10px_30px_rgba(37,99,235,0.4)]' : 'hover:bg-slate-50 dark:hover:bg-white/5 text-slate-500 dark:text-slate-400'">
                                <span class="font-black text-[13px] tracking-tight flex-1 truncate uppercase">{{ $channel->name }}</span>
                                <template x-if="$store.voice.connectedChannel?.id == {{ $channel->id }}">
                                    <div class="flex gap-0.5 shrink-0">
                                        <div class="w-1 h-3 bg-white/40 rounded-full animate-[soundwave_0.8s_infinite]"></div>
                                        <div class="w-1 h-3 bg-white rounded-full animate-[soundwave_0.8s_infinite_0.2s]"></div>
                                        <div class="w-1 h-3 bg-white/40 rounded-full animate-[soundwave_0.8s_infinite_0.4s]"></div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        {{-- Participants in Sidebar --}}
                        <div x-show="($store.voice.participants[{{ $channel->id }}] || []).length > 0" 
                             class="ml-8 space-y-3 py-2 transition-all duration-500">
                            <template x-for="user in $store.voice.participants[{{ $channel->id }}]" :key="user.uid">
                                <div class="flex items-center justify-between pr-4 group/user" x-cloak>
                                    <div class="flex items-center gap-3">
                                        <div class="relative shrink-0">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 dark:bg-white/10 flex items-center justify-center text-xs font-black overflow-hidden border-2 border-white dark:border-[#11131D] transition-all duration-500 shadow-sm"
                                                 :class="user.speaking ? 'ring-2 ring-green-500 ring-offset-2 scale-110 shadow-[0_0_15px_rgba(34,197,94,0.4)]' : ''">
                                                <template x-if="user.avatar">
                                                    <img :src="user.avatar" class="w-full h-full object-cover">
                                                </template>
                                                <template x-if="!user.avatar">
                                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 to-blue-600 text-white">
                                                        <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <template x-if="user.speaking">
                                                <div class="absolute inset-0 rounded-full bg-green-500/30 animate-ping z-[-1]"></div>
                                            </template>
                                        </div>
                                        <span x-show="$store.voice.connectedChannel?.id == {{ $channel->id }}"
                                              class="text-[12px] font-black truncate tracking-tight transition-colors duration-500" 
                                              :class="user.speaking ? 'text-green-500' : 'text-slate-700 dark:text-slate-300'"
                                              x-text="user.name"></span>
                                    </div>
                                    <template x-if="user.muted">
                                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 16 16"><path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3"/><path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z"/></svg>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- User Control Bar (Discord Style) --}}
                <div class="px-5 py-4 bg-slate-50 dark:bg-black/20 border-t border-slate-100 dark:border-white/5 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3 min-w-0 flex-1">
                        <div class="relative flex-shrink-0">
                            <div class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white font-black text-sm shadow-xl shadow-blue-500/20 overflow-hidden">
                                @if(auth()->user()->foto_profil)
                                    <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" class="w-full h-full object-cover">
                                @else
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-green-500 border-2 border-white dark:border-[#11131D]"></div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-black text-slate-900 dark:text-white truncate tracking-tight">{{ auth()->user()->name }}</p>
                            <p class="mt-0.5 text-[9px] font-bold text-slate-500 uppercase tracking-widest leading-none">
                                <span x-show="!$store.voice.connectedChannel">Not Connected</span>
                                <span x-show="$store.voice.connectedChannel" class="text-blue-500">Connected</span>
                            </p>
                        </div>
                    </div>
                    
                    {{-- Controls --}}
                    <div class="flex items-center gap-1">
                        <button @click="$store.voice.toggleCamera()" 
                                class="p-2 rounded-lg transition-all hover:bg-slate-200 dark:hover:bg-white/10"
                                :class="$store.voice.cameraEnabled ? 'text-blue-500 bg-blue-50 dark:bg-blue-500/10' : 'text-slate-500 dark:text-slate-400'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        <button @click="$store.voice.toggleMute()" 
                                class="p-2 rounded-lg transition-all hover:bg-slate-200 dark:hover:bg-white/10"
                                :class="$store.voice.muted ? 'text-red-500 bg-red-50 dark:bg-red-500/10' : 'text-slate-500 dark:text-slate-400'">
                            <template x-if="!$store.voice.muted">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                            </template>
                            <template x-if="$store.voice.muted">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 16 16"><path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3"/><path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z"/></svg>
                            </template>
                        </button>
                        {{-- Deafen --}}
                        <button @click="$store.voice.toggleDeaf()" 
                                class="p-2 rounded-lg transition-all hover:bg-slate-200 dark:hover:bg-white/10"
                                :class="$store.voice.deafened ? 'text-red-500 bg-red-50 dark:bg-red-500/10' : 'text-slate-500 dark:text-slate-400'">
                            <template x-if="!$store.voice.deafened">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9m0 12.728M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z"/></svg>
                            </template>
                            <template x-if="$store.voice.deafened">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" />
                                </svg>
                            </template>
                        </button>
                        <button @click="$store.voice.disconnect()" 
                                x-show="$store.voice.connectedChannel"
                                class="p-2 rounded-lg text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- MAIN PANEL --}}
            <div class="flex-1 flex flex-col relative min-h-[500px] lg:h-full overflow-hidden">
                 <div x-show="$store.voice.connectedChannel" 
                      class="flex-1 flex flex-col bg-slate-50 dark:bg-[#0b0c14] rounded-[40px] shadow-2xl overflow-hidden relative border border-slate-200 dark:border-white/5 p-6 lg:p-8">
                    
                    {{-- Grid of Avatars --}}
                    <div class="flex-1 overflow-y-auto custom-scrollbar min-h-[300px] mb-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-8">
                            <template x-for="user in $store.voice.participants[$store.voice.connectedChannel?.id] || []" :key="user.uid">
                                <div class="flex flex-col items-center gap-4 transition-all duration-500"
                                     :class="user.speaking ? 'scale-110' : 'opacity-80 scale-100'">
                                    
                                    <div class="relative">
                                        {{-- Round Avatar / Camera Feed --}}
                                        <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-2xl font-black overflow-hidden border-4 transition-all duration-300 shadow-2xl relative z-10"
                                             :class="user.speaking ? 'border-green-500 ring-4 ring-green-500/50 scale-105 shadow-[0_0_25px_rgba(34,197,94,0.6)]' : 'border-slate-200 dark:border-white/10'">
                                            
                                            <template x-if="user.cameraActive">
                                                <video x-init="$store.voice.attachVideo(user, $el)" autoplay playsinline class="w-full h-full object-cover scale-x-[-1]"></video>
                                            </template>

                                            <template x-if="!user.cameraActive">
                                                <div class="w-full h-full">
                                                    <template x-if="user.avatar">
                                                        <img :src="user.avatar" class="w-full h-full object-cover">
                                                    </template>
                                                    <template x-if="!user.avatar">
                                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-indigo-500 via-blue-600 to-blue-700 text-white">
                                                            <span x-text="user.name.charAt(0).toUpperCase()"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        {{-- Deep Speaking Glow Animation --}}
                                        <template x-if="user.speaking">
                                            <div class="absolute inset-0 rounded-full speaking-glow-circle z-0"></div>
                                        </template>
                                        
                                        {{-- Mute Icon --}}
                                        <template x-if="user.muted">
                                            <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center text-white shadow-xl border-2 border-slate-50 dark:border-[#0b0c14] z-20">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 16 16"><path d="M13 8c0 .564-.094 1.107-.266 1.613l-.814-.814A4 4 0 0 0 12 8V7a.5.5 0 0 1 1 0zm-5 4c.818 0 1.578-.245 2.212-.667l.718.719a5 5 0 0 1-2.43.923V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 1 0v1a4 4 0 0 0 4 4m3-9v4.879l-1-1V3a2 2 0 0 0-3.997-.118l-.845-.845A3.001 3.001 0 0 1 11 3"/><path d="m9.486 10.607-.748-.748A2 2 0 0 1 6 8v-.878l-1-1V8a3 3 0 0 0 4.486 2.607m-7.84-9.253 12 12 .708-.708-12-12z"/></svg>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <div class="text-center">
                                        <p class="text-[13px] font-black tracking-tight"
                                           :class="user.speaking ? 'text-green-500' : 'text-slate-700 dark:text-white/70'"
                                           x-text="user.name"></p>
                                        <div x-show="user.speaking" class="flex items-center justify-center gap-1 mt-1">
                                            <span class="w-1 h-3 bg-green-500 rounded-full animate-[soundwave_0.8s_infinite]"></span>
                                            <p class="text-[8px] font-black text-green-500 uppercase tracking-widest leading-none">Speaking</p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Connection Info & Controls Footer --}}
                    <div class="mt-auto pt-6 border-t border-slate-200 dark:border-white/5 flex flex-col gap-6 md:flex-row items-center justify-between">
                        <div class="flex items-center gap-4 md:gap-6 w-full md:w-auto justify-center md:justify-start">
                            <div class="flex flex-col">
                                <span class="text-[8px] md:text-[9px] font-black text-slate-500 dark:text-white/30 uppercase tracking-[0.2em]">Connected to</span>
                                <span class="text-slate-900 dark:text-white font-black uppercase text-xs md:text-sm tracking-widest truncate max-w-[120px] md:max-w-none" x-text="$store.voice.connectedChannel?.name"></span>
                            </div>
                            <div class="h-6 md:h-8 w-px bg-slate-200 dark:bg-white/5"></div>
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 md:w-2 md:h-2 rounded-full bg-green-500 animate-pulse"></div>
                                <span class="text-[9px] md:text-[10px] font-bold text-green-500 uppercase tracking-widest whitespace-nowrap">Ultra Low Latency</span>
                            </div>
                        </div>
                        
                        {{-- Global Controls --}}
                        <div class="flex items-center gap-2 md:gap-4 w-full md:w-auto justify-center">
                             <button @click="$store.voice.toggleCamera()" 
                                     class="flex-1 md:flex-none flex items-center justify-center gap-2 md:gap-3 px-4 md:px-6 py-3 md:py-3.5 rounded-xl md:rounded-2xl transition-all duration-300 border"
                                     :class="$store.voice.cameraEnabled ? 'bg-blue-600 text-white border-blue-500 shadow-[0_10px_20px_rgba(37,99,235,0.3)]' : 'bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-white/10 hover:bg-slate-200 dark:hover:bg-white/10'">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[9px] md:text-[11px] font-black uppercase tracking-widest" x-text="$store.voice.cameraEnabled ? 'Camera ON' : 'Camera OFF'"></span>
                             </button>

                             <button @click="$store.voice.disconnect()" 
                                     class="flex-1 md:flex-none flex items-center justify-center gap-2 md:gap-3 px-4 md:px-8 py-3 md:py-3.5 rounded-xl md:rounded-2xl bg-red-500/10 text-red-500 border border-red-500/20 hover:bg-red-500 hover:text-white transition-all duration-300 group">
                                <svg class="w-4 h-4 md:w-5 md:h-5 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                <span class="text-[9px] md:text-[11px] font-black uppercase tracking-widest">Disconnect</span>
                             </button>
                        </div>
                    </div>
                    
                    {{-- Loading Overlay --}}
                    <div x-show="$store.voice.isConnecting && !$store.voice.isRestoring" class="absolute inset-0 bg-slate-50/90 dark:bg-[#0b0c14] flex flex-col items-center justify-center z-50 backdrop-blur-sm transition-opacity duration-700">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 border-4 border-blue-600/10 border-t-blue-600 rounded-full animate-spin"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-3 h-3 bg-blue-500 rounded-full animate-[ping_2s_infinite]"></div>
                            </div>
                        </div>
                        <h3 class="text-[12px] font-black text-slate-500 dark:text-white/40 uppercase tracking-[0.4em] animate-pulse">Connecting to LiveKit...</h3>
                    </div>
                 </div>

                 {{-- Placeholder --}}
                 <div x-show="!$store.voice.connectedChannel" class="flex-1 flex items-center justify-center p-12 bg-white dark:bg-[#11131D] rounded-[40px] border border-slate-200 dark:border-white/5 shadow-xl transition-all duration-700">
                    <div class="text-center space-y-10 group">
                        <div class="relative mx-auto">
                            <div class="w-48 h-48 rounded-full bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-1000 group-hover:rotate-12">
                                <div class="w-24 h-24 rounded-[2.5rem] bg-blue-100 dark:bg-blue-600/20 flex items-center justify-center text-blue-600 dark:text-blue-500 shadow-inner">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                                </div>
                            </div>
                            <div class="absolute -top-4 -right-4 w-12 h-12 bg-blue-500/10 rounded-full animate-pulse border border-blue-500/20"></div>
                            <div class="absolute -bottom-6 -left-6 w-16 h-16 bg-blue-600/5 rounded-full animate-bounce-slow border border-blue-600/10"></div>
                        </div>
                        <div class="space-y-4">
                            <h3 class="text-3xl font-black text-slate-900 dark:text-white uppercase tracking-[0.3em] font-sans text-center">Social Lounge</h3>
                            <div class="h-1 w-12 bg-blue-500 mx-auto rounded-full"></div>
                            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-[0.2em] max-w-[300px] mx-auto leading-loose text-center">Established a neural connection via LiveKit Cloud for real-time communication</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        window.userAvatars = {
            @foreach($users as $u)
                "{{ $u->id }}": "{{ $u->foto_profil ? asset('storage/'.$u->foto_profil) : '' }}",
            @endforeach
        };
        window.currentVoiceParticipants = @json($currentParticipants);
    </script>

    @push('styles')
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(0,0,0,0.1); border-radius: 10px; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); }

        @keyframes soundwave {
            0%, 100% { transform: scaleY(0.4); opacity: 0.3; }
            50% { transform: scaleY(1); opacity: 1; }
        }
        
        @keyframes speaking-glow-pulse {
            0%   { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); opacity: 0.7; }
            70%  { box-shadow: 0 0 0 15px rgba(34, 197, 94, 0); opacity: 0; }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); opacity: 0; }
        }
        
        .speaking-glow-circle {
            animation: speaking-glow-pulse 1.5s infinite;
        }

        .animate-bounce-slow { animation: bounce 3s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        
        [x-cloak] { display: none !important; }

        .speaking-border {
            box-shadow: 0 0 20px #4ade80, inset 0 0 20px #4ade80;
            border-color: #4ade80 !important;
        }
    </style>
    @endpush
</x-admin-layout>
