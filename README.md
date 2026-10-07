# Aplikasi Web Log Inspeksi Harian Inventaris Laboratorium

Aplikasi Laravel ini menggunakan tabel MySQL dari aplikasi lama pada database `db_inspeksi`. Tabel lama dibaca dan ditulis melalui Eloquent tanpa mengganti nama atau menghapus data.

## Menjalankan aplikasi

1. Jalankan `composer install` dan pastikan MySQL Laragon aktif serta database `db_inspeksi` tersedia.
2. Atur koneksi database pada `.env` sesuai instalasi lokal. Jangan menaruh kredensial database dalam source code. Konfigurasi awal proyek menggunakan `127.0.0.1`, `db_inspeksi`, dan `root`; sesuaikan username atau password bila instalasi Anda berbeda.
3. Jika `APP_KEY` masih kosong, jalankan `php artisan key:generate`. Jangan mengganti key yang sudah dipakai aplikasi.
4. Jalankan `php artisan serve`, lalu buka alamat yang ditampilkan (umumnya `http://127.0.0.1:8000`).
5. Masuk menggunakan username dan kata sandi dari tabel `asisten` di database lama.
6. Izinkan akses kamera untuk pemindaian QR. Browser meminta HTTPS atau `localhost`; jika kamera tidak tersedia, pilih alat dengan mengetik ID QR secara manual.

Scanner kamera dan pembuat label QR memuat library JavaScript dari CDN, sehingga memerlukan koneksi internet. Pemilihan ID manual tetap tersedia bila scanner gagal dimuat.

## Skema data yang dipakai

- `asisten`: `username` (kunci utama), `nama_asisten`, `password`.
- `alat_inventaris`: `id_alat` (kunci utama), `nama_alat`, `lokasi_meja`.
- `log_inspeksi`: `id_log` (kunci utama otomatis), `id_alat`, `username`, `waktu_inspeksi`, `status`, `catatan`.

Aplikasi tidak memerlukan tabel atau migrasi baru. Sesi dan cache disimpan sebagai berkas karena database lama tidak memiliki tabel sesi/cache Laravel. Jangan jalankan migrasi template Laravel terhadap database lama tanpa meninjau dampaknya.

## Pengujian

Jalankan `php artisan test`. Pengujian menggunakan SQLite in-memory terpisah dan tidak mengubah data MySQL lama.
