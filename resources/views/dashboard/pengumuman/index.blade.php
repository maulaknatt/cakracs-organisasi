<x-admin-layout title="Pengumuman">
    <div class="flex flex-col gap-8">
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in">
            <div>
                <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Pengumuman</h1>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Informasi terkini untuk seluruh anggota organisasi</p>
            </div>
            
            @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                <a href="{{ route('pengumuman.create') }}" data-turbo-frame="modal" 
                   class="btn-primary flex items-center justify-center gap-2 group">
                    <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    <span>Buat Baru</span>
                </a>
            @endif
        </div>

        {{-- Search & Filter Bar --}}
        <div x-data="{
                filterOpen: {{ (request('tahun') || request('sort_by') || request('pinned_only') || request('tanggal_dari') || request('tanggal_sampai')) ? 'true' : 'false' }},
                activeFilters: {{ (request('tahun') || request('sort_by') || request('pinned_only') || request('tanggal_dari') || request('tanggal_sampai')) ? 1 : 0 }}
             }"
             class="card-glass p-4 animate-fade-in" style="animation-delay: 100ms">

            <form action="{{ route('pengumuman.index') }}" method="GET" id="filter-form">
                {{-- Search Row --}}
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari pengumuman..."
                               class="w-full h-12 pl-12 pr-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none font-medium text-slate-700 dark:text-white">
                    </div>

                    <div class="flex gap-2 shrink-0">
                        {{-- Filter toggle button --}}
                        <button type="button" @click="filterOpen = !filterOpen"
                                :class="filterOpen || activeFilters > 0 ? 'bg-blue-500 text-white border-blue-500 hover:bg-blue-600' : 'btn-secondary text-slate-600 dark:text-slate-300'"
                                class="h-12 px-5 flex items-center gap-2 rounded-xl border font-semibold text-sm transition-all relative">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Filter</span>
                            @if(request('tahun') || request('sort_by') || request('pinned_only') || request('tanggal_dari') || request('tanggal_sampai'))
                                <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-rose-500 text-white text-[9px] font-black rounded-full flex items-center justify-center">
                                    {{ collect([request('tahun'), request('sort_by'), request('pinned_only'), request('tanggal_dari'), request('tanggal_sampai')])->filter()->count() }}
                                </span>
                            @endif
                        </button>

                        <button type="submit" class="btn-primary h-12 px-7 uppercase tracking-widest text-[12px] font-black">
                            Cari
                        </button>

                        @if(request()->hasAny(['search','tahun','sort_by','pinned_only','tanggal_dari','tanggal_sampai']))
                            <a href="{{ route('pengumuman.index') }}"
                               class="h-12 w-12 flex items-center justify-center rounded-xl border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-white/5 text-slate-400 hover:text-rose-500 hover:border-rose-300 dark:hover:border-rose-500/40 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all"
                               title="Reset semua filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- Filter Panel --}}
                <div x-show="filterOpen"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 -translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mt-4 pt-4 border-t border-slate-200 dark:border-white/8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">

                        {{-- Filter Tahun --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Tahun</label>
                            <select name="tahun"
                                    class="h-10 px-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Tanggal Dari --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Dari Tanggal</label>
                            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                                   class="h-10 px-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        {{-- Tanggal Sampai --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Sampai Tanggal</label>
                            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                                   class="h-10 px-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                        </div>

                        {{-- Urutkan --}}
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[11px] font-bold uppercase tracking-wider text-slate-400 dark:text-slate-500">Urutkan</label>
                            <select name="sort_by"
                                    class="h-10 px-3 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-xl text-sm font-medium text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all">
                                <option value="tanggal" {{ request('sort_by', 'tanggal') === 'tanggal' ? 'selected' : '' }}>Terbaru</option>
                                <option value="tanggal_asc" {{ request('sort_by') === 'tanggal_asc' ? 'selected' : '' }}>Terlama</option>
                                <option value="likes_count" {{ request('sort_by') === 'likes_count' ? 'selected' : '' }}>Paling Disukai</option>
                                <option value="comments_count" {{ request('sort_by') === 'comments_count' ? 'selected' : '' }}>Paling Dikomentari</option>
                            </select>
                        </div>
                    </div>

                    {{-- Pinned only toggle --}}
                    <div class="mt-3 flex items-center gap-2.5">
                        <label class="flex items-center gap-2.5 cursor-pointer select-none group">
                            <div class="relative">
                                <input type="checkbox" name="pinned_only" value="1" {{ request('pinned_only') ? 'checked' : '' }}
                                       class="sr-only peer">
                                <div class="w-9 h-5 bg-slate-200 dark:bg-white/10 rounded-full peer-checked:bg-blue-500 transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                            </div>
                            <span class="text-sm font-semibold text-slate-600 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">
                                Hanya tampilkan yang Pinned
                            </span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        {{-- Announcement Cards Grid with Scrollable Content --}}
        <div class="max-h-[640px] overflow-y-auto px-1 -mx-1 custom-scrollbar scroll-smooth">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6">
                @forelse($pengumuman as $item)
                    <div class="card !p-0 overflow-hidden h-full flex flex-col hover-lift cursor-pointer group !bg-slate-500 dark:!bg-[#171e30]/40 !border-0 !border-t-4 !border-t-blue-500 !rounded-2xl !shadow-lg transition-all duration-300 relative" 
                         onclick="if(!event.target.closest('button') && !event.target.closest('a')) window.location='{{ route('pengumuman.show', $item->id) }}'">
                        
                        <div class="p-6 flex flex-col flex-1 relative z-10">
                            {{-- Meta & Actions --}}
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center gap-2">
                                        @if($item->is_pinned)
                                            <div class="flex items-center gap-1.5 px-2 py-1 rounded-full bg-white/10 border border-white/10">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-2.5 h-2.5 text-white/80 fill-current" viewBox="0 0 16 16">
                                                    <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                                                </svg>
                                                <span class="text-[9px] font-black text-white/80 uppercase tracking-widest">Pinned</span>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Pin Action (Left Top) --}}
                                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                                        <form action="{{ route('pengumuman.pin', $item->id) }}" method="POST" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            @csrf
                                            <button type="submit" class="w-7 h-7 rounded-lg flex items-center justify-center {{ $item->is_pinned ? 'bg-slate-700 text-white' : 'bg-white/10 text-white/70 hover:bg-white/20 hover:text-white' }} ring-1 ring-white/10 backdrop-blur-md" title="{{ $item->is_pinned ? 'Lepas Pin' : 'Pin Pengumuman' }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 fill-current" viewBox="0 0 16 16">
                                                  <path d="M4.146.146A.5.5 0 0 1 4.5 0h7a.5.5 0 0 1 .5.5c0 .68-.342 1.174-.646 1.479-.126.125-.25.224-.354.298v4.431l.078.048c.203.127.476.314.751.555C12.36 7.775 13 8.527 13 9.5a.5.5 0 0 1-.5.5h-4v4.5c0 .276-.224 1.5-.5 1.5s-.5-1.224-.5-1.5V10h-4a.5.5 0 0 1-.5-.5c0-.973.64-1.725 1.17-2.189A6 6 0 0 1 5 6.708V2.277a3 3 0 0 1-.354-.298C4.342 1.674 4 1.179 4 .5a.5.5 0 0 1 .146-.354m1.58 1.408-.002-.001zm-.002-.001.002.001A.5.5 0 0 1 6 2v5a.5.5 0 0 1-.276.447h-.002l-.012.007-.054.03a5 5 0 0 0-.827.58c-.318.278-.585.596-.725.936h7.792c-.14-.34-.407-.658-.725-.936a5 5 0 0 0-.881-.61l-.012-.006h-.002A.5.5 0 0 1 10 7V2a.5.5 0 0 1 .295-.458 1.8 1.8 0 0 0 .351-.271c.08-.08.155-.17.214-.271H5.14q.091.15.214.271a1.8 1.8 0 0 0 .37.282"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="flex flex-col items-end gap-2">
                                    <span class="text-[11px] font-bold text-slate-200 dark:text-slate-400 uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                    </span>

                                    {{-- Edit/Delete Actions (Right Top) --}}
                                    <div class="flex items-center gap-1.5 z-20 opacity-0 group-hover:opacity-100">
                                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                                            {{-- Edit --}}
                                            <a href="{{ route('pengumuman.edit', $item->id) }}" data-turbo-frame="modal" class="w-7 h-7 rounded-lg bg-blue-500 text-white flex items-center justify-center hover:bg-blue-600 ring-1 ring-white/10 backdrop-blur-md" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/></svg>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('pengumuman.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Pengumuman', 'Apakah Anda yakin ingin menghapus pengumuman ini? Tindakan ini tidak dapat dibatalkan.')" class="w-7 h-7 rounded-lg bg-rose-500 text-white flex items-center justify-center hover:bg-rose-600 ring-1 ring-white/10 backdrop-blur-md" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Title --}}
                            <h3 class="text-xl font-black text-white leading-tight mb-1 group-hover:text-blue-200 transition-colors line-clamp-2">
                                {{ $item->judul }}
                            </h3>

                            {{-- Excerpt --}}
                            <p class="text-[13px] font-medium text-slate-200 dark:text-slate-400 leading-relaxed mb-6 line-clamp-1">
                                {{ Str::limit(strip_tags($item->isi), 120) }}
                            </p>

                            {{-- Footer --}}
                            <div class="mt-auto pt-5 border-t border-slate-400 dark:border-white/5 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    @if($item->user && $item->user->foto_profil)
                                        <img src="{{ asset('storage/'.$item->user->foto_profil) }}" class="w-7 h-7 object-cover rounded-full border border-slate-300 dark:border-white/10 ring-2 ring-transparent group-hover:ring-blue-300 dark:group-hover:ring-blue-500/30 transition-all">
                                    @else
                                        <div class="w-7 h-7 rounded-full bg-slate-400 dark:bg-slate-700/50 flex items-center justify-center text-white dark:text-slate-300 font-extrabold text-[10px] border border-slate-300 dark:border-white/10 ring-2 ring-transparent group-hover:ring-blue-300 dark:group-hover:ring-blue-500/30 transition-all">
                                            {{ strtoupper(substr($item->user->name ?? 'A', 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-[13px] font-bold text-white dark:text-slate-300">{{ $item->user->name ?? 'Admin' }}</span>
                                </div>
                                
                                <div class="flex items-center gap-4 text-slate-200 dark:text-slate-400 text-[13px] font-bold">
                                    <span class="flex items-center gap-1.5 hover:text-rose-200 dark:hover:text-rose-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                                        {{ $item->likes_count }}
                                    </span>
                                    <span class="flex items-center gap-1.5 hover:text-blue-200 dark:hover:text-blue-400 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                        {{ $item->comments_count }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center animate-fade-in">
                        <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l4 4v10a2 2 0 01-2 2zM14 2v6h6"/></svg>
                        </div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Belum Ada Pengumuman</h3>
                        <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Nantikan informasi selanjutnya dari pengurus.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-admin-layout>
