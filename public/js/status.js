/**
 * Fungsi untuk membuka modal dengan data dinamis
 * @param {string} desc - Deskripsi kerusakan
 * @param {string} loc - Lokasi fasilitas
 * @param {string} status - Teks status (SELESAI, dll)
 * @param {string} colorClass - Class CSS untuk warna tombol
 * @param {string} imgUrl - Link gambar foto kerusakan
 */
function openModal(desc, loc, status, colorClass, imgUrl) {
    const modal = document.getElementById('modalOverlay');
    const modalImg = document.getElementById('modalImg');
    const modalDesc = document.getElementById('modalDesc');
    const modalLoc = document.getElementById('modalLoc');
    const statusBtn = document.getElementById('modalStatusBtn');

    // Injeksi data ke elemen modal
    modalImg.src = imgUrl;
    modalDesc.innerText = desc + " - Lorem ipsum dolor sit amet, consectetur adipiscing elit.";
    modalLoc.innerText = loc;
    statusBtn.innerText = status;

    // Reset warna tombol dan pasang class baru
    statusBtn.className = 'modal-status-btn ' + colorClass;

    // Tampilkan modal dengan Flexbox
    modal.style.display = 'flex';
}

/**
 * Fungsi untuk menutup modal
 */
function closeModal() {
    document.getElementById('modalOverlay').style.display = 'none';
}

/**
 * Event Listener: Menutup modal jika area di luar kotak modal diklik
 */
window.onclick = function(event) {
    const modal = document.getElementById('modalOverlay');
    if (event.target == modal) {
        closeModal();
    }
};