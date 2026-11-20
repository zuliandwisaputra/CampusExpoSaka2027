@extends('layouts.admin')

@section('judul', 'Pengaturan acara')

@section('isi')
    <x-admin.judul judul="Pengaturan acara" pengantar="Dipakai untuk beranda, hitung mundur, footer, dan pratinjau link." />

    <form method="post" enctype="multipart/form-data" action="{{ route('admin.pengaturan.update') }}" class="kartu flex max-w-3xl flex-col gap-5 p-5" data-lock>
        @csrf
        @method('PUT')

        <x-admin.foto name="foto_beranda" label="Foto sekolah untuk beranda" :url="\App\Models\Pengaturan::fotoBerandaUrl()" :dummy="\App\Support\Media::isDummy($p['foto_beranda'] ?? null)"
                      hapus="hapus_foto_beranda" rasio="aspect-video" lebar="w-full sm:w-72"
                      help="Rasio 16:9, misalnya 1920 × 1080 px, maksimal {{ \App\Support\ImageUploader::batasMb() }} MB. Di HP sisi kiri dan kanan terpotong, jadi letakkan objek utama di tengah." />

        <x-admin.input name="nama_acara" label="Nama acara" :value="$p['nama_acara']" required maxlength="150" />
        <x-admin.input name="tagline" label="Tagline" :value="$p['tagline']" maxlength="150" />
        <x-admin.textarea name="deskripsi" label="Deskripsi singkat" :value="$p['deskripsi']" rows="3" maxlength="600"
                          help="Tampil di beranda dan sebagai deskripsi saat link dibagikan." />

        <div class="grid gap-4 sm:grid-cols-3">
            <x-admin.input name="tanggal_acara" label="Tanggal acara" type="date" :value="$p['tanggal_acara']" required />
            <x-admin.input name="jam_mulai" label="Jam mulai" type="time" :value="$p['jam_mulai']" help="Kosong = hitung mundur ke 07.00." />
            <x-admin.input name="jam_selesai" label="Jam selesai" type="time" :value="$p['jam_selesai']" />
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="lokasi" label="Lokasi" :value="$p['lokasi']" required maxlength="150" />
            <x-admin.input name="penyelenggara" label="Penyelenggara" :value="$p['penyelenggara']" maxlength="150" />
        </div>
        <x-admin.input name="alamat" label="Alamat lengkap" :value="$p['alamat']" maxlength="255" />
        <div class="flex flex-col gap-2">
            <x-admin.input name="peta_lokasi" label="Lokasi di Google Maps" :value="$p['peta_lokasi']" required maxlength="200"
                           help="Isi nama tempat seperti yang muncul di Google Maps, atau koordinat supaya lebih tepat (format: -6.123456, 108.123456). Di Google Maps, tekan lama titik sekolah untuk menyalin koordinatnya." />
            <a href="{{ \App\Models\Pengaturan::petaUrl() }}" target="_blank" rel="noopener noreferrer" class="inline-flex min-h-10 items-center gap-1.5 self-start text-sm font-semibold text-zaitun hover:underline"><x-ikon name="luar" class="size-4" /> Cek lokasi tersimpan di Google Maps</a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="instagram" label="Instagram panitia" :value="$p['instagram']" maxlength="30" help="Username saja, tanpa @." />
            <x-admin.input name="email" label="Email panitia" type="email" :value="$p['email']" maxlength="150" />
        </div>
        <p class="bantuan -mt-2">Sesuai PRD, nomor HP panitia tidak ditampilkan di halaman publik.</p>

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan pengaturan</button>
        </div>
    </form>
@endsection
