<footer class="bg-background-dark text-white pt-32 pb-12 px-6 border-t border-white/5">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-16 mb-24">
            <div class="md:col-span-5">
                <div class="flex items-center gap-3 mb-10">
                    <div class="w-10 h-10 bg-primary flex items-center justify-center">
                        <span class="text-white font-bold text-xl uppercase leading-none">C</span>
                    </div>
                    <span class="text-2xl font-bold tracking-[0.2em] uppercase">CAKRA CS</span>
                </div>
                <p class="text-slate-500 text-lg max-w-md mb-12 leading-relaxed">
                    Wadah progresif bagi pikiran kreatif. Kami membangun infrastruktur budaya untuk masa depan yang lebih inovatif.
                </p>
                <div class="flex gap-8">
                    <a class="text-slate-400 hover:text-primary transition-colors text-xs font-bold uppercase tracking-widest" href="#">Instagram</a>
                    <a class="text-slate-400 hover:text-primary transition-colors text-xs font-bold uppercase tracking-widest" href="#">Twitter</a>
                    <a class="text-slate-400 hover:text-primary transition-colors text-xs font-bold uppercase tracking-widest" href="#">Discord</a>
                </div>
            </div>
            
            <div class="md:col-span-2 md:col-start-7">
                <h4 class="text-primary font-bold uppercase tracking-widest text-[10px] mb-8">Eksplorasi</h4>
                <ul class="space-y-4">
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('profil.tentang') }}">Tentang</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('profil.kegiatan') }}">Program</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('profil.galeri') }}">Galeri</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="#">Karier</a></li>
                </ul>
            </div>
            
            <div class="md:col-span-2">
                <h4 class="text-primary font-bold uppercase tracking-widest text-[10px] mb-8">Informasi</h4>
                <ul class="space-y-4">
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('login') }}">Keanggotaan</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="#">Donasi</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="#">FAQ</a></li>
                    <li><a class="text-slate-400 hover:text-white transition-colors text-sm" href="{{ route('profil.kontak') }}">Kontak</a></li>
                </ul>
            </div>
            
            <div class="md:col-span-2">
                <h4 class="text-primary font-bold uppercase tracking-widest text-[10px] mb-8">Kontak</h4>
                <address class="not-italic text-sm text-slate-400 leading-loose">
                    Jakarta, Indonesia<br/>
                    halo@cakracs.id<br/>
                    +62 812 3456 7890
                </address>
            </div>
        </div>
        
        <div class="pt-12 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] font-bold uppercase tracking-widest text-slate-600">
            <p>© {{ date('Y') }} CAKRA CS. The Future is Creative.</p>
            <div class="flex gap-10">
                <a class="hover:text-primary transition-colors" href="#">Privacy Policy</a>
                <a class="hover:text-primary transition-colors" href="#">Terms of Movement</a>
            </div>
        </div>
    </div>
</footer>
