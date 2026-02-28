<x-admin-layout title="Edit Pengumuman">
    <turbo-frame id="modal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 dark:bg-slate-950/40 backdrop-blur-md animate-fade-in"
             x-data="{}" @keydown.escape.window="document.getElementById('modal-close-btn').click()">
            
            <div @click.outside="document.getElementById('modal-close-btn').click()"
                 class="w-full max-w-xl bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
                 
                 {{-- Modal Header --}}
                 <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent">
                     <div>
                         <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Edit Pengumuman</h2>
                         <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Perbarui konten postingan komunitas</p>
                     </div>
                     <a href="{{ route('pengumuman.index') }}" data-turbo-frame="_top" id="modal-close-btn"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                     </a>
                 </div>

                 <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                     <form action="{{ route('pengumuman.update', $pengumuman->id) }}" method="POST" data-turbo-frame="_top" class="space-y-6">
                         @csrf
                         @method('PUT')

                         <div>
                             <label for="judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul Pengumuman</label>
                             <input type="text" id="judul" name="judul" value="{{ old('judul', $pengumuman->judul) }}"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70"
                                    placeholder="Apa judul pengumuman ini?" required>
                             @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div>
                             <label for="isi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Isi Detail</label>
                             <textarea id="isi" name="isi" rows="5"
                                       class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl p-4 text-[14px] font-medium text-slate-900 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 resize-none"
                                       placeholder="Tuliskan informasi lengkap di sini..." required>{{ old('isi', $pengumuman->isi) }}</textarea>
                             @error('isi')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div>
                             <label for="tanggal" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Tanggal Publikasi</label>
                             <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $pengumuman->tanggal) }}"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all color-scheme-dark dark:color-scheme-dark" required>
                             @error('tanggal')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <label class="flex items-center gap-3 cursor-pointer group w-fit">
                             <input type="checkbox" id="highlight" name="highlight" value="1" {{ old('highlight', $pengumuman->highlight ?? false) ? 'checked' : '' }}
                                    class="w-4 h-4 rounded border-slate-300 dark:border-white/20 dark:bg-white/5 text-blue-600 focus:ring-blue-500 dark:focus:ring-offset-0 transition-all cursor-pointer">
                             <span class="text-[13px] font-bold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Sematkan sebagai Highlight di Dashboard</span>
                         </label>

                         <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                             <a href="{{ route('pengumuman.index') }}" data-turbo-frame="_top"
                                class="flex items-center justify-center px-6 h-11 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 dark:hover:text-white transition-all">Batal</a>
                             <button type="submit" 
                                     class="px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                         </div>
                     </form>
                 </div>
                 
            </div>
        </div>
    </turbo-frame>
</x-admin-layout>
