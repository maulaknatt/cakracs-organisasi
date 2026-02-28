@extends('profil.layout')
@section('content')
<div class="max-w-3xl mx-auto py-8">
    <a href="{{ route('profil.kegiatan') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Kembali ke Daftar Kegiatan</a>
    <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-bold mb-2">{{ $kegiatan->judul }}</h1>
        <div class="text-sm text-gray-500 mb-2">{{ $kegiatan->tanggal_mulai }} - {{ $kegiatan->tanggal_selesai }}</div>
        <p class="mb-4">{{ $kegiatan->deskripsi }}</p>
        {{-- Dokumentasi publik kegiatan --}}
        <h2 class="font-bold text-lg mt-6 mb-2">Dokumentasi</h2>
        <div class="grid grid-cols-2 gap-4">
            @foreach($kegiatan->dokumentasi as $foto)
                <img src="{{ asset('storage/' . $foto->file) }}" alt="Foto Kegiatan" class="rounded shadow">
            @endforeach
        </div>
    </div>
</div>
@endsection
