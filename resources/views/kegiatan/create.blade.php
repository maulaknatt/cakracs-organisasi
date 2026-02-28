<h1>Tambah Kegiatan</h1>

<form action="{{ route('kegiatan.store') }}" method="POST">
    @csrf

    <label>Judul:</label><br>
    <input type="text" name="judul"><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="deskripsi"></textarea><br><br>

    <label>Tanggal:</label><br>
    <input type="date" name="tanggal"><br><br>

    <button type="submit">Simpan</button>
</form>
