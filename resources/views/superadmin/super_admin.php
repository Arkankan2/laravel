<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Pelaporan Fasilitas</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

    <input type="checkbox" id="toggle-sidebar" class="toggle-checkbox">
    <input type="checkbox" id="toggle-profile" class="toggle-checkbox">

    <header class="navbar">
        <div class="nav-left">
            <label for="toggle-sidebar" class="menu-icon">
                <i class="fa-solid fa-bars"></i>
            </label>
        </div>
        <div class="nav-center">
            <img src="ith.png" alt="Logo ITH" class="logo-img">
            <h1>SISTEM PELAPORAN FASILITAS KAMPUS</h1>
        </div>
        <div class="nav-right">
            <label for="toggle-profile" class="profile-circle"></label>
        </div>
    </header>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>MENU</h2>
            <label for="toggle-sidebar" class="close-sidebar"><i class="fa-solid fa-xmark"></i></label>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="nav-btn active">Dashboard</a>
            <a href="#" class="nav-btn">Manajemen Peran & Akun</a>
            <a href="#" class="nav-btn">Konfigurasi Wilayah ITH</a>
            <a href="#" class="nav-btn">Master Kategori Fasilitas</a>
            <a href="#" class="nav-btn">Log Sistem & Audit</a>
        </nav>
        <div class="sidebar-footer">
            <a href="#" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> LOG OUT</a>
        </div>
    </aside>

    <div class="profile-popup">
        <label for="toggle-profile" class="close-popup">X</label>
        <div class="popup-avatar"></div>
        <h3>Super Admin</h3>
        <p class="nip-text">NIP. .........................</p>
        <p class="desc-title">Deskripsi:</p>
        <div class="desc-line"></div>
        <div class="popup-actions">
            <button class="action-btn"><i class="fa-regular fa-pen-to-square"></i> Edit Profile</button>
            <button class="action-btn"><i class="fa-solid fa-right-from-bracket"></i> Log Out</button>
        </div>
    </div>

    <main class="main-content">
        <section class="card-grid">
            <div class="info-card"></div>
            <div class="info-card"></div>
            <div class="info-card"></div>
            <div class="info-card"></div>
        </section>

        <hr class="separator">

        <section class="action-grid">
            <button class="main-action-btn">Manajemen Pengguna</button>
            <button class="main-action-btn">Konfigurasi Wilayah</button>
            <button class="main-action-btn">Backup & Log Sistem</button>
        </section>
    </main>

</body>
</html>