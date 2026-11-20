// Skrip kecil tanpa framework supaya halaman tetap ringan di HP.

// Hitung mundur ke hari H: <div data-countdown="2027-01-18T07:00:00+07:00"> berisi
// elemen [data-unit="hari|jam|menit|detik"]. Saat waktunya lewat, halaman dimuat ulang
// supaya server menampilkan foto dokumentasi sebagai pengganti hitung mundur.
function startCountdowns() {
    document.querySelectorAll('[data-countdown]').forEach((el) => {
        const target = Date.parse(el.dataset.countdown);
        if (Number.isNaN(target)) return;

        const units = {
            hari: el.querySelector('[data-unit="hari"]'),
            jam: el.querySelector('[data-unit="jam"]'),
            menit: el.querySelector('[data-unit="menit"]'),
            detik: el.querySelector('[data-unit="detik"]'),
        };
        const pad = (n) => String(n).padStart(2, '0');

        const tick = () => {
            let s = Math.max(0, Math.floor((target - Date.now()) / 1000));
            const d = Math.floor(s / 86400);
            s %= 86400;
            const h = Math.floor(s / 3600);
            s %= 3600;
            const m = Math.floor(s / 60);
            const sec = s % 60;

            if (units.hari) units.hari.textContent = String(d);
            if (units.jam) units.jam.textContent = pad(h);
            if (units.menit) units.menit.textContent = pad(m);
            if (units.detik) units.detik.textContent = pad(sec);

            if (target - Date.now() <= 0) {
                clearInterval(timer);
                if (el.dataset.reloadWhenDone !== undefined) window.location.reload();
            }
        };

        const timer = setInterval(tick, 1000);
        tick();
    });
}

// Menu navigasi untuk layar HP.
function setupMenus() {
    document.querySelectorAll('[data-menu-toggle]').forEach((button) => {
        const panel = document.getElementById(button.getAttribute('aria-controls'));
        if (!panel) return;

        button.addEventListener('click', () => {
            const open = button.getAttribute('aria-expanded') === 'true';
            button.setAttribute('aria-expanded', String(!open));
            panel.hidden = open;
        });

        panel.querySelectorAll('[data-tutup-menu]').forEach((link) => {
            link.addEventListener('click', () => {
                button.setAttribute('aria-expanded', 'false');
                panel.hidden = true;
            });
        });
    });
}

// Setiap tombol hapus meminta konfirmasi lebih dulu (aturan di PRD).
function setupConfirms() {
    document.querySelectorAll('form[data-confirm]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm(form.dataset.confirm)) event.preventDefault();
        });
    });
}

// Pratinjau foto sebelum diunggah di panel admin.
function setupImagePreviews() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach((input) => {
        const img = document.getElementById(input.dataset.preview);
        if (!img) return;

        input.addEventListener('change', () => {
            const file = input.files && input.files[0];
            if (!file) return;
            img.src = URL.createObjectURL(file);
        });
    });
}

// Tombol simpan dinonaktifkan saat formulir sedang dikirim agar tidak terkirim dua kali.
function setupSubmitLocks() {
    document.querySelectorAll('form[data-lock]').forEach((form) => {
        form.addEventListener('submit', () => {
            form.querySelectorAll('button[type="submit"]').forEach((b) => {
                b.disabled = true;
                b.classList.add('opacity-70');
            });
        });
    });
}

const kurangiGerakan = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

// Angka ringkasan menghitung naik dari 0 saat terlihat (0 → 83).
function hitungNaik(root) {
    const targets = root.matches?.('[data-hitung]') ? [root] : root.querySelectorAll?.('[data-hitung]') ?? [];
    targets.forEach((el) => {
        const akhir = parseInt(el.dataset.hitung, 10);
        if (Number.isNaN(akhir) || kurangiGerakan()) {
            el.textContent = el.dataset.hitung;
            return;
        }
        const mulai = performance.now();
        const durasi = 900;
        const langkah = (t) => {
            const p = Math.min(1, (t - mulai) / durasi);
            el.textContent = String(Math.round(akhir * (1 - Math.pow(1 - p, 3))));
            if (p < 1) requestAnimationFrame(langkah);
        };
        requestAnimationFrame(langkah);
    });
}

// Elemen [data-reveal] muncul perlahan saat masuk layar, berurutan dalam satu baris/kelompok.
function setupReveal() {
    window.ceAnimasi = true;
    const elemen = document.querySelectorAll('[data-reveal]');

    if (kurangiGerakan() || !('IntersectionObserver' in window)) {
        elemen.forEach((el) => el.classList.add('terlihat'));
        return;
    }

    document.querySelectorAll('[data-reveal] [data-hitung], [data-hitung][data-reveal]').forEach((el) => {
        el.textContent = '0';
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries
                .filter((e) => e.isIntersecting)
                .sort((a, b) => a.boundingClientRect.top - b.boundingClientRect.top || a.boundingClientRect.left - b.boundingClientRect.left)
                .forEach((entry, i) => {
                    entry.target.style.setProperty('--tunda', `${Math.min(i, 6) * 80}ms`);
                    entry.target.classList.add('terlihat');
                    hitungNaik(entry.target);
                    observer.unobserve(entry.target);
                });
        },
        { rootMargin: '0px 0px -6% 0px', threshold: 0.12 },
    );

    elemen.forEach((el) => observer.observe(el));
}

