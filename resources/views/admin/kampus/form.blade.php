@extends('layouts.admin')

@php $baru = ! $kampus->exists; @endphp
@section('judul', $baru ? 'Tambah kampus' : 'Ubah kampus')

@section('isi')
    <x-admin.judul :judul="$baru ? 'Tambah kampus' : 'Ubah '.$kampus->nama">
        <a href="{{ route('admin.kampus.index') }}" class="tombol-garis"><x-ikon name="kiri" /> Kembali</a>
    </x-admin.judul>

    <form method="post" enctype="multipart/form-data" action="{{ $baru ? route('admin.kampus.store') : route('admin.kampus.update', $kampus) }}" class="kartu flex max-w-3xl flex-col gap-5 p-5" data-lock>
        @csrf
        @unless ($baru) @method('PUT') @endunless

        <x-admin.foto name="logo" label="Logo kampus" :url="$kampus->logo_url" :dummy="\App\Support\Media::isDummy($kampus->logo)" hapus="hapus_logo" fit="contain"
                      help="Logo persegi, misalnya 400 × 400 px, sebaiknya PNG berlatar transparan. Maksimal {{ \App\Support\ImageUploader::batasMb() }} MB, dikompres otomatis." />

        <div class="grid gap-4 sm:grid-cols-[1fr_180px]">
            <x-admin.input name="nama" label="Nama kampus" :value="$kampus->nama" required maxlength="150" />
            <x-admin.input name="singkatan" label="Singkatan" :value="$kampus->singkatan" maxlength="30" placeholder="UPI" />
        </div>

        <div class="grid gap-4 sm:grid-cols-3">
            <x-admin.select name="jenis" label="Jenis" :options="\App\Models\Kampus::JENIS" :value="$kampus->jenis" required />
            <x-admin.input name="kota" label="Kota" :value="$kampus->kota" maxlength="80" />
            <x-admin.input name="provinsi" label="Provinsi" :value="$kampus->provinsi" maxlength="80" />
        </div>

        <x-admin.textarea name="deskripsi" label="Profil singkat" :value="$kampus->deskripsi" rows="4" maxlength="3000"
                          help="Pisahkan paragraf dengan baris kosong." />

        <x-admin.textarea name="jurusan" label="Daftar jurusan" :value="$jurusanTeks" rows="8"
                          help="Satu jurusan per baris. Tulis jenjang di depan dengan tanda |, contoh: S1 | Pendidikan Matematika atau D3 | Teknik Informatika." />

        <x-admin.textarea name="jalur_masuk" label="Jalur masuk" :value="$kampus->jalur_masuk" rows="4" maxlength="3000"
                          help="Baris yang diawali tanda minus dan spasi ditampilkan sebagai daftar. Contoh: - SNBP" />
        <x-admin.textarea name="kisaran_biaya" label="Kisaran biaya kuliah" :value="$kampus->kisaran_biaya" rows="3" maxlength="2000" />
        <x-admin.textarea name="beasiswa" label="Info beasiswa" :value="$kampus->beasiswa" rows="3" maxlength="3000" />

        <div class="grid gap-4 sm:grid-cols-2">
            <x-admin.input name="website" label="Website resmi" type="url" :value="$kampus->website" placeholder="https://www.contoh.ac.id" />
            <x-admin.input name="instagram" label="Instagram resmi" :value="$kampus->instagram" maxlength="30" placeholder="username_saja" help="Username saja, tanpa @ dan tanpa link." />
        </div>

        <div class="flex flex-wrap gap-2 border-t border-garis pt-4">
            <button type="submit" class="tombol-zaitun">Simpan</button>
            <a href="{{ route('admin.kampus.index') }}" class="tombol-garis">Batal</a>
        </div>
    </form>
@endsection
