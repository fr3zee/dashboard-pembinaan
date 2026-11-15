<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Reset password akun SRIKANDI"><title>Lupa Password - SRIKANDI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page"><main class="login-card auth-card">
  <div class="login-logo">SRIKANDI</div><div class="login-subtitle">Direktorat TIK - Sistem Pembinaan</div>
  <h1 class="login-title">Lupa Password?</h1><p class="auth-description">Masukkan email akun. Link pengaturan password akan dikirim ke email tersebut.</p>
  @if(session('status'))<div class="notice notice-success" role="status">{{ session('status') }}</div>@endif
  @if($errors->any())<div class="notice" role="alert">{{ $errors->first() }}</div>@endif
  <form class="login-form" method="post" action="{{ route('password.email') }}">@csrf
    <div class="form-group"><label class="form-label" for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autofocus autocomplete="email"></div>
    <button class="btn btn-primary login-btn" type="submit">Kirim Link Reset</button>
  </form><a class="auth-back-link" href="{{ route('login') }}">&larr; Kembali ke halaman login</a>
</main></body></html>
