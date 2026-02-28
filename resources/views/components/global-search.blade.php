<div x-data="{ 
    q: '', 
    open: false, 
    results: [], 
    loading: false,
    searchTimeout: null,
    async search() {
        if (this.searchTimeout) clearTimeout(this.searchTimeout);
        
        this.searchTimeout = setTimeout(async () => {
            if (this.q.length < 2) {
                this.results = [];
                return;
            }
            this.loading = true;
            this.open = true;
            
            try {
                const url = `{{ route('dashboard.search') }}?q=${encodeURIComponent(this.q)}`;
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    credentials: 'same-origin'
                });
                
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                const data = await response.json();
                this.results = data.results || [];
            } catch (error) {
                console.error('Search error:', error);
                this.results = [];
            } finally {
                this.loading = false;
            }
        }, 300);
    },
    handleGlobalSlash(e) {
        if (!this.$refs.searchInput) return;
        this.$refs.searchInput.focus();
    }
}"
     x-init="$watch('q', value => search())"
     @keydown.window.prevent.slash="handleGlobalSlash($event)"
    class="relative flex-1 max-w-xl hidden lg:block">

    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-brand transition-colors">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <input 
            x-ref="searchInput"
            type="text" 
            x-model="q" 
            @focus="if (q.length >= 2) open = true"
            @click.away="open = false"
            @keydown.escape="open = false; $refs.searchInput?.blur()"
            placeholder="Cari kegiatan, pengumuman, anggota, dll..." 
            class="w-full pl-12 pr-12 py-3 bg-white/50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-700/60 rounded-xl text-[14px] font-medium text-slate-900 dark:text-slate-100 placeholder-slate-400 focus:bg-white dark:focus:bg-slate-800/80 focus:outline-none focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all shadow-sm"
            autocomplete="off"
        >
        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
            <template x-if="loading">
                <svg class="animate-spin h-3 w-3 text-brand" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </template>
            <template x-if="!loading">
                <span class="text-[10px] font-mono bg-white dark:bg-slate-700 px-1.5 py-0.5 rounded border border-slate-200 dark:border-slate-600 text-slate-400">/</span>
            </template>
        </div>
    </div>

    {{-- Results Dropdown --}}
    <div x-show="open && (loading || results.length > 0 || q.length >= 2)" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-2xl z-50 max-h-[32rem] overflow-hidden flex flex-col"
         x-cloak>
        
        <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
            <template x-if="loading">
                <div class="p-8 text-center">
                    <svg class="animate-spin h-6 w-6 mx-auto mb-2 text-brand" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mencari Data...</p>
                </div>
            </template>

            <template x-if="!loading && results.length === 0 && q.length >= 2">
                <div class="p-8 text-center">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Tidak ada hasil ditemukan</p>
                </div>
            </template>

            <template x-for="item in results" :key="item.url">
                <a :href="item.url" class="group flex items-center gap-3 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <div class="h-8 w-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 group-hover:bg-brand/10 transition-colors">
                        <span class="text-xs font-bold text-slate-500 group-hover:text-brand uppercase" x-text="item.type.substring(0, 1)"></span>
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-slate-900 dark:text-white truncate" x-text="item.title"></p>
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400" x-text="item.type"></span>
                        </div>
                        <p class="text-[10px] text-slate-500 truncate mt-0.5" x-text="item.subtitle"></p>
                    </div>
                </a>
            </template>
        </div>
        
        <div class="p-3 bg-slate-50 dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Tekan ESC untuk menutup</span>
            <span class="text-[9px] font-black text-brand uppercase tracking-widest" x-text="results.length + ' Hasil ditemukan'"></span>
        </div>
    </div>
</div>
