<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Kampus;
use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Database\Seeders\KontenAwalSeeder;
use Database\Seeders\PanitiaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HalamanPublikTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();
        $this->seed([PanitiaSeeder::class, KontenAwalSeeder::class]);
        Pengaturan::lupakan();
    }

    public function test_semua_halaman_publik_bisa_dibuka_tanpa_login(): void
    {
        $kampus = Kampus::where('singkatan', 'UPI')->first();
        $pengumuman = Pengumuman::first();

        foreach (['/', '/kampus', "/kampus/{$kampus->slug}", '/anggota', '/divisi', '/divisi/acara', '/jadwal',
            '/pengumuman', "/pengumuman/{$pengumuman->slug}", '/galeri', '/galeri/persiapan-panitia', '/faq'] as $url) {
            $this->get($url)->assertOk();
        }
    }

    public function test_data_panitia_dari_pdf_lengkap(): void
    {
        $this->assertSame(83, Anggota::count());
        $this->assertSame(31, Kampus::count());
        $this->assertSame(6, Anggota::where('jabatan', 'Ketua Divisi')->count());

        $this->get('/')->assertSee('83')->assertSee('31')->assertSee('Explore the');
    }

    public function test_anggota_yang_disembunyikan_tidak_tampil(): void
    {
        $anggota = Anggota::where('nama', 'Zulian Dwi Saputra')->first();
        $this->get('/anggota?q=zulian')->assertSee('Zulian Dwi Saputra');

        $anggota->update(['ditampilkan' => false]);

        $this->get('/anggota?q=zulian')->assertDontSee('Zulian Dwi Saputra');
        $this->get('/kampus/'.$anggota->kampus->slug)->assertDontSee('Zulian Dwi Saputra');
        $this->get('/divisi/logistik')->assertDontSee('Zulian Dwi Saputra');
    }

    public function test_nomor_hp_tidak_pernah_tampil_di_halaman_publik(): void
    {
        $anggota = Anggota::where('nama', 'Nayla Putri Amelia')->first();
        $anggota->update(['no_hp' => '081299990000']);

        foreach (['/', '/anggota', '/divisi', '/divisi/acara', '/kampus/'.$anggota->kampus->slug] as $url) {
            $this->get($url)->assertDontSee('081299990000');
        }
    }

    public function test_ikon_media_sosial_hanya_muncul_kalau_diisi_dan_dibuka_di_tab_baru(): void
    {
        $this->get('/anggota?q=nayla')->assertDontSee('instagram.com/nayla', false);

        Anggota::where('nama', 'Nayla Putri Amelia')->first()
            ->update(['instagram' => 'nayla.putri', 'linkedin' => 'https://www.linkedin.com/in/nayla']);

        $this->get('/anggota?q=nayla')
            ->assertSee('href="https://www.instagram.com/nayla.putri/" target="_blank" rel="noopener noreferrer"', false)
            ->assertSee('href="https://www.linkedin.com/in/nayla" target="_blank" rel="noopener noreferrer"', false);
    }

    public function test_filter_dan_pencarian_kampus(): void
    {
        $this->get('/kampus?jenis=politeknik')
            ->assertSee('Politeknik Negeri Indramayu')
            ->assertDontSee('Universitas Padjadjaran');

        $this->get('/kampus?q=soedirman')->assertSee('Universitas Jenderal Soedirman')->assertSee('1 kampus ditemukan');
    }

    public function test_pengumuman_draf_tidak_bisa_dibuka_publik(): void
    {
        $draf = Pengumuman::create(['judul' => 'Rahasia panitia', 'isi' => 'Belum boleh tahu.', 'status' => 'draf']);

        $this->get('/pengumuman')->assertDontSee('Rahasia panitia');
        $this->get('/pengumuman/'.$draf->slug)->assertNotFound();
    }

    public function test_isi_pengumuman_di_escape(): void
    {
        $p = Pengumuman::create(['judul' => 'Uji', 'isi' => '<script>alert(1)</script>', 'status' => 'terbit']);

        $this->get('/pengumuman/'.$p->slug)
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_hitung_mundur_diganti_dokumentasi_setelah_acara_lewat(): void
    {
        $this->get('/')->assertSee('data-countdown', false);

        Pengaturan::simpan('tanggal_acara', now()->subDays(3)->format('Y-m-d'));

        $this->get('/')->assertDontSee('data-countdown', false)->assertSee('Dokumentasi Campus Expo 2027');
    }

    public function test_pratinjau_link_whatsapp_tersedia(): void
    {
        $this->get('/')
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('images/dummy/pratinjau-link.jpg', false);
    }
}
