<x-admin-layout title="Scan Absensi" :noPadding="true">
    <div class="min-h-[100dvh] flex flex-col items-center pt-2 sm:pt-6 pb-6 px-4 overflow-hidden">
        {{-- Header --}}
        <div class="mb-3 sm:mb-8 text-center animate-fade-in-up">
            <div class="inline-flex items-center justify-center w-12 h-12 sm:w-20 sm:h-20 rounded-2xl sm:rounded-3xl mb-2 sm:mb-6 bg-gradient-to-br from-blue-600 to-blue-400 shadow-xl shadow-blue-500/30 ring-4 ring-white dark:ring-slate-800">
                <svg class="w-6 h-6 sm:w-10 sm:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
            </div>
            <h1 class="text-xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tighter mb-1 leading-tight">Scan Absensi</h1>
            <p class="text-[12px] sm:text-base text-slate-500 dark:text-slate-400 max-w-[260px] sm:max-w-sm mx-auto font-medium">Tempatkan QR Code di dalam kotak</p>
        </div>

        <div class="max-w-[400px] w-full">

        {{-- Scanner Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-[32px] border border-slate-200 dark:border-slate-800/60 overflow-hidden shadow-[0_32px_64px_-16px_rgba(0,0,0,0.2)] dark:shadow-[0_32px_64px_-16px_rgba(0,0,0,0.6)] transition-all duration-300">
            {{-- Video Viewport --}}
            <div class="relative h-[250px] sm:h-[320px]">
                <div id="reader" class="w-full h-full" style="background:#000;"></div>

                {{-- Scanning Frame Overlay --}}
                <div class="absolute inset-0 pointer-events-none flex items-center justify-center" id="scan-frame-overlay">
                    <div class="relative w-48 h-48 sm:w-60 sm:h-60 z-10">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-xl"></div>
                        <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-xl"></div>
                        <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-xl"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-xl"></div>
                        <div class="absolute left-4 right-4 h-0.5 bg-blue-500/80 scan-line shadow-[0_0_15px_rgba(59,130,246,0.8)]"></div>
                    </div>
                    <p class="absolute bottom-6 left-0 right-0 text-center text-[10px] sm:text-[11px] font-bold tracking-[0.2em] text-white/50 uppercase z-10">Searching...</p>
                </div>

                {{-- Processing Overlay --}}
                <div id="scan-preview" class="hidden absolute inset-0 z-20 bg-slate-950/95 backdrop-blur-sm flex flex-col items-center justify-center p-8 gap-5 animate-fade-in">
                    <div class="relative w-48 h-48 rounded-3xl overflow-hidden border-2 border-blue-500/30 group">
                        <div id="preview-image-container" class="w-full h-full transform transition-transform group-hover:scale-110 duration-700"></div>
                        <div class="absolute inset-0 flex items-center justify-center bg-blue-600/10 backdrop-blur-[2px]">
                            <svg class="animate-spin h-10 w-10 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-100" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-white font-bold text-base tracking-tight">Menganalisis QR Code...</p>
                </div>

                {{-- Result Overlay --}}
                <div id="status-overlay" class="hidden absolute inset-0 z-30 bg-white dark:bg-slate-900 flex flex-col items-center justify-center p-10 text-center animate-fade-in">
                    <div id="status-icon" class="w-24 h-24 rounded-full flex items-center justify-center mb-6 transform scale-0 transition-transform duration-500"></div>
                    <h3 id="status-title" class="text-2xl font-black mb-3 leading-tight tracking-tight"></h3>
                    <p id="status-message" class="text-[15px] text-slate-500 dark:text-slate-400 leading-relaxed mb-8 max-w-[280px] mx-auto"></p>
                    <button onclick="resetScanner()"
                            class="w-full py-4 rounded-2xl font-bold text-base text-white transition-all transform active:scale-[0.98] hover:shadow-xl bg-gradient-to-br from-blue-600 to-blue-500 shadow-lg shadow-blue-500/25">
                        Mulai Scan Baru
                    </button>
                </div>
            </div>

            {{-- Action Footer --}}
            <div class="bg-slate-50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 p-3 sm:p-6 space-y-3">
                <label for="qr-input-file"
                       class="flex items-center justify-center gap-3 w-full py-2.5 sm:py-4 px-6 rounded-2xl cursor-pointer transition-all duration-200
                              bg-white dark:bg-white/5 hover:bg-slate-100 dark:hover:bg-white/10
                              border border-slate-200 dark:border-white/10 text-[13px] sm:text-base font-bold text-slate-700 dark:text-slate-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Pilih Dari Galeri
                    <input type="file" id="qr-input-file" accept="image/*" class="hidden">
                </label>
                <div class="flex items-start gap-2 px-1">
                    <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                    <p class="text-[10.5px] text-slate-500 dark:text-slate-500 leading-snug">
                        Gunakan upload manual jika kamera gagal terbuka.
                    </p>
                </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        @keyframes scan { 0% { top: 0; } 100% { top: 100%; } }
        .scan-line { animation: scan 2.5s cubic-bezier(0.4, 0, 0.2, 1) infinite; }
        #reader { overflow: hidden !important; }
        #reader video { width: 100% !important; height: 100% !important; object-fit: cover; }
        #reader img { display: none !important; }
        #reader div:not([id]) { border: none !important; }
        .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
    @endpush

    @push('scripts')
    <script>
        let html5QrcodeScanner;
        const SCAN_URL = "{{ route('scan.store') }}";
        const CSRF_TOKEN = "{{ csrf_token() }}";
        let isScanning = true;

        document.getElementById('qr-input-file').addEventListener('change', e => {
            if (!e.target.files.length) return;
            const imageFile = e.target.files[0];
            if (html5QrcodeScanner && html5QrcodeScanner.getState() === 2) html5QrcodeScanner.pause();
            document.getElementById('scan-preview').classList.remove('hidden');
            const fr = new FileReader();
            fr.onload = ev => document.getElementById('preview-image-container').innerHTML = `<img src="${ev.target.result}" class="w-full h-full object-cover">`;
            fr.readAsDataURL(imageFile);
            processImage(imageFile).then(f => html5QrcodeScanner.scanFile(f, true).then(text => onScanSuccess(text)).catch(() => { document.getElementById('scan-preview').classList.add('hidden'); showStatus('error','Gagal Membaca','QR Code tidak terdeteksi. Pastikan gambar jelas.'); })).catch(() => { document.getElementById('scan-preview').classList.add('hidden'); showStatus('error','Gagal Memproses','Format gambar tidak didukung.'); });
        });

        function processImage(file) {
            return new Promise((resolve, reject) => {
                const img = new Image(), MAX = 1200;
                img.onload = () => {
                    const c = document.createElement('canvas'); const cx = c.getContext('2d');
                    let w = img.width, h = img.height;
                    if (w > h) { if (w > MAX) { h *= MAX/w; w = MAX; } } else { if (h > MAX) { w *= MAX/h; h = MAX; } }
                    c.width = w; c.height = h; cx.fillStyle='#FFF'; cx.fillRect(0,0,w,h); cx.drawImage(img,0,0,w,h);
                    c.toBlob(b => b ? resolve(new File([b], file.name, {type:'image/jpeg'})) : reject(), 'image/jpeg', 0.9);
                };
                img.onerror = reject;
                img.src = URL.createObjectURL(file);
            });
        }

        function onScanSuccess(text) {
            if (!isScanning) return;
            isScanning = false;
            if (html5QrcodeScanner && html5QrcodeScanner.getState() === 2) html5QrcodeScanner.pause();
            document.getElementById('scan-preview').classList.add('hidden');
            verifyToken(text);
        }

        async function verifyToken(token) {
            try {
                const res = await fetch(SCAN_URL, { 
                    method:'POST', 
                    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF_TOKEN,'Accept':'application/json'}, 
                    body:JSON.stringify({token}) 
                });
                const data = await res.json();
                if (res.ok) showStatus('success','Absensi Berhasil! 🎉', data.message);
                else showStatus('error','Absensi Gagal', data.message || 'Terjadi kesalahan.');
            } catch { showStatus('error','Koneksi Error','Gagal menghubungi server.'); }
        }

        function showStatus(type, title, message) {
            const overlay = document.getElementById('status-overlay'), icon = document.getElementById('status-icon');
            overlay.classList.remove('hidden');
            if (type === 'success') {
                icon.className = 'w-24 h-24 rounded-full flex items-center justify-center mb-6 scale-0 bg-blue-100 dark:bg-blue-500/10 text-blue-500';
                icon.innerHTML = '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>';
                document.getElementById('status-title').className = 'text-2xl font-black mb-2 text-slate-800 dark:text-blue-400';
            } else {
                icon.className = 'w-24 h-24 rounded-full flex items-center justify-center mb-6 scale-0 bg-rose-100 dark:bg-rose-500/10 text-rose-500';
                icon.innerHTML = '<svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path d="M6 18L18 6M6 6l12 12"/></svg>';
                document.getElementById('status-title').className = 'text-2xl font-black mb-2 text-slate-800 dark:text-rose-400';
            }
            document.getElementById('status-title').textContent = title;
            document.getElementById('status-message').innerHTML = message;
            setTimeout(() => { icon.classList.remove('scale-0'); icon.classList.add('scale-100'); }, 100);
        }

        function resetScanner() {
            document.getElementById('status-overlay').classList.add('hidden');
            document.getElementById('status-icon').classList.replace('scale-100','scale-0');
            isScanning = true;
            if (html5QrcodeScanner && html5QrcodeScanner.getState() === 3) html5QrcodeScanner.resume();
        }

        document.addEventListener('turbo:load', () => {
            const readerEl = document.getElementById('reader');
            if (!readerEl || html5QrcodeScanner) return;

            html5QrcodeScanner = new Html5Qrcode('reader');
            const config = { fps: 15, qrbox: { width: 250, height: 250 } };
            
            html5QrcodeScanner.start({ facingMode:'environment' }, config, onScanSuccess, ()=>{})
                .catch(() => html5QrcodeScanner.start({ facingMode:'user' }, config, onScanSuccess, ()=>{})
                .catch(e => {
                    let msg = 'Izin kamera ditolak atau tidak tersedia.';
                    if (!window.isSecureContext) msg = 'Akses kamera membutuhkan koneksi HTTPS.';
                    readerEl.innerHTML = `<div class="h-full flex flex-col items-center justify-center p-8 text-center bg-slate-900 gap-4"><div class="w-14 h-14 rounded-2xl bg-rose-500/10 flex items-center justify-center"><svg class="w-7 h-7 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" stroke-width="2"/></svg></div><div><p class="text-white font-bold text-base mb-1">Kamera Error</p><p class="text-slate-400 text-sm">${msg}</p></div></div>`;
                }));
        });

        // Cleanup on page change
        document.addEventListener('turbo:before-cache', () => {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().catch(() => {});
                html5QrcodeScanner = null;
            }
        });
    </script>
    @endpush
</x-admin-layout>
