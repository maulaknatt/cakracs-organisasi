@extends('profil.layout')

@section('title', '- Beranda')

@section('content')
<header class="relative overflow-hidden bg-dot-pattern pt-16 pb-24 lg:pt-32 lg:pb-48 px-6">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 pointer-events-none select-none z-0">
        <h2 class="text-[25vw] font-bold leading-none text-stroke opacity-20 tracking-tighter">CAKRA</h2>
    </div>
    <div class="max-w-7xl mx-auto grid lg:grid-cols-2 gap-16 items-center relative z-10">
        <div class="order-2 lg:order-1 animate-reveal">
            <div class="inline-block py-1 px-3 bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-[0.3em] mb-6 border border-primary/20">
                Independent Youth Cult 2024
            </div>
            <h1 class="text-[clamp(3.5rem,8vw,7rem)] leading-[0.9] font-bold text-slate-900 dark:text-white mb-8 tracking-tighter">
                MENDOBRAK <span class="text-primary">BATAS</span> KREATIVITAS.
            </h1>
            <p class="text-xl font-medium max-w-md mb-10 text-slate-600 dark:text-slate-400 leading-relaxed [animation-delay:200ms]">
                Kami adalah kolektif pemuda yang percaya bahwa inovasi lahir dari kolaborasi yang tidak terduga.
            </p>
            <div class="flex flex-wrap gap-6 [animation-delay:400ms]">
                <a href="{{ route('profil.kegiatan') }}" class="px-10 py-5 bg-primary text-white font-bold text-sm uppercase tracking-widest hover:bg-slate-900 transition-all">Mulai Eksplorasi</a>
                <a href="{{ route('profil.tentang') }}" class="px-10 py-5 border-2 border-slate-900 dark:border-white font-bold text-sm uppercase tracking-widest hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-all">Manifesto</a>
            </div>
        </div>
        <div class="relative order-1 lg:order-2 flex justify-center animate-reveal [animation-delay:600ms]">
            <div class="absolute -top-10 -right-6 z-30 bg-primary text-white p-6 rounded-full w-36 h-36 flex items-center justify-center text-center leading-none -rotate-12 border-4 border-white dark:border-background-dark shadow-2xl animate-bounce [animation-duration:3s]">
                <span class="font-bold text-xs tracking-tighter uppercase italic">Independent<br/>Youth Cult<br/>2024</span>
            </div>
            <div class="relative w-full max-w-md">
                <div class="absolute -inset-4 border border-primary/30 translate-x-4 translate-y-4"></div>
                <div class="relative z-10 bg-white dark:bg-slate-800 p-3 shadow-2xl">
                    @php $latest = $highlightKegiatan->first(); @endphp
                    @if($latest && $latest->dokumentasi->count() > 0)
                        <img class="w-full aspect-[3/4] object-cover filter grayscale hover:grayscale-0 transition-all duration-1000" src="{{ asset('storage/' . $latest->dokumentasi->first()->file) }}" alt="{{ $latest->judul }}"/>
                    @else
                        <img class="w-full aspect-[3/4] object-cover filter grayscale hover:grayscale-0 transition-all duration-1000" src="https://images.unsplash.com/photo-1523240715630-97fbb021190c?auto=format&fit=crop&q=80" alt="Default Hero"/>
                    @endif
                    <div class="mt-4 flex justify-between items-center text-[10px] font-bold uppercase tracking-widest text-slate-400">
                        <span>Archive No. {{ $latest ? $latest->id : '001' }}-24</span>
                        <span>Cakra Community</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<section class="bg-primary py-8 overflow-hidden border-y-2 border-slate-900 dark:border-white/10 reveal">
    <div class="flex whitespace-nowrap animate-marquee">
        <div class="flex items-center gap-12 mx-6">
            <span class="text-4xl font-bold text-white tracking-tighter uppercase italic">{{ $statistik['anggota'] }}+ Anggota Aktif —</span>
            <span class="text-4xl font-bold text-white/50 tracking-tighter uppercase italic">{{ $statistik['kegiatan'] }}+ Proyek Berhasil —</span>
            <span class="text-4xl font-bold text-white tracking-tighter uppercase italic">100% Inisiatif Lokal —</span>
            <span class="text-4xl font-bold text-white/50 tracking-tighter uppercase italic">Unlimited Kolaborasi —</span>
        </div>
        <div class="flex items-center gap-12 mx-6">
            <span class="text-4xl font-bold text-white tracking-tighter uppercase italic">{{ $statistik['anggota'] }}+ Anggota Aktif —</span>
            <span class="text-4xl font-bold text-white/50 tracking-tighter uppercase italic">{{ $statistik['kegiatan'] }}+ Proyek Berhasil —</span>
            <span class="text-4xl font-bold text-white tracking-tighter uppercase italic">100% Inisiatif Lokal —</span>
            <span class="text-4xl font-bold text-white/50 tracking-tighter uppercase italic">Unlimited Kolaborasi —</span>
        </div>
    </div>
