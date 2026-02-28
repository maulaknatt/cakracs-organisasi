@extends('layouts.app')

@section('content')

<h1>Edit Kegiatan</h1>

<form action="{{ route('kegiatan.update', $kegiatan->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Judul:</label><br>
    <input type="text" name="judul" value="{{ $kegiatan->judul }}"><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="deskripsi">{{ $kegiatan->deskripsi }}</textarea><br><br>

    <label>Tanggal:</label><br>
    <input type="date" name="tanggal" value="{{ $kegiatan->tanggal }}"><br><br>

    <button type="submit">Update</button>
</form>

@endsection
