<x-admin-layout title="Pengaturan">
<div class="page-header mb-8">
    <div>
        <h1 class="page-header-title">Pengaturan & Profil</h1>
        <p class="page-header-sub">Kelola pengaturan aplikasi dan profil akun Anda</p>
    </div>
</div>

<div class="card-glow mt-6" x-data="{ activeTab: '{{ session('active_tab', (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) ? 'organisasi' : 'pribadi') }}' }">
    {{-- Tab Navigation --}}
    <div class="border-b border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/50">
        <div class="flex overflow-x-auto">
            @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
                <button @click="activeTab = 'organisasi'" 
                    :class="activeTab === 'organisasi' ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-400 bg-white dark:bg-slate-800' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200'"
                    class="px-5 py-3.5 text-sm font-semibold border-b-2 transition-colors">
                    Profil Organisasi
                </button>
            @endif
            
            <button @click="activeTab = 'pribadi'"
                :class="activeTab === 'pribadi' ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-400 bg-white dark:bg-slate-800' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-5 py-3.5 text-sm font-semibold border-b-2 transition-colors">
                Profil Pribadi
            </button>
            
            <button @click="activeTab = 'password'"
                :class="activeTab === 'password' ? 'border-blue-600 text-blue-600 dark:border-blue-500 dark:text-blue-400 bg-white dark:bg-slate-800' : 'border-transparent text-slate-500 hover:text-slate-800 hover:border-slate-300 dark:text-slate-400 dark:hover:text-slate-200'"
                class="px-5 py-3.5 text-sm font-semibold border-b-2 transition-colors">
                Ganti Password
            </button>
        </div>
    </div>
    
    <div class="p-6">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm border border-green-200 dark:border-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg text-sm border border-red-200 dark:border-red-800">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Profil Organisasi Tab --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin())
            <div x-show="activeTab === 'organisasi'" style="display: none;">
                <form action="{{ route('pengaturan.updateOrganization') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <h2 class="text-base font-medium text-slate-900 dark:text-white mb-1">Identitas Organisasi</h2>
                        <p class="text-sm text-slate-500 mb-4">Informasi utama organisasi yang akan tampil di aplikasi.</p>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                    Logo Organisasi
                                </label>
                                <div class="flex items-center gap-4">
                                    @if(!empty($pengaturan->logo))
                                        <div class="relative group">
                                            <img src="{{ asset('storage/'.$pengaturan->logo) }}" class="h-16 w-16 object-contain border border-slate-200 dark:border-slate-700 rounded-lg bg-slate-50 p-1" alt="Logo">
                                            {{-- Delete Button Trigger --}}
                                            <button type="button" onclick="if(confirm('Hapus logo organisasi?')) document.getElementById('delete-logo-form').submit()" 
                                                class="absolute -top-2 -right-2 bg-white dark:bg-slate-800 p-1 text-red-500 hover:text-red-600 rounded-full shadow-sm border border-red-200 dark:border-red-800 transition-colors" title="Hapus Logo">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <div class="h-16 w-16 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                    <input type="file" name="logo" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>

                            <div>
                                <label for="nama_organisasi" class="form-label">
                                    Nama Organisasi <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="nama_organisasi" name="nama_organisasi" value="{{ old('nama_organisasi', $pengaturan->nama_organisasi ?? '') }}" required
                                    class="form-input">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                        <button type="submit" class="btn-primary">
                            Simpan Perubahan Organisasi
                        </button>
                    </div>
                </form>

                {{-- External Delete Logo Form --}}
                <form id="delete-logo-form" action="{{ route('pengaturan.deleteOrganizationLogo') }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endif

        {{-- Profil Pribadi Tab --}}
        <div x-show="activeTab === 'pribadi'" style="display: none;">
            <form action="{{ route('pengaturan.updateProfile') }}" method="POST" enctype="multipart/form-data" class="space-y-6 max-w-2xl">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-base font-medium text-slate-900 dark:text-white mb-1">Profil Saya</h2>
                    <p class="text-sm text-slate-500 mb-4">Informasi pribadi akun Anda.</p>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                                Foto Profil
                            </label>
                            <div class="flex items-center gap-4">
                                @if($user->foto_profil)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/'.$user->foto_profil) }}" class="h-16 w-16 object-cover border border-slate-200 dark:border-slate-700 rounded-full" alt="Foto Profil">
                                        {{-- Delete Button Trigger --}}
                                        <button type="button" onclick="if(confirm('Hapus foto profil?')) document.getElementById('delete-photo-form').submit()" 
                                            class="absolute -top-2 -right-2 bg-white dark:bg-slate-800 p-1 text-red-500 hover:text-red-600 rounded-full shadow-sm border border-red-200 dark:border-red-800 transition-colors" title="Hapus Foto">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4"viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </div>
                                @else
                                    <div class="h-16 w-16 rounded-full bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 text-xl font-bold border border-slate-200 dark:border-slate-600">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="foto_profil" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG. Maks: 2MB.</p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                class="form-input">
                        </div>

                        <div>
                            <label for="email" class="form-label">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                class="form-input">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                    <button type="submit" class="btn-primary">
                        Simpan Profil
                    </button>
                </div>
            </form>

            {{-- External Delete Form --}}
            <form id="delete-photo-form" action="{{ route('pengaturan.deleteProfilePhoto') }}" method="POST" class="hidden">
                @csrf
                @method('DELETE')
            </form>
        </div>

        {{-- Ganti Password Tab --}}
        <div x-show="activeTab === 'password'" style="display: none;">
            <form action="{{ route('pengaturan.updatePassword') }}" method="POST" class="space-y-6 max-w-2xl">
                @csrf
                @method('PUT')

                <div>
                    <h2 class="text-base font-medium text-slate-900 dark:text-white mb-1">Keamanan</h2>
                    <p class="text-sm text-slate-500 mb-4">Perbarui kata sandi akun Anda untuk menjaga keamanan.</p>

                    <div class="space-y-4">
                        <div>
                            <label for="current_password" class="form-label">
                                Password Saat Ini <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="current_password" name="current_password" required
                                class="form-input">
                        </div>

                        <div>
                            <label for="password" class="form-label">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password" name="password" required
                                class="form-input">
                        </div>

                        <div>
                            <label for="password_confirmation" class="form-label">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="form-input">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 dark:border-slate-700 flex justify-end">
                    <button type="submit" class="btn-primary">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-admin-layout>

