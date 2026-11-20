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

document.addEventListener('DOMContentLoaded', () => {
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
