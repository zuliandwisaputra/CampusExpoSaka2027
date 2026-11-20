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

document.addEventListener('DOMContentLoaded', () => {
    startCountdowns();
    setupMenus();
    setupConfirms();
    setupImagePreviews();
    setupSubmitLocks();
});