// Foto sekolah di beranda bergerak sedikit lebih lambat dari halaman (paralaks halus).
function setupParalaks() {
    const foto = document.querySelector('[data-paralaks]');
    if (!foto || kurangiGerakan()) return;

    let menunggu = false;
    const perbarui = () => {
        const y = window.scrollY;
        if (y < window.innerHeight * 1.2) foto.style.transform = `translate3d(0, ${Math.round(y * 0.3)}px, 0)`;
        menunggu = false;
    };
    window.addEventListener('scroll', () => {
        if (!menunggu) {
            requestAnimationFrame(perbarui);
            menunggu = true;
        }
    }, { passive: true });
}

// Header mendapat bayangan tipis setelah halaman di-scroll.
function setupHeader() {
    const header = document.querySelector('[data-header]');
    if (!header) return;
    const perbarui = () => header.classList.toggle('menggulir', window.scrollY > 8);
    window.addEventListener('scroll', perbarui, { passive: true });
    perbarui();
}

// Peta Google baru dimuat saat tombol diketuk, supaya beranda tetap ringan di hari H.
function setupPeta() {
    document.querySelectorAll('[data-peta-muat]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            const wadah = tombol.closest('[data-peta]');
            const iframe = document.createElement('iframe');
            iframe.src = tombol.dataset.src;
            iframe.title = tombol.dataset.judul || 'Peta lokasi';
            iframe.loading = 'lazy';
            iframe.referrerPolicy = 'no-referrer-when-downgrade';
            iframe.allowFullscreen = true;
            iframe.className = 'absolute inset-0 z-10 size-full border-0';
            wadah.appendChild(iframe);
            tombol.remove();
        });
    });
}

// Daftar yang bisa dicari dan disaring tanpa memuat ulang halaman (kampus, anggota, galeri, pengumuman).
// Struktur: [data-daftar] berisi [data-item], kontrol [data-saring]/[data-saring-chip],
// tombol [data-tampil-lebih], teks [data-jumlah], keadaan kosong [data-kosong], dan [data-reset].
function setupDaftar() {
    document.querySelectorAll('[data-daftar]').forEach((root) => {
        const items = [...root.querySelectorAll('[data-item]')];
        const awal = parseInt(root.dataset.awal || '0', 10) || items.length;
        const tambah = parseInt(root.dataset.tambah || '0', 10);
        const satuan = root.dataset.satuan || '';
        const tombolLebih = root.querySelector('[data-tampil-lebih]');
        const jumlah = root.querySelector('[data-jumlah]');
        const kosong = root.querySelector('[data-kosong]');
        const reset = root.querySelector('[data-reset]');
        const saring = {};
        let batas = awal;

        const cocok = (item) => Object.entries(saring).every(([kunci, nilai]) => {
            if (!nilai) return true;
            if (kunci === 'cari') return (item.dataset.cari || '').includes(nilai);
            return item.dataset[kunci] === nilai;
        });

        const tampilkan = () => {
            const aktif = Object.values(saring).some(Boolean);
            const hasil = items.filter(cocok);
            const terlihat = new Set(aktif ? hasil : hasil.slice(0, batas));
            items.forEach((item) => {
                item.hidden = !terlihat.has(item);
                item.classList.remove('lebih');
            });
            if (jumlah) jumlah.textContent = `${hasil.length} ${satuan}`.trim();
            if (kosong) kosong.hidden = hasil.length > 0;
            if (tombolLebih) tombolLebih.hidden = aktif || batas >= hasil.length;
            if (reset) reset.hidden = !aktif;
        };

        root.querySelectorAll('[data-saring]').forEach((el) => {
            const kunci = el.dataset.saring;
            el.addEventListener(el.tagName === 'SELECT' ? 'change' : 'input', () => {
                saring[kunci] = kunci === 'cari' ? el.value.trim().toLowerCase() : el.value;
                tampilkan();
            });
        });

        const chips = [...root.querySelectorAll('[data-saring-chip]')];
        const aktifkanChip = (kunci, nilai) => chips
            .filter((c) => c.dataset.saringChip === kunci)
            .forEach((c) => {
                const pilih = c.dataset.nilai === nilai;
                c.classList.toggle('chip-aktif', pilih);
                c.setAttribute('aria-pressed', String(pilih));
            });
        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                saring[chip.dataset.saringChip] = chip.dataset.nilai;
                aktifkanChip(chip.dataset.saringChip, chip.dataset.nilai);
                tampilkan();
            });
        });

        tombolLebih?.addEventListener('click', () => {
            batas = tambah > 0 ? batas + tambah : items.length;
            tampilkan();
        });

        reset?.addEventListener('click', () => {
            Object.keys(saring).forEach((k) => delete saring[k]);
            root.querySelectorAll('[data-saring]').forEach((el) => { el.value = ''; });
            [...new Set(chips.map((c) => c.dataset.saringChip))].forEach((k) => aktifkanChip(k, ''));
            tampilkan();
        });

        // Dipakai tombol "Lihat anggota divisi" untuk menyaring dari bagian lain.
        root.saringDari = (kunci, nilai) => {
            saring[kunci] = String(nilai);
            const el = root.querySelector(`[data-saring="${kunci}"]`);
            if (el) el.value = String(nilai);
            tampilkan();
        };

        tampilkan();
    });

    document.querySelectorAll('[data-pilih-divisi]').forEach((tombol) => {
        tombol.addEventListener('click', () => {
            document.querySelector('[data-daftar="anggota"]')?.saringDari('divisi', tombol.dataset.pilihDivisi);
        });
    });
}

