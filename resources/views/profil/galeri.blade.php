@extends('profil.layout')
@section('content')
<div class="max-w-5xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Galeri Organisasi</h1>
    <form method="GET" class="mb-6 flex gap-4">
        <select name="tahun" class="border rounded px-3 py-2">
            <option value="">Semua Tahun</option>
            {{-- Loop tahun dari dokumentasi --}}
            @foreach($tahunList as $tahun)
                <option value="{{ $tahun }}" @if(request('tahun')==$tahun) selected @endif>{{ $tahun }}</option>
            @endforeach
        </select>
        <select name="kegiatan" class="border rounded px-3 py-2">
            <option value="">Semua Acara</option>
            @foreach($kegiatanList as $kegiatan)
                <option value="{{ $kegiatan->id }}" @if(request('kegiatan')==$kegiatan->id) selected @endif>{{ $kegiatan->judul }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filter</button>
    </form>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        @forelse($galeri as $foto)
            <a href="{{ asset('storage/'.$foto->file) }}" target="_blank">
                <img src="{{ asset('storage/'.$foto->file) }}" class="w-full h-32 object-cover rounded shadow mb-2" alt="Foto">
            </a>
        @empty
            <div class="col-span-4 text-center text-gray-400">Belum ada foto</div>
        @endforelse
    </div>
</div>
@endsection
