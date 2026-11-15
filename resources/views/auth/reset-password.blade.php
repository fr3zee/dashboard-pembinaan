<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Atur password baru akun SRIKANDI"><title>Reset Password - SRIKANDI</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page"><main class="login-card auth-card">
  <div class="login-logo">SRIKANDI</div><div class="login-subtitle">Direktorat TIK - Sistem Pembinaan</div>
  <h1 class="login-title">Buat Password Baru</h1><p class="auth-description">Gunakan minimal 8 karakter untuk menjaga keamanan akun.</p>
  @if($errors->any())<div class="notice" role="alert">{{ $errors->first() }}</div>@endif
  <form class="login-form" method="post" action="{{ route('password.store') }}">@csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">
    <div class="form-group"><label class="form-label" for="email">Email</label><input class="input" id="email" name="email" type="email" value="{{ old('email', $request->email) }}" required autocomplete="email"></div>
    <div class="form-group"><label class="form-label" for="password">Password Baru</label><div class="input-eye-wrap"><input class="input" id="password" name="password" type="password" minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}" title="Minimal 8 karakter, berisi huruf besar, huruf kecil, angka, dan karakter khusus" required autocomplete="new-password"><button type="button" class="eye-toggle" onclick="this.previousElementSibling.type=this.previousElementSibling.type==='password'?'text':'password'" aria-label="Tampilkan password">◉</button></div><small>Minimal 8 karakter, huruf besar, huruf kecil, angka, karakter khusus.</small></div>
    <div class="form-group"><label class="form-label" for="password_confirmation">Ulangi Password Baru</label><input class="input" id="password_confirmation" name="password_confirmation" type="password" minlength="8" required autocomplete="new-password"></div>
    <button class="btn btn-primary login-btn" type="submit">Simpan Password Baru</button>
  </form>
</main></body></html>
