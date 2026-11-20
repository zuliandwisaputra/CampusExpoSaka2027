@extends('layouts.admin')

@php $baru = ! $anggota->exists; @endphp
@section('judul', $baru ? 'Tambah anggota' : 'Ubah anggota')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah anggota' : 'Ubah '.$anggota->nama">
        <a href="{{ route('admin.anggota.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" enctype="multipart/form-data" action="{{ $baru ? route('admin.anggota.store') : route('admin.anggota.update', $anggota) }}" class="kartu flex max-w-3xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-admin.foto name="foto" label="Foto anggota" :url="$anggota->foto_url" :dummy="\App\Support\Media::isDummy($anggota->foto)" hapus="hapus_foto"
                      help="Foto 1:1 (persegi) paling pas, misalnya 600 × 600 px. JPG, PNG, atau WebP, maksimal {{ \App\Support\ImageUploader::batasMb() }} MB, dikompres otomatis." />

        <x-admin.input name="nama" label="Nama lengkap" :value="$anggota->nama" required maxlength="120" />

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.select name="kampus_id" label="Kampus" :options="$kampus->pluck('nama', 'id')->all()" :value="$anggota->kampus_id" placeholder="Pilih kampus" required />
            <x-admin.select name="divisi_id" label="Divisi" :options="$divisi->pluck('nama', 'id')->all()" :value="$anggota->divisi_id ?? auth()->user()->divisi_id" :placeholder="$divisi->count() > 1 ? 'Pilih divisi' : null" required />
        </div>

        <div class="grid gap-4 sm:grid-cols-[1fr_140px_200px]">
            <x-admin.input name="jurusan" label="Jurusan" :value="$anggota->jurusan" maxlength="120" placeholder="Contoh: Pendidikan Matematika" />
            <x-admin.input name="angkatan" label="Angkatan" :value="$anggota->angkatan" maxlength="10" inputmode="numeric" placeholder="2023" />
            <x-admin.input name="jabatan" label="Jabatan" :value="$anggota->jabatan" required maxlength="80" list="pilihan-jabatan" />
            <datalist id="pilihan-jabatan"><option value="Ketua Divisi"><option value="Anggota"></datalist>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="instagram" label="Instagram" :value="$anggota->instagram" maxlength="30" placeholder="username_saja"
                           help="Isi username saja, tanpa @ dan tanpa link. Kosongkan kalau tidak ada." />
            <x-admin.input name="linkedin" label="LinkedIn" type="url" :value="$anggota->linkedin" placeholder="https://www.linkedin.com/in/nama"
                           help="URL lengkap yang diawali https://www.linkedin.com/. Kosongkan kalau tidak ada." />
        </div>

        <x-admin.input name="no_hp" label="Nomor HP" type="tel" :value="$anggota->no_hp" maxlength="30" placeholder="08xxxxxxxxxx"
                       help="Hanya terlihat oleh admin. Tidak pernah ditampilkan di halaman publik." />

        <x-admin.checkbox name="ditampilkan" label="Tampilkan di halaman publik" :checked="$anggota->ditampilkan"
                          help="Matikan kalau anggota belum menyetujui datanya ditampilkan." />

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.anggota.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
