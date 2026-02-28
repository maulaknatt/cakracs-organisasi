<x-admin-layout title="Tambah Anggota">
    <turbo-frame id="modal">
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/40 dark:bg-slate-950/40 backdrop-blur-md animate-fade-in"
             x-data="{}" @keydown.escape.window="document.getElementById('modal-close-btn').click()">
            
            <div @click.outside="document.getElementById('modal-close-btn').click()"
                 class="w-full max-w-xl bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col max-h-[90vh]">
                 
                 {{-- Modal Header --}}
                 <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent">
                     <div>
                         <h2 class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Tambah Anggota Baru</h2>
                         <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Daftarkan anggota baru ke dalam sistem organisasi</p>
                     </div>
                     <a href="{{ route('anggota.index') }}" data-turbo-frame="_top" id="modal-close-btn"
                        class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                     </a>
                 </div>

                 <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">
                     <form action="{{ route('anggota.store') }}" method="POST" data-turbo-frame="_top" class="space-y-6">
                         @csrf

                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                             <div>
                                 <label for="name" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                 <input type="text" id="name" name="name" value="{{ old('name') }}"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('name') border-rose-500 @enderror"
                                        placeholder="Nama lengkap anggota" required autofocus>
                                 @error('name')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                             <div>
                                 <label for="email" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Email <span class="text-rose-500">*</span></label>
                                 <input type="email" id="email" name="email" value="{{ old('email') }}"
                                        class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('email') border-rose-500 @enderror"
                                        placeholder="email@organisasi.com" required>
                                 @error('email')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                         </div>

                         <div>
                             <label for="password" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Password <span class="text-rose-500">*</span></label>
                             <input type="password" id="password" name="password"
                                    class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70 @error('password') border-rose-500 @enderror"
                                    placeholder="Minimal 8 karakter" required>
                             @error('password')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                         </div>

                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                             <div>
                                 <label for="jabatan" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Jabatan Struktur</label>
                                 <div class="relative">
                                     <select id="jabatan" name="jabatan" class="form-select w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none">
                                         <option value="">Pilih Jabatan</option>
                                         @foreach(getJabatanOptions() as $jabatan)
                                             <option value="{{ $jabatan }}" {{ old('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                                         @endforeach
                                     </select>
                                     <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                     </div>
                                 </div>
                                 @error('jabatan')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                             <div>
                                 <label for="role_id" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Role Sistem <span class="text-rose-500">*</span></label>
                                 <div class="relative">
                                     <select id="role_id" name="role_id" required class="form-select w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none @error('role_id') border-rose-500 @enderror">
                                         <option value="">Pilih Role</option>
                                         @foreach($roles as $role)
                                             <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                                         @endforeach
                                     </select>
                                     <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                         <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                     </div>
                                 </div>
                                 <p class="mt-1 pl-1 text-[11px] text-slate-500 dark:text-slate-400">Menentukan hak akses menu & fitur</p>
                                 @error('role_id')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                             </div>
                         </div>

                         <div>
                             <label for="is_active" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Status Akun</label>
                             <div class="relative">
                                 <select id="is_active" name="is_active" class="form-select w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all appearance-none">
                                     <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                     <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Nonaktif</option>
                                 </select>
                                 <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                     <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                 </div>
                             </div>
                         </div>

                         <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                             <a href="{{ route('anggota.index') }}" data-turbo-frame="_top" class="flex items-center justify-center px-6 h-11 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 dark:hover:text-white transition-all">Batal</a>
                             <button type="submit" class="px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-95 transition-all">Simpan Anggota</button>
                         </div>
                     </form>
                 </div>
                 
            </div>
        </div>
    </turbo-frame>
</x-admin-layout>
