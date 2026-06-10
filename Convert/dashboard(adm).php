<?php 
include "Conn.php";
session_start();

$username = $_SESSION['admin'];

$query = "SELECT * FROM admin 
          WHERE nama_admin='$username'";

$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);


?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistem Pelaporan Fasilitas</title>

  <link rel="stylesheet" href="filecss/dashboard.css">
</head>
<body>

  <div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

      <div class="sidebar-header">
        <h2>MENU</h2>
      </div>

      <div class="sidebar-menu">
        <a href="dashboard(adm).php"><button>Dashboard</button></a>
        <a href=""><button>Verifikasi Laporan</button></a>
        <a href=""><button>Semua Laporan</button></a>
        <a href=""><button>Teknisi Tersedia</button></a>
        <a href=""><button>Menu Lanjutan</button></a>
      </div>

      <div class="logout">
        ⤺ LOG OUT
      </div>

    </aside>

    <!-- MAIN -->
    <main class="main-content">

      <!-- HEADER -->
      <div class="header">

        <!-- BUTTON MENU -->
  <div class="menu-btn" onclick="toggleSidebar()">
    ☰
  </div>

        <div class="title">
          <img src="Image Login/logo.png" alt="">
          <h1>SISTEM PELAPORAN FASILITAS</h1>
        </div>

        <!-- BUTTON PROFILE -->
        <div class="profile-icon" onclick="toggleProfile()"></div>

      </div>

      <!-- CONTENT -->
       <h2 class = "welcome">Halo, Admin <?= $row ['nama_admin']; ?></h2>
      <div class="content">

        <!-- CARD -->
        <div class="cards">
          <div class="card"></div>
          <div class="card"></div>
          <div class="card"></div>
        </div>

        <!-- PROFILE POPUP -->
        <div class="profile-popup" id="profilePopup">

          <span class="close" onclick="toggleProfile()">×</span>
          
          <div class="profile-picture"></div>

          <h3><?= $row['nama_admin']; ?></h3>
          <p>Administrator</p>

          <div class="profile-buttons">
            <button>Edit Profile</button>
            <a href="login.php"><button type = "reset">Log Out</button></a>
          </div>

        </div>

      </div>

      <!-- LINE -->
      <div class="line"></div>

      <!-- BUTTON -->
      <div class="buttons">
        <a href="daftarmahasiswa.php"><button>Daftar Mahasiswa</button></a>
        <button>Verifikasi Laporan</button>
        <button>Riwayat Laporan</button>
      </div>

    </main>

  </div>

  <script>
    function toggleSidebar() {
      document.getElementById("sidebar")
      .classList.toggle("active");
    }

    function toggleProfile() {
      document.getElementById("profilePopup").classList.toggle("show");
    }
  </script>

</body>
</html>