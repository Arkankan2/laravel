<?php 
include "Conn.php";
session_start();

$username = $_SESSION['mahasiswa'];

$query = "SELECT * FROM mahasiswa 
          WHERE Nama_mahasiswa='$username'";

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
        <button>Dashboard</button>
        <button>Buat Laporan</button>
        <button>Status Laporan</button>
        <button>Riwayat Tersedia</button>
        <button>Notifikasi</button>
      </div>

      <div class="logout">
        <a href="login.php">⤺ LOG OUT</a>
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
       <h2 class = "welcome">Selamat Datang, <?= $row ['Nama_mahasiswa']; ?></h2>
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

          <h3><?= $row['Nama_mahasiswa']; ?></h3>
          <p><?= $row['Nim'];?></p>

          <div class="profile-buttons">
            <a href="biodata.php"><button>Edit Profile</button></a>
            <a href="login.php"><button type = "reset">Log Out</button></a>
          </div>

        </div>

      </div>

      <!-- LINE -->
      <div class="line"></div>

      <!-- BUTTON -->
      <div class="buttons">
        <a href=""><button>Buat Laporan</button></a>
        <a href=""><button>Pantau Laporan</button></a>
        <a href=""><button>Riwayat Laporan</button></a>
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