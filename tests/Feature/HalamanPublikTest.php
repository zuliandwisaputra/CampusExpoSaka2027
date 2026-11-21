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

    public function test_situs_one_page_memuat_semua_bagian(): void
    {
        $response = $this->get('/')->assertOk();

        foreach (['beranda', 'pengumuman', 'kampus', 'anggota', 'divisi', 'jadwal', 'galeri', 'faq', 'lokasi'] as $id) {
            $response->assertSee('id="'.$id.'"', false)->assertSee('href="#'.$id.'"', false);
        }
    }

    public function test_alamat_lama_dialihkan_ke_bagiannya(): void
    {
        $kampus = Kampus::where('singkatan', 'UPI')->first();
        $pengumuman = Pengumuman::first();

        foreach (['/kampus' => 'kampus', '/anggota' => 'anggota', '/divisi' => 'divisi', '/divisi/acara' => 'divisi',
            '/jadwal' => 'jadwal', '/pengumuman' => 'pengumuman', '/galeri' => 'galeri', '/faq' => 'faq'] as $lama => $bagian) {
            $this->get($lama)->assertRedirect(url('/').'#'.$bagian);
        }

        $this->get('/kampus/'.$kampus->slug)->assertRedirect(url('/').'#kampus/'.$kampus->slug);
        $this->get('/pengumuman/'.$pengumuman->slug)->assertRedirect(url('/').'#pengumuman/'.$pengumuman->slug);
    }

    public function test_data_panitia_dari_pdf_lengkap(): void
    {
        $this->assertSame(83, Anggota::count());
        $this->assertSame(31, Kampus::count());
        $this->assertSame(6, Anggota::where('jabatan', 'Ketua Divisi')->count());

        $this->get('/')
            ->assertSee('data-hitung="83"', false)
            ->assertSee('data-hitung="31"', false)
            ->assertSee('Zulian Dwi Saputra')
            ->assertSee('Explore the');
    }

    public function test_detail_kampus_ada_di_pop_up(): void
    {
        $kampus = Kampus::where('singkatan', 'Polindra')->first();

        $this->get('/')
            ->assertSee('data-hash="kampus/'.$kampus->slug.'"', false)
            ->assertSee('data-buka-dialog="dialog-kampus-'.$kampus->id.'"', false)
            ->assertSee('Alumni yang bisa kamu tanyai (9)');
    }

    public function test_daftar_bisa_disaring_tanpa_memuat_ulang(): void
    {
        $this->get('/')
            ->assertSee('data-saring="cari"', false)
            ->assertSee('data-saring-chip="jenis"', false)
            ->assertSee('data-jenis="politeknik"', false)
            ->assertSee('data-saring="divisi"', false);
    }

    public function test_daftar_panjang_digeser_ke_samping_dan_ada_tombol_ke_atas(): void
    {
        $response = $this->get('/');

        foreach (['geser-pengumuman', 'geser-kampus', 'geser-anggota', 'geser-divisi'] as $id) {
            $response->assertSee('id="'.$id.'"', false)->assertSee('aria-controls="'.$id.'"', false);
        }

        $response->assertSee('aria-label="Geser ke kanan"', false)
            ->assertSee('aria-label="Jeda geser otomatis"', false)
            ->assertSee('Lihat semua kampus')
            ->assertSee('Lihat semua anggota')
            ->assertSee('data-ke-atas', false)
            ->assertSee('aria-label="Kembali ke atas"', false);
    }

    public function test_kampus_kedinasan_masuk_kategori_politeknik(): void
    {
        $this->assertArrayNotHasKey('kedinasan', Kampus::JENIS);
        $this->assertSame(0, Kampus::where('jenis', 'kedinasan')->count());
        $this->assertSame('politeknik', Kampus::where('singkatan', 'Poltekesos')->value('jenis'));

        $this->get('/')
            ->assertDontSee('data-nilai="kedinasan"', false)
            ->assertSee('data-nilai="politeknik"', false);
    }

    public function test_anggota_yang_disembunyikan_tidak_tampil(): void
    {
        // Bukan nama pembuat website, karena nama itu selalu tampil di footer.
        $this->get('/')->assertSee('Aliyah Syifa Azzahra');

        Anggota::where('nama', 'Aliyah Syifa Azzahra')->first()->update(['ditampilkan' => false]);

        $this->get('/')->assertDontSee('Aliyah Syifa Azzahra');
    }

    public function test_nomor_hp_tidak_pernah_tampil_di_halaman_publik(): void
    {
        Anggota::where('nama', 'Nayla Putri Amelia')->first()->update(['no_hp' => '081299990000']);

        $this->get('/')->assertDontSee('081299990000');
    }

    public function test_ikon_media_sosial_hanya_muncul_kalau_diisi_dan_dibuka_di_tab_baru(): void
    {
        $this->get('/')->assertDontSee('instagram.com/nayla', false);

        Anggota::where('nama', 'Nayla Putri Amelia')->first()
            ->update(['instagram' => 'nayla.putri', 'linkedin' => 'https://www.linkedin.com/in/nayla']);

        $this->get('/')
            ->assertSee('href="https://www.instagram.com/nayla.putri/" target="_blank" rel="noopener noreferrer"', false)
            ->assertSee('href="https://www.linkedin.com/in/nayla" target="_blank" rel="noopener noreferrer"', false);
    }

    public function test_pengumuman_draf_tidak_tampil(): void
    {
        $draf = Pengumuman::create(['judul' => 'Rahasia panitia', 'isi' => 'Belum boleh tahu.', 'status' => 'draf']);

        $this->get('/')->assertDontSee('Rahasia panitia');
        $this->get('/pengumuman/'.$draf->slug)->assertNotFound();
    }

    public function test_isi_pengumuman_di_escape(): void
    {
        Pengumuman::create(['judul' => 'Uji', 'isi' => '<script>alert(1)</script>', 'status' => 'terbit']);

        $this->get('/')
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;', false);
    }

    public function test_hitung_mundur_diganti_dokumentasi_setelah_acara_lewat(): void
    {
        $this->get('/')->assertSee('data-countdown', false);

        Pengaturan::simpan('tanggal_acara', now()->subDays(3)->format('Y-m-d'));

        $this->get('/')->assertDontSee('data-countdown', false)->assertSee('Dokumentasi Campus Expo 2027');
    }

    public function test_beranda_menampilkan_lokasi_dengan_peta_yang_dimuat_saat_diketuk(): void
    {
        $html = $this->get('/')
            ->assertSee('Jl. Pantura Karanganyar')
            ->assertSee('https://www.google.com/maps/dir/?api=1&amp;destination=', false)
            ->assertSee('data-peta-muat', false)
            ->getContent();

        // Iframe Google Maps hanya ada sebagai cadangan <noscript>, tidak dimuat langsung.
        $tanpaNoscript = preg_replace('#<noscript>.*?</noscript>#s', '', $html);
        $this->assertStringNotContainsString('<iframe', $tanpaNoscript);
    }

    public function test_sosmed_instagram_dan_tiktok_panitia_tampil(): void
    {
        $this->get('/')
            ->assertSee('Ikuti update Campus Expo')
            ->assertSee('href="https://www.instagram.com/campusexposaka_/" target="_blank" rel="noopener noreferrer"', false)
            ->assertSee('href="https://www.tiktok.com/@campusexposaka_" target="_blank" rel="noopener noreferrer"', false);
    }

    public function test_nama_pembuat_website_tampil_di_footer(): void
    {
        $this->get('/')
            ->assertSee('Dibuat oleh <span class="font-semibold text-krem">Zulian Dwi Saputra</span>', false)
            ->assertSee('<meta name="author" content="Zulian Dwi Saputra">', false);
    }

    public function test_pratinjau_link_whatsapp_tersedia(): void
    {
        $this->get('/')
            ->assertSee('property="og:title"', false)
            ->assertSee('property="og:image"', false)
            ->assertSee('images/dummy/pratinjau-link.jpg', false);
    }
}
