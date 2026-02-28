<x-admin-layout title="Upload Dokumentasi">
    <turbo-frame id="modal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 dark:bg-slate-950/40 backdrop-blur-md animate-fade-in"
             x-data="{}" @keydown.escape.window="document.getElementById('modal-close-btn').click()">
            
            <div @click.outside="document.getElementById('modal-close-btn').click()"
                 class="w-full max-w-xl bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
                 
                 {{-- Modal Header --}}
                 <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent">
                     <div>
                         <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Upload Dokumentasi</h2>
                         <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Simpan momen foto kegiatan organisasi</p>
                     </div>
                     <a href="{{ route('dokumentasi.index') }}" data-turbo-frame="_top" id="modal-close-btn"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                     </a>
                 </div>

                 <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                     <form action="{{ route('dokumentasi.store') }}" method="POST" enctype="multipart/form-data" data-turbo-frame="_top" class="space-y-6">
                         @csrf
                         @if(request('redirect') === 'workspace')
                             <input type="hidden" name="redirect" value="workspace">
                         @endif

                         {{-- Field: Judul --}}
                         <div>
                             <label for="judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul Dokumentasi <span class="text-rose-500">*</span></label>
                             <input type="text" id="judul" name="judul" value="{{ old('judul') }}"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('judul') border-rose-500 @enderror"
                                    placeholder="Contoh: Foto Bersama Pemateri" required autofocus>
                             @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         {{-- Field: Kegiatan --}}
                         <div>
                             <label for="kegiatan_id" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Terkait Kegiatan</label>
                             <div class="relative">
                                 <select id="kegiatan_id" name="kegiatan_id" class="form-select w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none cursor-pointer">
                                     <option value="">Tidak Terkait Kegiatan Apapun</option>
                                     @foreach(\App\Models\Kegiatan::orderBy('judul')->get() as $k)
                                         <option value="{{ $k->id }}" {{ old('kegiatan_id', request('kegiatan_id')) == $k->id ? 'selected' : '' }}>{{ $k->judul }}</option>
                                     @endforeach
                                 </select>
                                 <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                     <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path></svg>
                                 </div>
                             </div>
                             @error('kegiatan_id')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         {{-- Field: File/Foto --}}
                         <div>
                             <label for="file" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Upload Foto <span class="text-rose-500">*</span></label>
                             
                             <div class="relative" x-data="{ fileName: '' }">
                                 <label for="file" 
                                        class="flex items-center justify-center w-full h-32 px-4 transition border-2 border-slate-300 dark:border-slate-600 border-dashed rounded-xl appearance-none cursor-pointer hover:border-blue-500 focus:outline-none bg-slate-50 dark:bg-white/5">
                                     <span class="flex items-center space-x-2">
                                         <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                         <span class="font-medium text-slate-600 dark:text-slate-400" x-text="fileName || 'Pilih foto (JPG, PNG, maksimal 10MB)'"></span>
                                     </span>
                                     <input type="file" id="file" name="file" accept="image/*" class="hidden" required
                                            @change="fileName = $event.target.files[0].name">
                                 </label>
                             </div>
                             @error('file')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         {{-- Field: Highlight --}}
                         <div class="flex items-center mt-2">
                             <input type="checkbox" id="highlight" name="highlight" value="1" {{ old('highlight') ? 'checked' : '' }}
                                    class="w-4 h-4 text-blue-600 bg-slate-100 border-slate-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-slate-700 dark:border-slate-600">
                             <label for="highlight" class="ml-2 text-[13px] font-medium text-slate-700 dark:text-slate-300">
                                 Tandai sebagai Highlight (Penting)
                             </label>
                         </div>

                         <div class="pt-6 flex justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                             <a href="{{ route('dokumentasi.index') }}" data-turbo-frame="_top"
                                class="px-5 py-2.5 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-colors">
                                 Batal
                             </a>
                             <button type="submit" 
                                     class="px-5 py-2.5 rounded-xl text-[13px] font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all active:scale-95">
                                 Upload Dokumentasi
                             </button>
                         </div>
                     </form>
                 </div>
             </div>
        </div>
    </turbo-frame>
</x-admin-layout>
