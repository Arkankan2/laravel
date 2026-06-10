<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password — Sistem Pelaporan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <style>
    .step-indicator {
      display: flex;
      justify-content: center;
      gap: 8px;
      margin-bottom: 22px;
    }
    .step {
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #ddd;
    }
    .step.active { background: #3b82f6; }
    .password-wrapper {
      position: relative;
    }
    .password-wrapper input {
      padding-right: 48px !important;
    }
    .toggle-eye {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      cursor: pointer;
      font-size: 18px;
      color: #777;
      padding: 0;
      line-height: 1;
    }
    .strength-bar {
      height: 4px;
      border-radius: 4px;
      margin-top: 6px;
      transition: all 0.3s ease;
      background: #ddd;
    }
    .strength-bar.weak   { background: #dc3545; width: 33%; }
    .strength-bar.medium { background: #ffc107; width: 66%; }
    .strength-bar.strong { background: #198754; width: 100%; }
    .strength-text {
      font-size: 11px;
      margin-top: 3px;
      color: #777;
    }
    .alert-box {
      padding: 10px 15px;
      border-radius: 8px;
      margin-bottom: 14px;
      font-size: 13px;
    }
    .alert-error   { background:#f8d7da; color:#721c24; }
    .alert-success { background:#d4edda; color:#155724; }
    .back-link {
      display: flex;
      align-items: center;
      gap: 6px;
      font-size: 12px;
      color: #3fa9f5;
      text-decoration: none;
      margin-bottom: 20px;
    }
    .back-link:hover { color: #2563eb; }
  </style>
</head>
<body>

  <div class="container">

    <!-- LEFT SIDE -->
    <div class="left"></div>

    <!-- RIGHT SIDE -->
    <div class="right">

      <img class="logo" src="{{ asset('images/logo.png') }}" alt="logo">

      <div class="login-box">

        <a href="{{ route('login') }}" class="back-link">← Kembali ke Login</a>

        <div class="title">
          <h1>Reset<br>Password</h1>
        </div>

        <div class="step-indicator">
          <div class="step active"></div>
          <div class="step active"></div>
          <div class="step"></div>
        </div>

        {{-- Error --}}
        @if ($errors->any())
          <div class="alert-box alert-error">
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('reset.password.post') }}" method="POST" id="resetForm">
          @csrf

          {{-- Username --}}
          <div class="input-group">
            <input type="text"
                   name="username"
                   id="username"
                   placeholder="Username / NIM"
                   value="{{ old('username') }}"
                   required
                   autocomplete="off">
          </div>

          {{-- Password Baru --}}
          <div class="input-group">
            <div class="password-wrapper">
              <input type="password"
                     name="new_password"
                     id="new_password"
                     placeholder="Password Baru"
                     required
                     oninput="checkStrength(this.value)">
              <button type="button" class="toggle-eye" onclick="togglePass('new_password', this)">👁</button>
            </div>
            <div class="strength-bar" id="strengthBar"></div>
            <div class="strength-text" id="strengthText"></div>
          </div>

          {{-- Konfirmasi Password --}}
          <div class="input-group">
            <div class="password-wrapper">
              <input type="password"
                     name="new_password_confirmation"
                     id="confirm_password"
                     placeholder="Konfirmasi Password Baru"
                     required
                     oninput="checkMatch()">
              <button type="button" class="toggle-eye" onclick="togglePass('confirm_password', this)">👁</button>
            </div>
            <div class="strength-text" id="matchText"></div>
          </div>

          <button type="submit" class="btn" id="submitBtn">RESET PASSWORD</button>

        </form>

        <div class="forgot" style="margin-top:16px;">
          <small style="color:#888;">Ingat password Anda?</small>
          <a href="{{ route('login') }}"> Login di sini</a>
        </div>

      </div>
    </div>
  </div>

  <script>
    function togglePass(fieldId, btn) {
      const input = document.getElementById(fieldId);
      if (input.type === 'password') {
        input.type = 'text';
        btn.textContent = '🙈';
      } else {
        input.type = 'password';
        btn.textContent = '👁';
      }
    }

    function checkStrength(val) {
      const bar  = document.getElementById('strengthBar');
      const text = document.getElementById('strengthText');
      if (!val) { bar.className = 'strength-bar'; text.textContent = ''; return; }

      const hasUpper   = /[A-Z]/.test(val);
      const hasNumber  = /[0-9]/.test(val);
      const hasSpecial = /[^A-Za-z0-9]/.test(val);
      const score = (val.length >= 8 ? 1 : 0) + (hasUpper ? 1 : 0) + (hasNumber ? 1 : 0) + (hasSpecial ? 1 : 0);

      if (score <= 1) {
        bar.className = 'strength-bar weak';
        text.textContent = 'Lemah — tambahkan angka & huruf besar';
        text.style.color = '#dc3545';
      } else if (score <= 2) {
        bar.className = 'strength-bar medium';
        text.textContent = 'Sedang — bisa lebih kuat lagi!';
        text.style.color = '#e07b00';
      } else {
        bar.className = 'strength-bar strong';
        text.textContent = 'Kuat ✓';
        text.style.color = '#198754';
      }
    }

    function checkMatch() {
      const p1   = document.getElementById('new_password').value;
      const p2   = document.getElementById('confirm_password').value;
      const text = document.getElementById('matchText');
      const btn  = document.getElementById('submitBtn');
      if (!p2) { text.textContent = ''; return; }

      if (p1 === p2) {
        text.textContent = 'Password cocok ✓';
        text.style.color = '#198754';
        btn.disabled = false;
        btn.style.opacity = '1';
      } else {
        text.textContent = 'Password tidak cocok ✗';
        text.style.color = '#dc3545';
        btn.disabled = true;
        btn.style.opacity = '0.6';
      }
    }
  </script>

</body>
</html>
