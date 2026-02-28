# Development Lokal

## Error: "No connection could be made because the target machine actively refused it"

Error ini muncul karena **MySQL tidak berjalan** atau aplikasi dikonfigurasi memakai MySQL.

### Opsi A: Pakai MySQL (disarankan jika sudah terpasang)

1. **Nyalakan MySQL**
   - **XAMPP**: Buka XAMPP Control Panel → Start **MySQL**
   - **WAMP**: Pastikan layanan MySQL berjalan
   - **Laragon**: Start All
   - **Windows Services**: Jalankan layanan "MySQL" atau "MySQL80"

2. Pastikan di `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=organisasi_db
   DB_USERNAME=root
   DB_PASSWORD=
   SESSION_DRIVER=database
   ```

3. Buat database (jika belum): `CREATE DATABASE organisasi_db;`

4. Jalankan migrasi: `php artisan migrate`

---

### Opsi B: Pakai SQLite (tanpa MySQL)

Aplikasi bisa jalan tanpa MySQL dengan SQLite + session file.

1. **Ubah file `.env`** (bagian database dan session):

   ```env
   DB_CONNECTION=sqlite
   DB_DATABASE=database/database.sqlite

   SESSION_DRIVER=file
   ```

   (Comment atau hapus baris `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` jika ada.)

2. **Buat file database SQLite** (jika belum ada):

   ```bash
   # Windows PowerShell
   New-Item -Path database\database.sqlite -ItemType File -Force
   ```

   Atau buat manual: file kosong `database/database.sqlite`.

3. **Jalankan migrasi**:

   ```bash
   php artisan migrate --force
   ```

4. **(Opsional)** Isi data awal:

   ```bash
   php artisan db:seed
   ```

Setelah itu jalankan lagi: `php artisan serve` dan buka http://127.0.0.1:8000.
