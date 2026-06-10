<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelaporan Fasilitas</title>
    <link rel="stylesheet" href="status.css">
</head>
<body>

    <div class="container">
        <!-- Top Navigation -->
        <header class="header">
            <button class="back-btn">&#8592;</button>
            <div class="logo-section">
                <img src="https://via.placeholder.com/40x40?text=LOGO" alt="Logo" class="logo-img">
                <h1>SISTEM PELAPORAN FASILITAS</h1>
            </div>
            <div class="profile-circle"></div>
        </header>

        <!-- Page Title -->
        <div class="main-title">
            <h2>MANAJEMEN LAPORAN</h2>
        </div>

        <!-- List of Reports -->
        <div class="report-box">
            
            <!-- Item Selesai -->
            <div class="report-item" onclick="openModal('Kursi Rusak', 'Kampus 2, Ruang Lab 201', 'SELESAI', 'green-bg', 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?auto=format&fit=crop&q=80&w=500')">
                <div class="text-bar">Deskripsi kerusakan kursi patah, Ruang 201...</div>
                <div class="status-indicator green"></div>
            </div>

            <!-- Item Perbaikan Ditunda -->
            <div class="report-item" onclick="openModal('Meja Retak', 'Kampus 2, Ruang Lab 205', 'PERBAIKAN DITUNDA', 'red-bg', 'https://images.unsplash.com/photo-1530018607912-eff2df114f11?auto=format&fit=crop&q=80&w=500')">
                <div class="text-bar">Meja kayu retak parah, Ruang 205...</div>
                <div class="status-indicator red"></div>
            </div>

            <!-- Item Dalam Perbaikan -->
            <div class="report-item" onclick="openModal('Lampu Mati', 'Kampus 1, Koridor B', 'DALAM PERBAIKAN', 'gray-bg', 'https://images.unsplash.com/photo-1550985616-10810253b84d?auto=format&fit=crop&q=80&w=500')">
                <div class="text-bar">Lampu koridor kedap-kedip, Kampus 1...</div>
                <div class="status-indicator gray"></div>
            </div>

        </div>
    </div>

    <!-- MODAL OVERLAY -->
    <div id="modalOverlay" class="modal-overlay">
        <div class="modal-content">
            <header class="modal-header">
                <button class="close-btn" onclick="closeModal()">X</button>
            </header>
            
            <div class="modal-body">
                <div class="image-frame">
                    <img id="modalImg" src="" alt="Foto Kerusakan">
                </div>

                <div id="modalDesc" class="desc-box"></div>

                <div id="modalLoc" class="location-box"></div>

                <div class="status-section">
                    <div class="status-label">STATUS</div>
                    <button id="modalStatusBtn" class="modal-status-btn"></button>
                </div>
            </div>
        </div>
    </div>

    <script src="status.js"></script>
</body>
</html>