</section>

<section class="py-32 px-6 bg-grid-pattern">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-24 gap-8">
            <div class="max-w-2xl reveal reveal-left">
                <span class="text-primary font-bold uppercase tracking-[0.4em] text-xs block mb-4">What we do</span>
                <h2 class="text-6xl lg:text-8xl font-bold tracking-tighter leading-none mb-0 text-slate-900 dark:text-white">AKTIVITAS <br/><span class="text-primary italic">KAMI.</span></h2>
            </div>
            <p class="max-w-xs text-sm font-bold text-slate-500 uppercase leading-relaxed border-l-4 border-primary pl-6 reveal reveal-right">
                Membangun ekosistem yang berkelanjutan bagi pelaku industri kreatif muda.
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4 group bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-2 reveal" style="transition-delay: 200ms">
                <div class="overflow-hidden mb-6">
                    <img class="w-full h-96 object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700" src="https://images.unsplash.com/photo-1515187029135-18ee286d815b?auto=format&fit=crop&q=80" alt="Workshop"/>
                </div>
                <div class="px-6 pb-8">
                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4 block">01 / Skill Focus</span>
                    <h3 class="text-2xl font-bold mb-4 tracking-tight text-slate-900 dark:text-white">Workshop Kreatif</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">Mentorship intensif dengan pakar industri untuk mengasah technical skill yang relevan.</p>
                    <a class="text-xs font-bold uppercase tracking-widest flex items-center gap-2 group-hover:text-primary transition-colors text-slate-900 dark:text-white" href="{{ route('profil.kegiatan') }}">Learn More <span class="material-icons text-sm">arrow_forward</span></a>
                </div>
            </div>

            <div class="md:col-span-4 bg-primary text-white p-12 flex flex-col justify-between min-h-[500px] border-x-4 border-slate-900 dark:border-white/20 reveal" style="transition-delay: 400ms">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-[0.4em] mb-8 block opacity-80">02 / Art Collective</span>
                    <h3 class="text-5xl font-bold leading-[0.9] tracking-tighter mb-8 italic">KOLABORASI SENI TANPA SEKAT.</h3>
                </div>
                <div>
                    <div class="flex -space-x-3 mb-8">
                        @foreach($statistik['anggota_random'] ?? [] as $anggota)
                            <div class="w-12 h-12 rounded-full border-2 border-primary bg-slate-200 flex items-center justify-center text-[10px] overflow-hidden">
                                @if($anggota->foto)
                                    <img src="{{ asset('storage/' . $anggota->foto) }}" class="w-full h-full object-cover"/>
                                @else
                                    {{ substr($anggota->nama, 0, 2) }}
                                @endif
                            </div>
                        @endforeach
                        <div class="w-12 h-12 rounded-full border-2 border-primary bg-white flex items-center justify-center text-primary font-bold text-xs">+{{ max(0, $statistik['anggota'] - 3) }}</div>
                    </div>
                    <a href="{{ route('profil.kontak') }}" class="block w-full py-5 bg-white text-primary text-center font-bold text-xs uppercase tracking-widest hover:bg-slate-900 hover:text-white transition-all">Submit Portfolio</a>
                </div>
            </div>

            <div class="md:col-span-4 group bg-white dark:bg-slate-900 border border-slate-200 dark:border-white/10 p-2 reveal" style="transition-delay: 600ms">
                <div class="overflow-hidden mb-6">
                    <img class="w-full h-96 object-cover grayscale group-hover:grayscale-0 group-hover:scale-105 transition-all duration-700" src="https://images.unsplash.com/photo-1488521787991-ed7bbaae773c?auto=format&fit=crop&q=80" alt="Social Impact"/>
                </div>
                <div class="px-6 pb-8">
                    <span class="text-[10px] font-bold text-primary uppercase tracking-widest mb-4 block">03 / Movement</span>
                    <h3 class="text-2xl font-bold mb-4 tracking-tight text-slate-900 dark:text-white">Aksi Sosial</h3>
                    <p class="text-slate-500 text-sm leading-relaxed mb-6">Menggunakan kreativitas untuk memecahkan masalah sosial di komunitas lokal.</p>
                    <a class="text-xs font-bold uppercase tracking-widest flex items-center gap-2 group-hover:text-primary transition-colors text-slate-900 dark:text-white" href="{{ route('profil.kegiatan') }}">Read Impact <span class="material-icons text-sm">arrow_forward</span></a>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-32 bg-white dark:bg-slate-950 px-6">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-24 animate-reveal">
            <h2 class="text-7xl lg:text-9xl font-bold tracking-tighter uppercase mb-4 opacity-10 text-slate-900 dark:text-white">ARCHIVE</h2>
            <h3 class="text-4xl lg:text-6xl font-bold tracking-tighter -mt-16 relative z-10 text-slate-900 dark:text-white">LENSA <span class="text-primary italic">CAKRA</span></h3>
        </div>
        
        <div class="grid grid-cols-12 gap-4 md:gap-8">
            <div class="col-span-12 md:col-span-8 group relative aspect-[16/10] overflow-hidden bg-slate-100 dark:bg-slate-800 reveal reveal-left">
                <div class="absolute top-6 left-6 z-20 bg-primary text-white text-[10px] font-bold px-4 py-2 uppercase tracking-widest">Featured Moment</div>
                @if($fotoPilihan->first())
                    <img class="w-full h-full object-cover filter grayscale hover:grayscale-0 transition-all duration-1000 scale-105" src="{{ asset('storage/' . $fotoPilihan->first()->file) }}" alt="Featured"/>
                @else
                    <img class="w-full h-full object-cover grayscale" src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&q=80"/>
                @endif
                <div class="absolute inset-0 border-[20px] border-white/10 pointer-events-none"></div>
            </div>
            
            <div class="col-span-12 md:col-span-4 grid grid-rows-2 gap-4 md:gap-8 reveal reveal-right">
                <div class="bg-primary/5 p-2 border border-primary/20 overflow-hidden">
                    <img class="w-full h-full object-cover grayscale hover:scale-110 transition-transform duration-[2s]" src="https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&q=80"/>
                </div>
                <div class="bg-slate-900 flex items-center justify-center p-8 text-center border-l-8 border-primary">
                    <div>
                        <span class="text-white/40 text-[10px] font-bold tracking-[0.4em] uppercase mb-4 block">Manifesto</span>
                        <p class="text-white text-lg font-bold italic">"Kami tidak sekadar mengambil gambar, kami membingkai masa depan."</p>
                    </div>
                </div>
            </div>

            @foreach($fotoPilihan->skip(1)->take(3) as $key => $foto)
                <div class="col-span-12 md:col-span-4 aspect-square overflow-hidden border-2 border-primary reveal" style="transition-delay: {{ 0.2 + ($key * 0.1) }}s">
                    <img class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500" src="{{ asset('storage/' . $foto->file) }}"/>
                </div>
            @endforeach
        </div>
        
        <div class="mt-16 text-center animate-reveal">
            <a href="{{ route('profil.galeri') }}" class="inline-block px-12 py-5 border-2 border-slate-900 dark:border-white font-bold text-sm uppercase tracking-widest hover:bg-slate-900 hover:text-white dark:hover:bg-white dark:hover:text-slate-900 transition-all">Lihat Seluruh Galeri</a>
        </div>
    </div>
