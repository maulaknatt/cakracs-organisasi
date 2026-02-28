<x-admin-layout title="Detail Absensi">
<div class="mb-12 flex flex-col lg:flex-row lg:items-end justify-between gap-6">
    <div class="space-y-4">
        <a href="{{ route('attendance.index') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-blue-600 dark:text-blue-400 hover:opacity-75 transition-all group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span>Kembali ke Daftar</span>
        </a>
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $session->title }}</h1>
            <div class="flex items-center gap-2 text-[13px] font-medium text-slate-500 dark:text-slate-400">
                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <span>{{ $session->date->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>
    </div>
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
        @php
            // Check permission for manual attendance
            $canManualAttend = auth()->user()->isSuperAdmin() || auth()->user()->isPengurus() || auth()->user()->jabatan === 'Ketua';
            $currentUserLog = $session->logs->where('user_id', auth()->id())->first();
        @endphp

        @if($canManualAttend)
        <a href="{{ route('attendance.export', $session->id) }}" class="flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 h-11 px-5 rounded-xl text-sm font-bold transition shadow-sm">
            <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span>Excel</span>
        </a>
        @endif

        @if(!$currentUserLog && $canManualAttend && $session->is_active)
        <form action="{{ route('attendance.storeManual', $session->id) }}" method="POST" class="w-full sm:w-auto">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <button type="submit" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white h-11 px-5 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                <span>Hadir</span>
            </button>
        </form>
        @endif

        @if($session->is_active)
        <button onclick="openQrModal()" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white h-11 px-5 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-500/20">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            <span>Tampilkan QR</span>
        </button>
        @endif

        <div class="flex items-center justify-center gap-2 px-4 h-11 rounded-xl text-xs font-black uppercase tracking-widest border {{ $session->is_active ? 'bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20' : 'bg-slate-50 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $session->is_active ? 'bg-blue-500 animate-pulse' : 'bg-slate-400' }}"></span>
            <span>{{ $session->is_active ? 'Aktif' : 'Tutup' }}</span>
        </div>
    </div>
</div>

{{-- Explicit Spacer to prevent margin collapse --}}
<div class="h-8 md:h-12"></div>

{{-- Table Card with Fixed Height for 10 Rows --}}
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-white/5 flex flex-col overflow-hidden">
    <div class="max-h-[650px] overflow-y-auto overflow-x-auto custom-scrollbar flex-1 relative">
        <table class="w-full min-w-[700px] text-left text-sm border-separate border-spacing-0">
            <thead class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-400 sticky top-0 z-10 backdrop-blur-sm">
                <tr>
                    <th class="px-6 py-4 font-semibold">Nama Anggota</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold">Waktu Absensi</th>
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isPengurus() || auth()->user()->jabatan === 'Ketua')
                    <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($attendanceData as $data)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-medium text-slate-900 dark:text-white">{{ $data['user']->name }}</div>
                        <div class="text-xs text-slate-500">{{ $data['user']->email }}</div>
                    </td>
                    <td class="px-6 py-4">
                        @if($data['is_present'])
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                Hadir
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                                Tidak Hadir
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 font-mono">
                        {{ $data['scanned_at'] ? $data['scanned_at']->format('H:i:s') : '-' }}
                    </td>
                    @if(auth()->user()->isSuperAdmin() || auth()->user()->isPengurus() || auth()->user()->jabatan === 'Ketua')
                    <td class="px-6 py-4 text-center">
                        @if($data['is_present'])
                            <form action="{{ route('attendance.destroyManual', ['session' => $session->id, 'user' => $data['user']->id]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Batalkan Absensi', 'Batalkan absensi user ini? Data kehadiran akan dihapus.', 'Ya, Hapus')" class="text-xs bg-red-50 text-red-600 hover:bg-red-100 px-3 py-1.5 rounded-md font-medium transition">
                                    ❌ Batalkan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('attendance.storeManual', ['session' => $session->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $data['user']->id }}">
                                <button type="submit" class="text-xs bg-blue-50 text-blue-600 hover:bg-blue-100 px-3 py-1.5 rounded-md font-medium transition">
                                    ✅ Hadirkan
                                </button>
                            </form>
                        @endif
                    </td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($attendanceData->hasPages())
        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900/30 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-xs text-slate-600 dark:text-slate-400 font-medium">
                Menampilkan <span class="text-slate-900 dark:text-white">{{ $attendanceData->firstItem() }}</span> 
                sampai <span class="text-slate-900 dark:text-white">{{ $attendanceData->lastItem() }}</span> 
                dari <span class="text-slate-900 dark:text-white">{{ $attendanceData->total() }}</span> anggota
            </div>
            <div class="pagination-container">
                {{ $attendanceData->links() }}
            </div>
        </div>
    @endif
</div>

@push('modals')
    {{-- QR Code Modal --}}
    <div id="modal-qr" class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-slate-950/80 backdrop-blur-lg transition-opacity opacity-0 pointer-events-none" style="transition: opacity 0.3s ease;">
        
        <div class="bg-white dark:bg-[#111827] rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl max-w-sm w-full p-6 sm:p-8 relative transform scale-95 transition-transform duration-300 flex flex-col items-center border border-slate-200 dark:border-white/5" style="transition: transform 0.3s ease;">
            
            <button onclick="closeQrModal()" class="absolute top-6 right-6 p-2 text-slate-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-500/10 rounded-full transition-all">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight mb-2">Scan Absensi</h3>
                <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 rounded-full text-[11px] font-bold uppercase tracking-wider">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                    Kode Diperbarui Otomatis
                </div>
            </div>

            <div class="relative group">
                <div class="absolute -top-2 -left-2 sm:-top-3 sm:-left-3 w-6 h-6 sm:w-8 sm:h-8 border-t-4 border-l-4 border-blue-600 rounded-tl-lg sm:rounded-tl-xl"></div>
                <div class="absolute -top-2 -right-2 sm:-top-3 sm:-right-3 w-6 h-6 sm:w-8 sm:h-8 border-t-4 border-r-4 border-blue-600 rounded-tr-lg sm:rounded-tr-xl"></div>
                <div class="absolute -bottom-2 -left-2 sm:-bottom-3 sm:-left-3 w-6 h-6 sm:w-8 sm:h-8 border-b-4 border-l-4 border-blue-600 rounded-bl-lg sm:rounded-bl-xl"></div>
                <div class="absolute -bottom-2 -right-2 sm:-bottom-3 sm:-right-3 w-6 h-6 sm:w-8 sm:h-8 border-b-4 border-r-4 border-blue-600 rounded-br-lg sm:rounded-br-xl"></div>

                <div class="p-3 bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-100 relative overflow-hidden flex items-center justify-center size-[220px] sm:size-[260px]">
                     <div id="qrcode" class="flex items-center justify-center overflow-hidden rounded-xl"></div>
                     
                     {{-- Loading Overlay --}}
                     <div id="qr-loading" class="absolute inset-0 bg-white/95 backdrop-blur-sm flex flex-col items-center justify-center rounded-lg z-10 hidden">
                        <svg class="animate-spin h-10 w-10 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Memuat...</span>
                     </div>
                </div>
            </div>
            
            <div class="mt-10 flex flex-col items-center gap-1">
                <p class="text-[13px] font-bold text-slate-500 dark:text-slate-400">Silakan scan melalui aplikasi mobile</p>
                <p class="text-[11px] font-medium text-slate-400 dark:text-slate-500 tracking-wide">Refresh otomatis setiap 25 detik</p>
            </div>
        </div>
    </div>
@endpush

@push('scripts')
{{-- QR Code Library --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    let qrInterval;
    let qrCodeObj = null;
    const SESSION_ID = "{{ $session->id }}";
    const QR_URL_BASE = "{{ route('attendance.getQrToken', $session->id) }}";

    function openQrModal() {
        const modal = document.getElementById('modal-qr');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        
        // Scale effect for content
        const content = modal.firstElementChild;
        content.classList.remove('scale-95');
        content.classList.add('scale-100');

        fetchQrToken(); // Fetch immediately
        
        // Start loop (25 seconds = 25000 ms)
        qrInterval = setInterval(fetchQrToken, 25000);
    }

    function closeQrModal() {
        const modal = document.getElementById('modal-qr');
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');

        // Scale effect for content
        const content = modal.firstElementChild;
        content.classList.remove('scale-100');
        content.classList.add('scale-95');

        clearInterval(qrInterval);

        // Remove query param from URL without refreshing
        const url = new URL(window.location);
        url.searchParams.delete('open_qr');
        window.history.replaceState({}, '', url);
    }

    async function fetchQrToken() {
        try {
            const response = await fetch(QR_URL_BASE, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            if (!response.ok) throw new Error('Gagal memuat token');

            const data = await response.json();
            renderQr(data.token);

        } catch (error) {
            console.error(error);
        }
    }

    function renderQr(token) {
        const container = document.getElementById('qrcode');
        container.innerHTML = ''; // Clear previous
        
        // Using qrcode.js
        new QRCode(container, {
            text: token,
            width: 256,
            height: 256,
            colorDark : "#000000",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.L
        });

        // Add responsive styling to the generated image/canvas
        const img = container.querySelector('img');
        const canvas = container.querySelector('canvas');
        if (img) img.style.maxWidth = '100%';
        if (canvas) canvas.style.maxWidth = '100%';
    }

    // Initialize if URL has parameter
    document.addEventListener("turbo:load", () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('open_qr') === 'true' && document.querySelector('button[onclick="openQrModal()"]')) {
            openQrModal();
        }
    });
</script>
@endpush
</x-admin-layout>

