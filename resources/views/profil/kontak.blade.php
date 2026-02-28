@extends('profil.layout')
@section('content')
<div class="max-w-2xl mx-auto py-8">
    <h1 class="text-2xl font-bold mb-6">Kontak Organisasi</h1>
    <section class="mb-8">
        <h2 class="font-bold text-lg mb-2">Info Organisasi</h2>
        <p>Alamat, email, telepon, and info lain di sini...</p>
    </section>
    <section>
        <h2 class="font-bold text-lg mb-2">Form Gabung</h2>
        <form action="#" method="POST" class="space-y-4">
            <input type="text" name="nama" placeholder="Nama" class="w-full border rounded px-3 py-2">
            <input type="email" name="email" placeholder="Email" class="w-full border rounded px-3 py-2">
            <input type="text" name="kontak" placeholder="No. HP" class="w-full border rounded px-3 py-2">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Gabung</button>
        </form>
    </section>
</div>
@endsection
