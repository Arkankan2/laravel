<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Sistem Pelaporan Fasilitas')</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  @stack('styles')
</head>
<body>

  <div class="container">

    <!-- SIDEBAR -->
    <aside class="sidebar" id="sidebar">

      <div class="sidebar-header">
        <h2>MENU</h2>
      </div>

      <div class="sidebar-menu">
        @yield('sidebar-menu')
      </div>

      <div class="logout">
        <form action="{{ route('logout') }}" method="POST" style="display:inline">
          @csrf
          <button type="submit" style="background:none;border:none;cursor:pointer;font-weight:bold;font-size:14px;">⤺ LOG OUT</button>
        </form>
      </div>

    </aside>

    <!-- MAIN -->
    <main class="main-content">

      <!-- HEADER -->
      <div class="header">

        <div class="menu-btn" onclick="toggleSidebar()">☰</div>

        <div class="title">
          <img src="{{ asset('images/logo.png') }}" alt="Logo">
          <h1>SISTEM PELAPORAN FASILITAS</h1>
        </div>

        <div class="profile-icon" onclick="toggleProfile()"></div>

      </div>

      @yield('content')

    </main>

  </div>

  <!-- PROFILE POPUP (global) -->
  <div class="profile-popup" id="profilePopup">
    <span class="close" onclick="toggleProfile()">×</span>
    <div class="profile-picture"></div>
    <h3>@yield('profile-name')</h3>
    <p>@yield('profile-role')</p>
    <div class="profile-buttons">
      @yield('profile-buttons')
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Log Out</button>
      </form>
    </div>
  </div>

  <script>
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('active');
    }
    function toggleProfile() {
      document.getElementById('profilePopup').classList.toggle('show');
    }
  </script>

  @stack('scripts')

</body>
</html>
