# Website Campus Expo SMAN 1 Kandanghaur 2027

Pusat informasi Campus Expo SMA Negeri 1 Kandanghaur (Senin, 18 Januari 2027): siswa bisa melihat kampus yang hadir, alumni yang mewakilinya, dan jadwal acara langsung dari HP. Semua konten diubah lewat panel admin, tanpa menyentuh kode.

Tampilan memakai **Opsi A · Lanskap** (dipilih panitia), disusun dengan skill UI UX Pro Max: gaya *Nature Distilled*, font Playfair Display + Inter, warna krem, zaitun, dan rose dari kop surat panitia.

## Fitur versi 1

Situs publik berbentuk **one page**: semua bagian ada di satu halaman dan menu di atas langsung menggulir ke bagiannya (menu yang sedang dibaca ikut ditandai). Daftar yang panjang (pengumuman, kampus, anggota, divisi, foto galeri) tampil satu baris yang digeser ke samping: di HP tinggal diusap, ada tombol panah kiri/kanan, dan daftar mengalir pelan sendiri (bolak-balik). Geser otomatis berhenti saat pengunjung menyentuh daftar (lanjut lagi 4 detik kemudian), mengarahkan mouse, atau menekan tombol jeda, dan mati kalau HP memakai mode "kurangi gerakan". Kecepatannya diatur lewat atribut `kecepatan` di komponen `<x-geser>` (piksel per detik, bawaan 70). Kampus dan anggota punya tombol "Lihat semua" untuk membuka semuanya sebagai grid. Tombol "kembali ke atas" muncul setelah pengunjung menggulir lebih dari satu layar. Bagian "Ikuti update Campus Expo" menampilkan Instagram dan TikTok panitia (diatur di admin → Pengaturan; yang dikosongkan otomatis disembunyikan), dan bagian lokasi memakai ilustrasi peta yang ringan sebelum peta Google dimuat. Panel admin tetap per halaman.

| Situs publik (tanpa login, satu halaman) | Panel admin (`/admin`) |
| --- | --- |
| Beranda: foto sekolah, hitung mundur, ringkasan angka. Setelah acara lewat, hitung mundur diganti foto dokumentasi. | Dashboard dengan penanda data yang belum lengkap |
| Pengumuman: kartu digeser ke samping, isi lengkap terbuka di pop-up | Anggota: tambah, ubah, hapus, cari, filter, upload foto, tampil/sembunyi |
| Kampus: cari, filter jenis (PTN, PTS, Politeknik; kampus kedinasan seperti Poltek PU, Poltekkes, dan Poltekesos masuk Politeknik) dan provinsi tanpa memuat ulang halaman. Ketuk kartu untuk membuka detail di pop-up: profil, jurusan, jalur masuk, biaya, beasiswa, link resmi, alumni, tombol bagikan ke WhatsApp | Kampus dan jurusan |
| Anggota: cari, filter kampus dan divisi; kartu dengan ikon Instagram/LinkedIn (muncul hanya kalau diisi) | Divisi, Jadwal, Pengumuman (draf/terbit, sematkan), Galeri (upload banyak foto), FAQ |
| Divisi (tombol "Lihat anggota divisi" langsung menyaring daftar anggota), Jadwal, Galeri, FAQ, dan Lokasi SMAN 1 Kandanghaur (peta Google Maps + petunjuk arah) | Pengaturan acara dan Akun admin (khusus super admin) |

Pop-up punya alamat sendiri, jadi bisa dibagikan: `/#kampus/politeknik-negeri-indramayu` langsung membuka detail Polindra, `/#pengumuman/<slug>` membuka pengumumannya. Alamat lama seperti `/kampus`, `/kampus/<slug>`, `/anggota`, atau `/faq` otomatis dialihkan ke bagian yang sama di halaman utama, jadi link yang sudah tersebar tetap jalan.

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
- Animasi masuk dan animasi saat scrolling yang ringan, otomatis mati kalau HP pengunjung memakai mode "kurangi gerakan"
- Ringan di HP kelas bawah: geser otomatis memakai satu `requestAnimationFrame` yang berhenti total kalau tidak ada daftar yang terlihat, header tanpa efek blur, dan `public/.htaccess` mengompres HTML/CSS/JS (halaman ±384 KB jadi ±25 KB) serta menyimpan file build di cache browser

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

Rundown belum diisi karena jam acara belum diputuskan. Untuk mencoba tampilan bagian Jadwal di komputer lokal saja:

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

### Batas upload foto 7 MB

Satu foto boleh sampai 7 MB (galeri maksimal 10 foto sekali unggah). Setelah diunggah, foto otomatis dikecilkan dan dikompres ke WebP, jadi yang tersimpan tetap kecil. Batas bawaan PHP biasanya hanya 2 MB, jadi project ini sudah menyertakan:

- `public/.htaccess` untuk Apache dengan modul PHP (misalnya **Laragon**), langsung berlaku tanpa pengaturan tambahan.
- `public/.user.ini` untuk hosting FastCGI/LiteSpeed (kebanyakan shared hosting). Perubahan bisa butuh sampai 5 menit untuk berlaku.

Kalau upload foto besar masih gagal, ubah langsung `php.ini` (di Laragon: menu **PHP → php.ini**; di cPanel: **Select PHP Version → Options** atau **MultiPHP INI Editor**) menjadi `upload_max_filesize = 8M`, `post_max_size = 80M`, `memory_limit = 512M`, lalu restart.

### Lokasi di Google Maps

Peta di beranda memakai isian **Lokasi di Google Maps** pada menu Pengaturan acara. Supaya titiknya tepat, isi dengan koordinat sekolah (di Google Maps, tekan lama titik SMAN 1 Kandanghaur lalu salin angkanya). Peta baru dimuat saat pengunjung mengetuk "Tampilkan peta interaktif", jadi beranda tetap ringan.

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

Tes memeriksa semua bagian di halaman one page, pengalihan alamat lama, pop-up detail kampus, bagian lokasi, privasi (nomor HP tidak tampil, anggota tersembunyi tidak muncul), escape isi pengumuman, dua role dan batasannya, batas percobaan login, validasi Instagram/LinkedIn/foto (sampai 7 MB), kompres foto ke WebP, dan thumbnail galeri.

## Masih perlu diputuskan panitia

- Jam mulai dan selesai acara (isi di Pengaturan acara; hitung mundur sementara menuju 07.00 WIB)
- Koordinat SMAN 1 Kandanghaur untuk peta (sementara memakai pencarian nama sekolah)
- Foto sekolah untuk beranda, logo kampus, dan foto anggota
- Jurusan, angkatan, Instagram, dan LinkedIn tiap anggota (target lengkap H-7, 11 Januari 2027)
- Profil, jurusan, jalur masuk, biaya, dan beasiswa tiap kampus
- Kota dan provinsi "Universitas Muhammadiyah Indonesia" (nama sesuai dokumen panitia)
- Pemegang akun super admin, akun koordinator tiap divisi, serta nama domain dan paket hosting
