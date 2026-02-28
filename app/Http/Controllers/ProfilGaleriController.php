<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

class ProfilGaleriController extends Controller
{
    public function index(Request $request)
    {
        $query = Dokumentasi::query();
        if ($request->tahun) {
            $query->whereYear('created_at', $request->tahun);
        }
        if ($request->kegiatan) {
            $query->where('kegiatan_id', $request->kegiatan);
        }
        $galeri = $query->orderByDesc('created_at')->get();
        $tahunList = Dokumentasi::selectRaw('YEAR(created_at) as tahun')->distinct()->pluck('tahun');
        $kegiatanList = Kegiatan::orderBy('judul')->get();

        return view('profil.galeri', compact('galeri', 'tahunList', 'kegiatanList'));
    }
}