// Pop-up detail kampus dan pengumuman. Alamat #kampus/{slug} atau #pengumuman/{slug} langsung membukanya.
function setupDialog() {
    const buka = (dialog, perbaruiAlamat = true) => {
        if (!dialog || dialog.open) return;
        document.querySelectorAll('dialog[open]').forEach((d) => d.close());
        dialog.showModal();
        if (perbaruiAlamat && dialog.dataset.hash) history.replaceState(null, '', `#${dialog.dataset.hash}`);
    };

    document.addEventListener('click', (event) => {
        const pemicu = event.target.closest('[data-buka-dialog]');
        if (pemicu) {
            const dialog = document.getElementById(pemicu.dataset.bukaDialog);
            if (dialog) {
                event.preventDefault();
                buka(dialog);
            }
            return;
        }
        if (event.target.closest('[data-tutup-dialog]')) event.target.closest('dialog')?.close();
    });

    document.querySelectorAll('dialog.dialog').forEach((dialog) => {
        // Ketuk area gelap di luar pop-up untuk menutup.
        dialog.addEventListener('click', (event) => {
            if (event.target === dialog) dialog.close();
        });
        dialog.addEventListener('close', () => {
            if (decodeURIComponent(location.hash) === `#${dialog.dataset.hash}`) {
                history.replaceState(null, '', `#${dialog.dataset.hash.split('/')[0]}`);
            }
        });
    });

    const dariAlamat = () => {
        const hash = decodeURIComponent(location.hash.slice(1));
        if (!hash.includes('/')) return;
        const dialog = [...document.querySelectorAll('dialog[data-hash]')].find((d) => d.dataset.hash === hash);
        if (!dialog) return;
        document.getElementById(hash.split('/')[0])?.scrollIntoView();
        buka(dialog, false);
    };
    dariAlamat();
    window.addEventListener('hashchange', dariAlamat);
}

// Menu header menandai bagian yang sedang dibaca saat halaman di-scroll.
function setupScrollspy() {
    const tautan = [...document.querySelectorAll('[data-nav]')];
    const bagian = [...new Set(tautan.map((t) => t.dataset.nav))].map((id) => document.getElementById(id)).filter(Boolean);
    if (!bagian.length) return;

    // Bagian aktif = bagian terakhir yang bagian atasnya sudah melewati 35% layar.
    // Di dasar halaman, bagian terakhir (lokasi) yang aktif walau tingginya pendek.
    let aktif = null;
    let antre = false;
    const perbarui = () => {
        antre = false;
        const batas = window.innerHeight * 0.35;
        let id = bagian[0].id;
        bagian.forEach((b) => {
            if (b.getBoundingClientRect().top <= batas) id = b.id;
        });
        if (window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 4) id = bagian[bagian.length - 1].id;
        if (id === aktif) return;
        aktif = id;
        tautan.forEach((t) => t.setAttribute('aria-current', String(t.dataset.nav === id)));
    };
    const jadwalkan = () => {
        if (antre) return;
        antre = true;
        requestAnimationFrame(perbarui);
    };
    window.addEventListener('scroll', jadwalkan, { passive: true });
    window.addEventListener('resize', jadwalkan);
    perbarui();
}

document.addEventListener('DOMContentLoaded', () => {
    setupDaftar();
    setupDialog();
    setupScrollspy();
    setupReveal();
    setupParalaks();
    setupHeader();
    setupPeta();
    startCountdowns();
    setupMenus();
    setupConfirms();
    setupImagePreviews();
    setupSubmitLocks();
});
