{{-- Grid Area --}}
<div class="flex-1 flex flex-col bg-transparent overflow-hidden h-full">
    {{-- Header --}}
    <div class="px-6 py-6 lg:px-8 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
             {{-- Collapse Button (Mobile) --}}
            <button @click="expanded = false" class="lg:hidden p-2 -ml-2 rounded-xl text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-white/10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            
            <div>
                <h1 class="text-xl lg:text-2xl font-black text-slate-900 dark:text-white tracking-tight flex items-center gap-3">
                    <span class="w-3 h-3 rounded-full bg-green-500 animate-pulse shadow-[0_0_15px_rgba(34,197,94,0.6)]"></span>
                    <span x-text="$store.voice.connectedChannel?.name"></span>
                </h1>
                <p class="mt-1 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                    <span x-text="($store.voice.participants[$store.voice.connectedChannel?.id] || []).length"></span> Peserta Aktif
                </p>
            </div>
        </div>
        
        {{-- Quick Actions --}}
        <div class="flex items-center gap-2 lg:gap-3">
            <button class="hidden sm:flex px-4 py-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-[10px] font-black uppercase tracking-widest text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/10 transition-colors shadow-sm">
                Undang Teman
            </button>
            <button class="p-2 rounded-xl bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </button>
        </div>
    </div>

    {{-- Grid Content --}}
    <div class="flex-1 overflow-y-auto p-4 lg:p-8 custom-scrollbar">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-5 gap-4 lg:gap-8 pb-32 lg:pb-8">
            <template x-for="user in ($store.voice.participants[$store.voice.connectedChannel?.id] || [])" :key="user.uid">
                <div class="group relative flex flex-col items-center justify-center p-4 transition-all duration-500 rounded-3xl hover:bg-slate-50 dark:hover:bg-white/[0.02] border border-transparent hover:border-slate-100 dark:hover:border-white/5">
                    
                    {{-- Avatar --}}
                    <div class="relative mb-4">
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full p-1.5 border-2 border-transparent relative z-10 transition-all duration-500"
                             :class="user.speaking ? 'border-green-500 shadow-[0_0_30px_rgba(34,197,94,0.3)] scale-105' : 'group-hover:border-slate-200 dark:group-hover:border-white/10'">
                             <div class="w-full h-full rounded-full bg-slate-100 dark:bg-slate-800 overflow-hidden relative shadow-inner">
                                <template x-if="user.avatar">
                                    <img :src="'/storage/' + user.avatar" class="w-full h-full object-cover">
                                </template>
                                <template x-if="!user.avatar">
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-black text-slate-300 dark:text-slate-600" x-text="user.name.substring(0,1).toUpperCase()"></div>
                                </template>
                             </div>
                        </div>
                        
                        {{-- Speaking Pulse --}}
                        <div x-show="user.speaking" class="absolute inset-0 rounded-full bg-green-500/20 animate-ping z-0 scale-110"></div>
                        
                        {{-- Mute Badge --}}
                        <div x-show="user.muted" class="absolute -bottom-1 -right-1 bg-red-500 text-white p-2 rounded-2xl shadow-xl border-2 border-white dark:border-[#181B28] z-20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/></svg>
                        </div>
                    </div>

                    {{-- Name --}}
                    <div class="text-center w-full min-w-0">
                        <h3 class="font-black text-slate-900 dark:text-white text-sm truncate" 
                            x-text="user.id == {{ auth()->id() }} ? user.name + ' (You)' : user.name"></h3>
                        <p x-show="user.speaking" class="text-[9px] font-black text-green-500 uppercase tracking-widest mt-1.5 animate-pulse">Speaking...</p>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>
