<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;

class ProfilKegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::orderByDesc('tanggal_mulai')->get();

        return view('profil.kegiatan', compact('kegiatan'));
    }

    public function show($id)
    {
        $kegiatan = Kegiatan::with('dokumentasi')->findOrFail($id);

        return view('profil.kegiatan-detail', compact('kegiatan'));
    }
}
