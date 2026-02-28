@extends('profil.layout')
@section('content')
<div class="max-w-4xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Daftar Kegiatan</h1>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Loop kegiatan di sini --}}
        @foreach($kegiatan as $item)
        <div class="bg-white rounded shadow p-4 flex flex-col justify-between">
            <div>
                <h2 class="font-bold text-lg mb-2">{{ $item->judul }}</h2>
                <div class="text-sm text-gray-500 mb-2">{{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}</div>
                <p class="mb-2">{{ Str::limit($item->deskripsi, 80) }}</p>
            </div>
            <a href="{{ route('profil.kegiatan.detail', $item->id) }}" class="mt-2 text-blue-600 hover:underline">Lihat Detail</a>
        </div>
        @endforeach
    </div>
</div>
@endsection
