<x-admin-layout title="Buat Kegiatan">
    <turbo-frame id="modal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 dark:bg-slate-950/40 backdrop-blur-md animate-fade-in"
             x-data="{}" @keydown.escape.window="document.getElementById('modal-close-btn').click()">
            
            <div @click.outside="document.getElementById('modal-close-btn').click()"
                 class="w-full max-w-xl bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
                 
                 {{-- Modal Header --}}
                 <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent">
                     <div>
                         <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Buat Kegiatan Baru</h2>
                         <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Tambahkan kegiatan baru untuk organisasi</p>
                     </div>
                     <a href="{{ route('kegiatan.index') }}" data-turbo-frame="_top" id="modal-close-btn"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                     </a>
                 </div>

                 <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                     <form action="{{ route('kegiatan.store') }}" method="POST" data-turbo-frame="_top" class="space-y-6">
                         @csrf

                         <div>
                             <label for="judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul Kegiatan <span class="text-rose-500">*</span></label>
                             <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('judul') border-rose-500 @enderror"
                                    placeholder="Contoh: Ramadhan 2026" required autofocus>
                             @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div>
                             <label for="deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi</label>
                             <textarea id="deskripsi" name="deskripsi" rows="3"
                                       class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl p-4 text-[14px] font-medium text-slate-900 dark:text-slate-200 transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 resize-none @error('deskripsi') border-rose-500 @enderror"
                                       placeholder="Jelaskan tujuan dan detail kegiatan ini...">{{ old('deskripsi') }}</textarea>
                             @error('deskripsi')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                             <div>
                                 <label for="tanggal_mulai" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Tanggal Mulai <span class="text-rose-500">*</span></label>
                                 <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all color-scheme-dark dark:color-scheme-dark @error('tanggal_mulai') border-rose-500 @enderror" required>
                                 @error('tanggal_mulai')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                             <div>
                                 <label for="tanggal_selesai" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Tanggal Selesai <span class="text-rose-500">*</span></label>
                                 <input type="date" id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all color-scheme-dark dark:color-scheme-dark @error('tanggal_selesai') border-rose-500 @enderror" required>
                                 @error('tanggal_selesai')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                         </div>

                         <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                             <button type="button" onclick="document.getElementById('modal-close-btn').click()" class="flex items-center justify-center px-6 h-11 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 dark:hover:text-white transition-all">Batal</button>
                             <button type="submit" class="px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-95 transition-all">Simpan Kegiatan</button>
                         </div>
                     </form>
                 </div>
                 
            </div>
        </div>
    </turbo-frame>
</x-admin-layout>
