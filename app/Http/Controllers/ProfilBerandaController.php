<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\Dokumentasi;
use App\Models\Kegiatan;

class ProfilBerandaController extends Controller
{
    public function index()
    {
        $highlightKegiatan = Kegiatan::orderByDesc('tanggal_mulai')->limit(3)->get();
        $statistik = [
            'kegiatan' => Kegiatan::count(),
            'anggota' => \App\Models\User::count(),
            'foto' => Dokumentasi::count(),
        ];
        $fotoPilihan = Dokumentasi::where('highlight', true)->orderByDesc('created_at')->limit(4)->get();

        return view('profil.beranda', compact('highlightKegiatan', 'statistik', 'fotoPilihan'));
    }
}
