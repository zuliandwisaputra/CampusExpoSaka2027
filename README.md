# Website Campus Expo SMAN 1 Kandanghaur 2027

Pusat informasi Campus Expo SMA Negeri 1 Kandanghaur (Senin, 18 Januari 2027): siswa bisa melihat kampus yang hadir, alumni yang mewakilinya, dan jadwal acara langsung dari HP. Semua konten diubah lewat panel admin, tanpa menyentuh kode.

Tampilan memakai **Opsi A · Lanskap** (dipilih panitia), disusun dengan skill UI UX Pro Max: gaya *Nature Distilled*, font Playfair Display + Inter, warna krem, zaitun, dan rose dari kop surat panitia.

## Fitur versi 1

| Halaman publik (tanpa login) | Panel admin (`/admin`) |
| --- | --- |
| Beranda: foto sekolah, hitung mundur, ringkasan angka, 3 pengumuman terbaru. Setelah acara lewat, hitung mundur diganti foto dokumentasi. | Dashboard dengan penanda data yang belum lengkap |
| Kampus: cari, filter jenis (PTN, PTS, Politeknik, Kedinasan) dan provinsi | Anggota: tambah, ubah, hapus, cari, filter, upload foto, tampil/sembunyi |
| Detail kampus: profil, jurusan, jalur masuk, biaya, beasiswa, link resmi, alumni, tombol bagikan ke WhatsApp | Kampus dan jurusan |
| Anggota: kartu dengan ikon Instagram/LinkedIn (muncul hanya kalau diisi) | Divisi, Jadwal, Pengumuman (draf/terbit, sematkan), Galeri (upload banyak foto), FAQ |
| Divisi, Jadwal, Pengumuman, Galeri, FAQ | Pengaturan acara dan Akun admin (khusus super admin) |

### Hak akses dua role

| Hak akses | Super admin | Koordinator divisi |
| --- | --- | --- |
| Akun admin, pengaturan acara | Kelola | Tidak ada akses |
| Kampus dan jurusan | Tambah, ubah, hapus | Lihat saja |
| Divisi | Tambah, ubah, hapus | Ubah deskripsi divisinya sendiri |
| Anggota | Semua anggota | Hanya anggota divisinya |
| Jadwal, pengumuman, galeri, FAQ | Tambah, ubah, hapus | Tambah dan ubah |

## Teknologi

- Laravel 13 (PHP 8.3+), MySQL/MariaDB di hosting (SQLite boleh untuk lokal)
- Blade + Tailwind CSS 4 via Vite, font di-host sendiri (tidak bergantung Google Fonts saat hari H)
- Foto dikecilkan dan dikompres ke WebP saat upload memakai ekstensi GD bawaan PHP
- Data halaman publik di-cache dan otomatis segar setiap kali admin mengubah data

## Menjalankan di komputer sendiri

Butuh PHP 8.3+, Composer, dan Node.js 20+.

```bash
composer install
```

```bash
npm install
```

```bash
cp .env.example .env
```

```bash
php artisan key:generate
```

Isi `ADMIN_EMAIL` dan `ADMIN_PASSWORD` di `.env` untuk akun super admin pertama. Kalau `ADMIN_PASSWORD` dikosongkan, seeder membuat password acak dan menampilkannya sekali di terminal.

```bash
touch database/database.sqlite
```

```bash
php artisan migrate --seed
```

```bash
php artisan storage:link
```

```bash
npm run build
```

```bash
php artisan serve
```

Buka `http://localhost:8000` untuk situs dan `http://localhost:8000/admin` untuk panel admin.

### Data awal

`php artisan migrate --seed` mengisi:

- **6 divisi, 31 kampus, dan 83 anggota** dari dokumen "Pengumuman Panitia Pelaksana" (`database/data/panitia.php`). Jurusan, angkatan, foto, Instagram, dan LinkedIn belum ada di dokumen itu, jadi masih kosong dan ditandai di dashboard.
- Pengaturan acara, pengumuman susunan panitia, FAQ dasar, dan 2 album galeri berisi foto dummy.
- Akun super admin dari `.env`.

Rundown belum diisi karena jam acara belum diputuskan. Untuk mencoba tampilan halaman Jadwal di komputer lokal saja:

```bash
php artisan db:seed --class=JadwalContohSeeder
```

### Foto dummy

Semua tempat gambar memakai foto dummy di `public/images/dummy/` sampai foto asli diunggah lewat panel admin:

| File | Dipakai di | Ukuran |
| --- | --- | --- |
| `foto-sekolah.jpg` | Beranda paling atas | 1920 × 1080 |
| `foto-anggota.jpg` | Kartu anggota | 600 × 600 |
| `logo-kampus.png` | Kartu dan detail kampus | 400 × 400 |
| `foto-galeri.jpg` | Galeri | 1200 × 900 |
| `pratinjau-link.jpg` | Pratinjau saat link dibagikan ke WhatsApp | 1200 × 630 |

Untuk mengganti `pratinjau-link.jpg` dengan desain asli, timpa file itu dengan gambar 1200 × 630 px.

## Pasang di shared hosting

1. Pastikan hosting menyediakan **PHP 8.3**, MySQL/MariaDB, ekstensi GD, dan SSL. Akses SSH/terminal sangat membantu.
2. Di komputer sendiri jalankan `npm run build` (hosting tidak perlu Node.js), lalu unggah seluruh folder project **kecuali** `node_modules` dan `.env`.
3. Arahkan document root domain ke folder `public/`. Kalau hosting tidak mengizinkan, pindahkan isi `public/` ke `public_html/` dan sesuaikan path di `public_html/index.php`.
4. Buat database MySQL, lalu salin `.env.example` menjadi `.env` dan isi:
   - `APP_ENV=production`, `APP_DEBUG=false`, `APP_URL=https://domain-kamu`
   - `DB_CONNECTION=mysql` dan detail database
   - `ADMIN_EMAIL` dan `ADMIN_PASSWORD`
5. Jalankan lewat SSH:

```bash
composer install --no-dev --optimize-autoloader
```

```bash
php artisan key:generate
```

```bash
php artisan migrate --seed --force
```

```bash
php artisan storage:link
```

```bash
php artisan config:cache && php artisan route:cache && php artisan view:cache
```

6. Hapus `ADMIN_PASSWORD` dari `.env` setelah berhasil login, lalu jalankan ulang `php artisan config:cache`.
7. **Backup database** sebelum hari H dan setelah perubahan data besar (ekspor dari phpMyAdmin atau `mysqldump`).

Setiap kali mengubah file `.env` atau kode di server, jalankan ulang perintah cache pada langkah 5.

## Menjalankan tes

```bash
php artisan test
```

Tes memeriksa semua halaman publik, privasi (nomor HP tidak tampil, anggota tersembunyi tidak muncul), escape isi pengumuman, dua role dan batasannya, batas percobaan login, validasi Instagram/LinkedIn/foto, kompres foto ke WebP, dan thumbnail galeri.

## Masih perlu diputuskan panitia

- Jam mulai dan selesai acara (isi di Pengaturan acara; hitung mundur sementara menuju 07.00 WIB)
- Foto sekolah untuk beranda, logo kampus, dan foto anggota
- Jurusan, angkatan, Instagram, dan LinkedIn tiap anggota (target lengkap H-7, 11 Januari 2027)
- Profil, jurusan, jalur masuk, biaya, dan beasiswa tiap kampus
- Kota dan provinsi "Universitas Muhammadiyah Indonesia" (nama sesuai dokumen panitia)
- Pemegang akun super admin, akun koordinator tiap divisi, serta nama domain dan paket hosting
