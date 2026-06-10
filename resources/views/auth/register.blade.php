<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register Admin — Sistem Pelaporan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<div class="container">

  <!-- LEFT -->
  <div class="left"></div>

  <!-- RIGHT -->
  <div class="right">

    <img class="logo" src="{{ asset('images/logo.png') }}" alt="logo">

    <div class="login-box">

      <div class="title">
        <h1>Buat Akun<br>Admin</h1>
      </div>

      @if ($errors->any())
        <div style="background:#f8d7da;color:#721c24;padding:10px 15px;border-radius:8px;margin-bottom:14px;font-size:13px;">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('register.post') }}" method="POST">
        @csrf

        <div class="input-group">
          <input type="text"
                 name="nama_admin"
                 placeholder="Nama Admin"
                 value="{{ old('nama_admin') }}"
                 required>
        </div>

        <div class="input-group">
          <input type="number"
                 name="nip"
                 placeholder="NIP"
                 value="{{ old('nip') }}"
                 required>
        </div>

        <div class="input-group">
          <input type="number"
                 name="kontak"
                 placeholder="Nomor Kontak"
                 value="{{ old('kontak') }}"
                 required>
        </div>

        <div class="input-group">
          <input type="password"
                 name="sandi"
                 placeholder="Password"
                 required>
        </div>

        <button type="submit" class="btn">REGISTER</button>

      </form>

      <div class="forgot">
        <a href="{{ route('login') }}">Sudah punya akun? Login</a>
      </div>

    </div>
  </div>
</div>

</body>
</html>