</section>

<section class="py-32 px-6 overflow-hidden bg-slate-900 dark:bg-black reveal">
    <div class="max-w-7xl mx-auto">
        <div class="relative bg-gradient-to-br from-slate-900 via-slate-800 to-primary/20 border-2 border-primary/30 p-12 lg:p-32 text-center overflow-hidden animate-reveal">
            <div class="absolute inset-0 bg-dot-pattern opacity-10"></div>
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
            <div class="absolute -bottom-24 -right-24 w-96 h-96 bg-primary/20 rounded-full blur-[120px]"></div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <span class="text-primary font-bold text-xs uppercase tracking-[0.6em] block mb-10">The Call to Action</span>
                <h2 class="text-5xl lg:text-8xl font-bold tracking-tighter text-white mb-12 leading-[0.9]">
                    Ini bukan sekadar komunitas. Ini adalah sebuah <span class="italic text-primary">pergerakan.</span>
                </h2>
                <p class="text-slate-400 text-lg md:text-xl mb-16 max-w-2xl mx-auto font-medium">
                    Jadilah katalisator perubahan dalam ekosistem kreatif Indonesia. Kursi Anda telah menanti.
                </p>
                <div class="flex flex-col md:flex-row items-center justify-center gap-8">
                    <a href="{{ route('login') }}" class="group w-full md:w-auto px-16 py-6 bg-primary text-white font-bold text-sm tracking-[0.2em] uppercase hover:bg-white hover:text-primary transition-all shadow-[0_20px_50px_rgba(37,106,244,0.3)] flex items-center justify-center gap-4">
                        Gabung Sekarang
                        <span class="material-icons group-hover:translate-x-2 transition-transform">arrow_forward</span>
                    </a>
                    <a href="{{ route('profil.kontak') }}" class="w-full md:w-auto px-16 py-6 border border-white/20 text-white font-bold text-sm tracking-[0.2em] uppercase hover:bg-white hover:text-slate-900 transition-all">
                        Hubungi Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>@endsection
