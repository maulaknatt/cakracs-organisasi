@php
    $isiFormatted = preg_replace('/@([a-zA-Z0-9_\-\.]+)/', '<span class="text-blue-500 font-semibold cursor-pointer hover:underline">@$1</span>', e($reply->isi));
    $isAdminComment = $reply->user && ($reply->user->isSuperAdmin() || $reply->user->isAdmin() || $reply->user->isPengurus());
    $isOwn = auth()->check() && $reply->user_id === auth()->id();
    $canDelete = auth()->check() && ($isOwn || auth()->user()->isAdmin() || auth()->user()->isSuperAdmin());
    $likeCount = \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')->where('pengumuman_comment_id', $reply->id)->count();
    $isLiked = auth()->check() ? \Illuminate\Support\Facades\DB::table('pengumuman_comment_likes')->where('pengumuman_comment_id', $reply->id)->where('user_id', auth()->id())->exists() : false;
@endphp

<div id="comment-{{ $reply->id }}" class="flex gap-2.5 sm:gap-3 relative transition-all duration-300 group pt-4">
    @if($reply->user->foto_profil)
        <img src="{{ asset('storage/'.$reply->user->foto_profil) }}" 
             class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex-shrink-0 object-cover z-10 mt-1 {{ $isAdminComment ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-[#151c2c]' : '' }}" alt="Avatar">
    @else
        <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'A') }}&background=0284c7&color=fff&bold=true"  
             class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex-shrink-0 object-cover z-10 mt-1 {{ $isAdminComment ? 'ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-[#151c2c]' : '' }}" alt="Avatar">
    @endif
         
    <div class="flex-1 min-w-0 relative" x-data="{ editing: false, menuOpen: false, isiEdit: '{{ addslashes($reply->isi) }}' }">
        <div class="flex items-start justify-between gap-2">
            
            <div class="flex-1 min-w-0 pt-0.5">
                {{-- Comment Content: Inline Name & Text --}}
                <div x-show="!editing" class="text-[13px] leading-snug break-words text-slate-800 dark:text-slate-200">
                    <span class="font-semibold text-slate-900 dark:text-white mr-1">{{ $reply->user->name ?? 'Anonim' }}</span>
                    @if($reply->user?->jabatan)
                        <span class="px-1 py-0.5 rounded text-[9px] font-bold bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 mr-1 relative -top-[1px]">{{ $reply->user->jabatan }}</span>
                    @endif
                    <span id="komentar-text-{{ $reply->id }}" class="whitespace-pre-line">{!! $isiFormatted !!}</span>
                </div>

                {{-- Editing Field --}}
                <div x-show="editing" class="mb-2 mt-2" style="display: none;">
                    <form @submit.prevent="updateComment({{ $reply->id }}, isiEdit, editing)">
                        <input type="text" x-model="isiEdit" x-ref="editInput" required
                               @keydown.escape="editing = false; isiEdit = '{{ addslashes($reply->isi) }}'"
                               class="w-full h-9 bg-transparent border-b-2 border-slate-200 dark:border-white/10 focus:border-blue-500 dark:focus:border-blue-500 px-2 text-[13px] text-slate-900 dark:text-white outline-none transition-all placeholder:text-slate-400">
                        <div class="flex justify-start gap-2 mt-2">
                            <button type="button" @click="editing = false; isiEdit = '{{ addslashes($reply->isi) }}'" class="text-[11px] font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 px-2">Batal</button>
                            <button type="submit" class="text-[11px] font-bold px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                        </div>
                    </form>
                </div>

                {{-- Footer / Actions (Under text) --}}
                <div class="flex items-center gap-3 sm:gap-4 mt-2 text-[12px] font-semibold text-slate-500 dark:text-slate-400">
                    {{-- Timestamp --}}
                    <span title="{{ $reply->created_at }}" class="font-normal">{{ str_replace([' seconds', ' minutes', ' hours', ' days', ' weeks', ' months', ' years', ' second', ' minute', ' hour', ' day', ' week', ' month', ' year'], ['s', 'm', 'h', 'd', 'w', 'mo', 'y', 's', 'm', 'h', 'd', 'w', 'mo', 'y'], $reply->created_at->diffForHumans(null, true, true)) }}</span>
                    
                    @if($reply->is_edited)
                        <span class="font-normal text-slate-400 -ml-1 sm:-ml-2">(diedit)</span>
                    @endif
                    
                    {{-- Likes Count --}}
                    <span x-show="(likes[{{ $reply->id }}] ? likes[{{ $reply->id }}].count : {{ $likeCount }}) > 0">
                        <span x-text="likes[{{ $reply->id }}] ? likes[{{ $reply->id }}].count : {{ $likeCount }}"></span> suka
                    </span>
                    
                    <button @click="initReply({{ $parent_id ?? $reply->id }}, '{{ Str::slug($reply->user->name ?? 'anonim') }}')" class="hover:text-slate-700 dark:hover:text-slate-300 transition-colors">
                        Balas
                    </button>
                    
                    {{-- 3 Dots Menu --}}
                    @if($canDelete || $isOwn)
                    <div class="relative flex items-center" @click.away="menuOpen = false">
                        <button @click="menuOpen = !menuOpen" class="w-5 h-5 flex items-center justify-center rounded-full hover:text-slate-700 dark:hover:text-slate-200 opacity-0 group-hover:opacity-100 transition-opacity focus:opacity-100">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM8.5 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM14 10a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z"/></svg>
                        </button>
                        <div x-show="menuOpen" x-transition class="absolute left-0 top-6 w-28 bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-white/10 rounded-xl shadow-lg z-20 py-1" style="display: none;">
                            @if($isOwn)
                                <button @click="editing = true; menuOpen = false; $nextTick(() => $refs.editInput.focus())" class="w-full text-left px-4 py-2 text-[13px] font-medium text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Edit</button>
                            @endif
                            @if($canDelete)
                                <button @click="deleteComment({{ $reply->id }}); menuOpen = false" class="w-full text-left px-4 py-2 text-[13px] font-medium text-red-600 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">Hapus</button>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Right: Heart Like Icon --}}
            <div class="flex-shrink-0 pt-2 pr-1">
                <button @click="toggleLike({{ $reply->id }})" class="flex items-center transition-colors group/like text-slate-400" :class="(likes[{{ $reply->id }}] ? likes[{{ $reply->id }}].liked : {{ $isLiked ? 'true' : 'false' }}) ? '!text-rose-500' : 'hover:text-slate-600 dark:hover:text-slate-300'">
                    <svg class="w-3.5 h-3.5 transition-transform group-active:scale-95" :class="(likes[{{ $reply->id }}] ? likes[{{ $reply->id }}].liked : {{ $isLiked ? 'true' : 'false' }}) ? 'fill-current scale-110' : 'group-hover/like:scale-110'" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Editing Field --}}
        <div x-show="editing" class="mb-2" style="display: none;">
            <form @submit.prevent="updateComment({{ $reply->id }}, isiEdit, editing)">
                <input type="text" x-model="isiEdit" x-ref="editInput" required
                       @keydown.escape="editing = false; isiEdit = '{{ addslashes($reply->isi) }}'"
                       class="w-full h-9 bg-transparent border-b-2 border-slate-200 dark:border-white/10 focus:border-blue-500 dark:focus:border-blue-500 px-2 text-[13px] text-slate-900 dark:text-white outline-none transition-all placeholder:text-slate-400">
                <div class="flex justify-end gap-2 mt-2">
                    <button type="button" @click="editing = false; isiEdit = '{{ addslashes($reply->isi) }}'" class="text-[12px] font-bold text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 px-2">Batal</button>
                    <button type="submit" class="text-[12px] font-bold px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>

    </div>
</div>
