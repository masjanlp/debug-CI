# debug-CI
Debugging CodeIgniter

Tools ini tidak langsung masuk ke CodeIgniter, tapi mengecek beberapa aspek penting seperti:
- Versi PHP & ekstensi
- File konfigurasi CodeIgniter (app/Config/App.php)
- Koneksi database dari app/Config/Database.php
- Izin folder writable/
- Log file terakhir (jika ada)

Cara Pakai
- Simpan file sebagai debug-codeigniter.php
- Letakkan di direktori root CodeIgniter kamu (sejajar dengan folder app, public, dll.)
- Akses via URL: http://domainanda.com/debug-codeigniter.php
- Hapus setelah dipakai agar tidak terbuka untuk publik
