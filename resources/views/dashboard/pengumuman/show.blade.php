<x-admin-layout :title="$pengumuman->judul" :noPadding="true">
<div class="absolute inset-x-0 bottom-0 top-0 flex flex-col items-center pt-3 pb-0 sm:py-6 px-0 sm:px-4 overflow-hidden z-10 w-full">
    <div class="w-full max-w-2xl sm:max-w-3xl animate-fade-in-up flex flex-col h-full min-h-0">
        
        {{-- Back Button --}}
        <div class="mb-3 px-4 sm:px-0 shrink-0">
            <a href="{{ route('pengumuman.index') }}" class="group inline-flex items-center gap-2 text-[13px] font-bold text-slate-500 hover:text-blue-500 dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
        </div>

        {{-- Single Social-media style Card --}}
        <div class="bg-white dark:bg-[#151c2c] sm:rounded-3xl border-y sm:border border-slate-200 dark:border-white/10 shadow-sm sm:shadow-xl flex flex-col flex-1 min-h-0 overflow-hidden relative" x-data="commentsApp({{ $pengumuman->id }})">
            
            {{-- Scrollable Content Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                
                {{-- Post Header --}}
                <div class="p-4 sm:p-6 flex gap-3 sm:gap-4 items-center">
                    @if($pengumuman->user && $pengumuman->user->foto_profil)
                        <img src="{{ asset('storage/'.$pengumuman->user->foto_profil) }}" 
                             class="w-12 h-12 rounded-full shadow-sm object-cover" alt="Avatar">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pengumuman->user->name ?? 'A') }}&background=0284c7&color=fff&bold=true" 
                             class="w-12 h-12 rounded-full shadow-sm object-cover" alt="Avatar">
                    @endif
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-1.5">
                            <span class="text-[15px] font-bold text-slate-900 dark:text-white truncate">{{ $pengumuman->user->name ?? 'Admin' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-[13px] text-slate-500 dark:text-slate-400">
                            <span>@<span>{{ Str::slug($pengumuman->user->name ?? 'admin') }}</span></span>
                            <span>•</span>
                            <span class="hover:underline cursor-pointer">{{ $pengumuman->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                        <div class="flex items-center gap-1">
                            <a href="{{ route('pengumuman.edit', $pengumuman->id) }}" data-turbo-frame="modal" 
                            class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-colors" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </a>
                            <form action="{{ route('pengumuman.destroy', $pengumuman->id) }}" method="POST" class="inline">
                                @csrf @method('DELETE')
                                <button type="button" @click="window.showModalConfirm($el.closest('form'), 'Hapus Pengumuman', 'Apakah Anda yakin ingin menghapus ini?', 'Hapus', 'Batal')"
                                        class="w-8 h-8 rounded-full flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

                {{-- Post Content --}}
                <div class="px-4 sm:px-6 pb-2">
                    <h1 class="text-xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight mb-4">
                        {{ $pengumuman->judul }}
                    </h1>
                    <div class="text-[15px] sm:text-[16px] text-slate-700 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                        {{ $pengumuman->isi }}
                    </div>
                </div>

                {{-- Stats Summary --}}
                <div class="px-4 sm:px-6 py-4 flex items-center gap-4 text-[13px] sm:text-[14px] text-slate-500 dark:text-slate-400 font-medium">
                    <span class="hover:underline cursor-pointer"><b class="text-slate-900 dark:text-white">{{ $pengumuman->likes_count }}</b> Suka</span>
                    <span class="hover:underline cursor-pointer"><b class="text-slate-900 dark:text-white">{{ $pengumuman->comments_count }}</b> Komentar</span>
                </div>

                {{-- Action Bar --}}
                <div class="px-2 sm:px-4 py-1 mx-4 sm:mx-6 border-y border-slate-200 dark:border-white/10 flex items-center justify-between">
                    <form action="{{ route('pengumuman.like', $pengumuman->id) }}" method="POST" class="flex-1">
                        @csrf
                        @php $isLiked = $pengumuman->isLikedBy(auth()->user()); @endphp
                        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-colors font-bold text-[13px] {{ $isLiked ? 'text-rose-500' : 'text-slate-500 dark:text-slate-400' }}">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $isLiked ? 'fill-current' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                            <span>{{ $isLiked ? 'Disukai' : 'Suka' }}</span>
                        </button>
                    </form>
                    <button onclick="document.getElementById('comment-input').focus()" class="flex-1 flex items-center justify-center gap-2 py-2.5 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-500 dark:text-slate-400 transition-colors font-bold text-[13px]">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        <span>Komentar</span>
                    </button>
                </div>

                {{-- Comments List Section --}}
                <div class="px-4 sm:px-6 py-2 pb-6 space-y-0" id="comments-container">
                    
                    {{-- Toolbar/Sorting --}}
                    @if($pengumuman->comments_count > 0)
                    <div class="flex justify-between items-center mb-4 py-2 border-b border-slate-100 dark:border-white/5">
                        <span class="text-[14px] font-bold text-slate-700 dark:text-slate-300">
                            {{ $pengumuman->comments_count }} Komentar
                        </span>
                        <select onchange="window.location.href='?sort='+this.value" class="text-[13px] font-medium bg-slate-50 border-0 dark:bg-[#111724] text-slate-600 dark:text-slate-300 rounded cursor-pointer outline-none focus:ring-0">
                            <option value="latest" {{ $sortBy == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="popular" {{ $sortBy == 'popular' ? 'selected' : '' }}>Terpopuler</option>
                        </select>
                    </div>
                    @endif

                    <div id="comments-list">
                        @forelse($pengumuman->comments->whereNull('parent_id') as $komentar)
                            @include('dashboard.pengumuman.partials.comment_item', ['komentar' => $komentar])
                        @empty
                            <div class="py-10 text-center flex flex-col items-center empty-state" id="empty-state">
                                 <div class="border-2 border-dashed border-slate-200 dark:border-white/10 rounded-full p-4 mb-3 text-slate-300 dark:text-white/20">
                                     <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                 </div>
                                 <span class="text-[14px] font-bold text-slate-500 dark:text-slate-400">Jadilah yang pertama berkomentar</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Sticky Comment Input (Always visible at bottom of the flex card) --}}
            <div class="border-t border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#111724] z-10 shrink-0 w-full relative" style="padding-bottom: env(safe-area-inset-bottom);">
                
                {{-- Emoji Picker Panel --}}
                <div x-show="emojiPickerOpen" @click.away="emojiPickerOpen = false" x-cloak
                    x-transition:enter="transition ease-out duration-300 transform"
                    x-transition:enter-start="opacity-0 translate-y-12 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    class="absolute bottom-[calc(100%+10px)] left-4 right-4 max-w-sm glass-surface rounded-[32px] shadow-2xl z-[60] overflow-hidden border-white/20 flex flex-col h-64 bg-white/90 dark:bg-[#1a2333]/90 backdrop-blur-xl">
                    <div class="p-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                        <span class="text-[11px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Pilih Ekspresi</span>
                        <button @click="emojiPickerOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                    <div class="flex-1 overflow-y-auto p-4 custom-scrollbar">
                        <div class="grid grid-cols-6 gap-2">
                            <template x-for="emoji in emojis" :key="emoji">
                                <button @click="addEmoji(emoji)" class="text-2xl hover:scale-125 transition-all p-1.5 rounded-xl hover:bg-blue-500/10 active:scale-95">
                                    <span x-text="emoji"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <div class="p-3 sm:p-4">
                    <form @submit.prevent="submitMergedForm($event.target.isi.value); $event.target.reset();" class="mb-0">
                        @csrf
                        <div class="flex gap-3 items-center">
                            @if(auth()->user()->foto_profil)
                                <img src="{{ asset('storage/'.auth()->user()->foto_profil) }}" 
                                     class="hidden sm:block w-9 h-9 flex-shrink-0 rounded-full object-cover" alt="Avatar">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=0284c7&color=fff&bold=true" 
                                     class="hidden sm:block w-9 h-9 flex-shrink-0 rounded-full object-cover" alt="Avatar">
                            @endif
                            <div class="flex-1 relative group/input">
                                <button type="button" @click="emojiPickerOpen = !emojiPickerOpen" 
                                    class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-blue-500 transition-colors p-1 rounded-lg hover:bg-blue-500/5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                </button>
                                <input type="text" name="isi" id="comment-input" required placeholder="Tulis komentar..." x-ref="commentInput"
                                       class="w-full h-11 bg-white dark:bg-[#1a2333] border border-slate-200 dark:border-white/5 focus:border-blue-500 dark:focus:border-blue-500 rounded-full pl-11 pr-12 text-[14px] text-slate-700 dark:text-white outline-none transition-all shadow-sm placeholder:text-slate-400 dark:placeholder:text-slate-500">
                                <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-blue-500 hover:bg-blue-600 flex items-center justify-center text-white transition-all active:scale-95 shadow-sm" :disabled="submitting" :class="submitting ? 'opacity-50' : ''">
                                    <svg class="w-4 h-4 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/></svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function commentsApp(pengumumanId) {
        return {
            submitting: false,
            likes: {},
            activeReplyId: null,
            emojiPickerOpen: false,
            emojis: ['😊', '😂', '🤣', '❤️', '👍', '🙏', '🔥', '🚀', '✨', '🙌', '🎉', '💡', '✅', '❌', '👀', '😎', '😜', '💪', '🤝', '🎈', '🎁', '🍕', '🍔', '🍟', '🍦', '🍰', '☀️', '🌈', '⭐', '🍀', '💎'],

            initReply(commentId, username) {
                this.activeReplyId = commentId;
                const input = this.$refs.commentInput;
                input.value = '@' + username + ' ';
                input.focus();
            },

            addEmoji(emoji) {
                const input = this.$refs.commentInput;
                input.value += emoji;
                this.emojiPickerOpen = false;
                input.focus();
            },

            async submitMergedForm(isi) {
                if (!isi.trim()) return;
                if (this.activeReplyId) {
                    await this.submitReply(this.activeReplyId, isi);
                    this.activeReplyId = null;
                } else {
                    await this.submitComment(isi);
                }
            },
            
            async submitComment(isi) {
                if (!isi.trim()) return;
                this.submitting = true;
                
                try {
                    const res = await fetch(`{{ url('dashboard/pengumuman') }}/${pengumumanId}/comment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ isi })
                    });
                    
                    const data = await res.json();
                    if(data.success) {
                        const emptyState = document.getElementById('empty-state');
                        if(emptyState) emptyState.remove();
                        
                        document.getElementById('comments-list').insertAdjacentHTML('afterbegin', data.html);
                        
                        // Scroll to top of comment list slowly
                        document.querySelector('.custom-scrollbar').scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    }
                } catch (e) {
                    console.error(e);
                } finally {
                    this.submitting = false;
                }
            },

            async submitReply(parentId, isi) {
                if (!isi.trim()) return;
                try {
                    const res = await fetch(`{{ url('dashboard/pengumuman') }}/${pengumumanId}/comment`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ isi, parent_id: parentId })
                    });
                    
                    const data = await res.json();
                    if(data.success) {
                        document.getElementById('new-replies-' + parentId).insertAdjacentHTML('beforeend', data.html);
                    }
                } catch (e) {}
            },

            async updateComment(commentId, isi, editingVar) {
                if (!isi.trim()) return;
                try {
                    const res = await fetch(`{{ url('dashboard/pengumuman/comments') }}/${commentId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ isi, _method: 'PUT' })
                    });
                    const data = await res.json();
                    if(data.success) {
                        // We can just update the frontend text
                        const parser = new DOMParser();
                        let formatted = data.comment.isi.replace(/@([a-zA-Z0-9_\-\.]+)/g, '<span class="text-blue-500 font-semibold cursor-pointer hover:underline">@$1</span>');
                        document.getElementById('komentar-text-' + commentId).innerHTML = formatted;
                    }
                } catch(e) {}
                editingVar = false; 
            },

            async deleteComment(commentId) {
                if(!confirm('Yakin ingin menghapus komentar ini?')) return;
                try {
                    const res = await fetch(`{{ url('dashboard/pengumuman/comments') }}/${commentId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    });
                    const data = await res.json();
                    if(data.success) {
                        const el = document.getElementById('comment-' + commentId);
                        if(el) {
                            el.style.opacity = '0';
                            setTimeout(() => el.remove(), 300);
                        }
                    }
                } catch(e) {}
            },

            async toggleLike(commentId) {
                try {
                    const res = await fetch(`{{ url('dashboard/pengumuman/comments') }}/${commentId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    });
                    const data = await res.json();
                    if(data.success) {
                        this.likes[commentId] = {
                            liked: data.status === 'liked',
                            count: data.likes_count
                        };
                    }
                } catch(e) {}
            }
        }
    }
</script>
@endpush
</x-admin-layout>
