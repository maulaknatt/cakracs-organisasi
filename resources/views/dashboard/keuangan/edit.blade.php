<x-admin-layout title="Edit Transaksi">
    <turbo-frame id="modal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 dark:bg-slate-950/40 backdrop-blur-md animate-fade-in"
             x-data="{}" @keydown.escape.window="document.getElementById('modal-close-btn').click()">
            
            <div @click.outside="document.getElementById('modal-close-btn').click()"
                 class="w-full max-w-xl bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
                 
                 {{-- Modal Header --}}
                 <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent">
                     <div>
                         <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Edit Transaksi</h2>
                         <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Perbarui data transaksi keuangan</p>
                     </div>
                     <a href="{{ route('keuangan.index') }}" data-turbo-frame="_top" id="modal-close-btn"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                     </a>
                 </div>

                 <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                     <form action="{{ route('keuangan.update', $keuangan->id) }}" method="POST" data-turbo-frame="_top" class="space-y-6">
                         @csrf
                         @method('PUT')

                         <div>
                             <label for="judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul Transaksi <span class="text-rose-500">*</span></label>
                             <input type="text" id="judul" name="judul" value="{{ old('judul', $keuangan->judul) }}"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('judul') border-rose-500 @enderror" required autofocus>
                             @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                             <div>
                                 <label for="tanggal" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Tanggal <span class="text-rose-500">*</span></label>
                                 <input type="date" id="tanggal" name="tanggal" value="{{ old('tanggal', $keuangan->tanggal) }}"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all color-scheme-dark dark:color-scheme-dark @error('tanggal') border-rose-500 @enderror" required>
                                 @error('tanggal')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                             <div>
                                 <label for="jenis" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Jenis <span class="text-rose-500">*</span></label>
                                 <select id="jenis" name="jenis" class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none @error('jenis') border-rose-500 @enderror" required>
                                     <option value="masuk" {{ old('jenis', $keuangan->jenis) == 'masuk' ? 'selected' : '' }}>💚 Pemasukan</option>
                                     <option value="keluar" {{ old('jenis', $keuangan->jenis) == 'keluar' ? 'selected' : '' }}>🔴 Pengeluaran</option>
                                 </select>
                             </div>
                         </div>

                         <div>
                             <label for="jumlah" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Jumlah (Rp) <span class="text-rose-500">*</span></label>
                             <div class="relative">
                                 <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[14px] font-bold text-slate-500">Rp</span>
                                 <input type="number" id="jumlah" name="jumlah" value="{{ old('jumlah', $keuangan->jumlah) }}"
                                        class="w-full h-12 pl-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('jumlah') border-rose-500 @enderror" min="0" required>
                             </div>
                             @error('jumlah')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div>
                             <label for="deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi</label>
                             <textarea id="deskripsi" name="deskripsi" rows="3" class="w-full bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl p-4 text-[14px] font-medium text-slate-900 dark:text-slate-200 transition-all resize-none">{{ old('deskripsi', $keuangan->deskripsi) }}</textarea>
                         </div>

                         <div>
                             <label for="kegiatan_id" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Kegiatan <span class="text-slate-400 dark:text-slate-500 text-[11px] font-normal ml-1">(opsional)</span></label>
                             <div class="relative">
                                 <select id="kegiatan_id" name="kegiatan_id" class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none">
                                     <option value="">Tidak terkait kegiatan</option>
                                     @foreach($kegiatan as $k)
                                         <option value="{{ $k->id }}" {{ old('kegiatan_id', $keuangan->kegiatan_id) == $k->id ? 'selected' : '' }}>{{ $k->judul }}</option>
                                     @endforeach
                                 </select>
                                 <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                     <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                 </div>
                             </div>
                         </div>

                         <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                             <a href="{{ route('keuangan.index') }}" data-turbo-frame="_top" class="flex items-center justify-center px-6 h-11 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 dark:hover:text-white transition-all">Batal</a>
                             <button type="submit" class="px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-95 transition-all">Simpan Perubahan</button>
                         </div>
                     </form>
                 </div>
                 
            </div>
        </div>
    </turbo-frame>
</x-admin-layout>
