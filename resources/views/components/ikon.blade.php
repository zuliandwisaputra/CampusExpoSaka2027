@props(['name', 'class' => 'size-5'])
{{-- Ikon garis 24×24 (gaya Lucide). Dekoratif, jadi disembunyikan dari pembaca layar. --}}
<svg {{ $attributes->merge(['class' => $class]) }} viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    @switch($name)
        @case('menu')<path d="M4 7h16M4 12h16M4 17h16"/>@break
        @case('x')<path d="M18 6 6 18M6 6l12 12"/>@break
        @case('cari')<circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/>@break
        @case('kanan')<path d="m9 18 6-6-6-6"/>@break
        @case('kiri')<path d="m15 18-6-6 6-6"/>@break
        @case('panah')<path d="M5 12h14M12 5l7 7-7 7"/>@break
        @case('atas')<path d="M12 19V5M5 12l7-7 7 7"/>@break
        @case('jeda')<rect x="6" y="5" width="4" height="14" rx="1"/><rect x="14" y="5" width="4" height="14" rx="1"/>@break
        @case('putar')<path d="M7 4.5v15a1 1 0 0 0 1.5.86l12-7.5a1 1 0 0 0 0-1.72l-12-7.5A1 1 0 0 0 7 4.5z"/>@break
        @case('kalender')<rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>@break
        @case('lokasi')<path d="M20 10c0 5-5.5 10.2-7.4 11.8a1 1 0 0 1-1.2 0C9.5 20.2 4 15 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>@break
        @case('jam')<circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/>@break
        @case('pin')<path d="M12 17v5M9 10.8a2 2 0 0 1-1.1 1.8l-1.8.9A2 2 0 0 0 5 15.2V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.8a2 2 0 0 0-1.1-1.8l-1.8-.9a2 2 0 0 1-1.1-1.8V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"/>@break
        @case('tiktok')<path d="M21 7.9v4a10 10 0 0 1-5-1.9V16a6.5 6.5 0 1 1-8-6.3V14a2.5 2.5 0 1 0 4 2V4.5h4.1A6 6 0 0 0 21 7.9z"/>@break
        @case('instagram')<rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.4A4 4 0 1 1 12.6 8 4 4 0 0 1 16 11.4z"/><path d="M17.5 6.5h.01"/>@break
        @case('linkedin')<path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-4 0v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/>@break
        @case('web')<circle cx="12" cy="12" r="10"/><path d="M2 12h20M12 2a15 15 0 0 1 0 20M12 2a15 15 0 0 0 0 20"/>@break
        @case('bagikan')<path d="M7.9 20A9 9 0 1 0 4 16.1L2 22z"/>@break
        @case('email')<rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 6L2 7"/>@break
        @case('orang')<circle cx="9" cy="8" r="4"/><path d="M2 21v-1a6 6 0 0 1 12 0v1M16 4a4 4 0 0 1 0 8M22 21v-1a6 6 0 0 0-4-5.6"/>@break
        @case('kampus')<path d="M22 10 12 5 2 10l10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>@break
        @case('divisi')<rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/>@break
        @case('pengumuman')<path d="m3 11 18-5v12L3 14v-3z"/><path d="M11.6 16.8a3 3 0 1 1-5.8-1.6"/>@break
        @case('galeri')<rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/>@break
        @case('tanya')<circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3M12 17h.01"/>@break
        @case('obrolan')<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>@break
        @case('dasbor')<rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/>@break
        @case('pengaturan')<path d="M12.2 2h-.4a2 2 0 0 0-2 2v.2a2 2 0 0 1-1 1.7l-.4.3a2 2 0 0 1-2 0l-.2-.1a2 2 0 0 0-2.7.7l-.2.4a2 2 0 0 0 .7 2.7l.2.1a2 2 0 0 1 1 1.7v.5a2 2 0 0 1-1 1.7l-.2.1a2 2 0 0 0-.7 2.7l.2.4a2 2 0 0 0 2.7.7l.2-.1a2 2 0 0 1 2 0l.4.3a2 2 0 0 1 1 1.7v.2a2 2 0 0 0 2 2h.4a2 2 0 0 0 2-2v-.2a2 2 0 0 1 1-1.7l.4-.3a2 2 0 0 1 2 0l.2.1a2 2 0 0 0 2.7-.7l.2-.4a2 2 0 0 0-.7-2.7l-.2-.1a2 2 0 0 1-1-1.7v-.5a2 2 0 0 1 1-1.7l.2-.1a2 2 0 0 0 .7-2.7l-.2-.4a2 2 0 0 0-2.7-.7l-.2.1a2 2 0 0 1-2 0l-.4-.3a2 2 0 0 1-1-1.7V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>@break
        @case('kunci')<rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>@break
        @case('keluar')<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/>@break
        @case('ubah')<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/>@break
        @case('hapus')<path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>@break
        @case('tambah')<path d="M12 5v14M5 12h14"/>@break
        @case('sembunyi')<path d="M9.9 4.2A10 10 0 0 1 12 4c7 0 10 8 10 8a13 13 0 0 1-1.7 2.7M6.6 6.6A13 13 0 0 0 2 12s3 8 10 8a9.7 9.7 0 0 0 5.4-1.6M2 2l20 20"/><path d="M14.1 14.1a3 3 0 0 1-4.2-4.2"/>@break
        @case('lihat')<path d="M2 12s3-8 10-8 10 8 10 8-3 8-10 8-10-8-10-8z"/><circle cx="12" cy="12" r="3"/>@break
        @case('centang')<path d="M20 6 9 17l-5-5"/>@break
        @case('peringatan')<path d="m21.7 18-8-14a2 2 0 0 0-3.4 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.7-3z"/><path d="M12 9v4M12 17h.01"/>@break
        @case('luar')<path d="M15 3h6v6M10 14 21 3M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>@break
        @default<circle cx="12" cy="12" r="9"/>
    @endswitch
</svg>
