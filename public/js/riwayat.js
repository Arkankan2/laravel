/**
 * riwayat.js – Logika interaksi halaman Riwayat Laporan (Laravel)
 * Sistem Pelaporan Fasilitas Kampus
 */

/* ================================================================
   MODAL — Buka & Tutup
   ================================================================ */

/**
 * Buka modal dengan data dinamis dari baris laporan
 * @param {string} imgUrl    URL foto kerusakan
 * @param {string} desc      Deskripsi kerusakan
 * @param {string} lokasi    Lokasi fasilitas
 * @param {string} tanggal   Waktu laporan
 * @param {string} status    Label status teks (SELESAI, dll)
 * @param {string} statusKey Key status: selesai | perbaikan | ditunda
 */
function openModal(imgUrl, desc, lokasi, tanggal, status, statusKey) {
    var overlay    = document.getElementById('modalOverlay');
    var img        = document.getElementById('rwModalImg');
    var descEl     = document.getElementById('rwModalDesc');
    var locEl      = document.getElementById('rwModalLoc');
    var timeEl     = document.getElementById('rwModalTime');
    var statusBtn  = document.getElementById('rwModalStatusBtn');

    // Isi konten
    img.src             = imgUrl;
    img.alt             = 'Foto – ' + desc;
    descEl.textContent  = desc;
    locEl.textContent   = lokasi;
    timeEl.textContent  = tanggal;
    statusBtn.textContent = status;

    // Warna tombol status
    var colorMap = {
        'selesai'   : 'rw-btn-selesai',
        'perbaikan' : 'rw-btn-perbaikan',
        'ditunda'   : 'rw-btn-ditunda'
    };
    statusBtn.className = 'rw-status-btn';
    if (colorMap[statusKey]) {
        statusBtn.classList.add(colorMap[statusKey]);
    }

    // Tampilkan overlay
    overlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

/**
 * Tutup modal
 */
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
    document.body.style.overflow = '';
}

/**
 * Tutup modal jika klik di area luar kartu
 */
function handleOverlayClick(event) {
    if (event.target === document.getElementById('modalOverlay')) {
        closeModal();
    }
}

// Tutup dengan tombol Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeModal();
});

/* ================================================================
   FILTER STATUS
   ================================================================ */

/**
 * Filter baris laporan berdasarkan status
 * @param {string}      key - 'semua' | 'selesai' | 'perbaikan' | 'ditunda'
 * @param {HTMLElement} btn - Tombol filter yang diklik
 */
function filterStatus(key, btn) {
    // Update tombol aktif
    document.querySelectorAll('.filter-btn').forEach(function (b) {
        b.classList.remove('active-filter');
    });
    btn.classList.add('active-filter');

    // Tampil / sembunyikan baris
    var items   = document.querySelectorAll('.report-item');
    var visible = 0;

    items.forEach(function (item) {
        var match = (key === 'semua' || item.getAttribute('data-status') === key);
        item.style.display = match ? 'flex' : 'none';
        if (match) visible++;
    });

    // Empty state
    var emptyEl = document.getElementById('emptyState');
    if (emptyEl) {
        emptyEl.style.display = visible === 0 ? 'block' : 'none';
    }
}

/* ================================================================
   TUTUP PROFILE / SIDEBAR SAAT KLIK LUAR
   ================================================================ */
document.addEventListener('click', function (e) {
    var popup   = document.getElementById('profilePopup');
    var icon    = document.querySelector('.profile-icon');
    var sidebar = document.getElementById('sidebar');
    var menuBtn = document.querySelector('.menu-btn');

    if (popup && icon) {
        if (!popup.contains(e.target) && !icon.contains(e.target)) {
            popup.classList.remove('show');
        }
    }

    if (sidebar && menuBtn) {
        if (sidebar.classList.contains('active') &&
            !sidebar.contains(e.target) &&
            !menuBtn.contains(e.target)) {
            sidebar.classList.remove('active');
        }
    }
});
