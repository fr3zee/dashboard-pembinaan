<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Login ke sistem dashboard pembinaan SRIKANDI Direktorat TIK">
  <title>Login - SRIKANDI Dashboard</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="login-page">
  <main class="login-card">
    <div class="login-logo">SRIKANDI</div>
    <div class="login-subtitle">Direktorat TIK — Sistem Pembinaan</div>
    <h1 class="login-title">Masuk ke Dashboard</h1>
    @if(session('status'))
      <div class="notice notice-success" role="status">{{ session('status') }}</div>
    @endif
    @if($errors->any())
      <div class="notice" role="alert">{{ $errors->first() }}</div>
    @endif
    <form class="login-form" method="post" action="{{ route('login.store') }}">
      @csrf
      <div class="form-group"><label class="form-label" for="email">Email / Username</label><input class="input" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@instansi.go.id" required autofocus autocomplete="username"></div>
      <div class="form-group"><label class="form-label" for="password">Password</label><input class="input" id="password" name="password" type="password" placeholder="Masukkan password" required autocomplete="current-password"></div>
      <button class="btn btn-primary login-btn" type="submit">Masuk</button>
    </form>
    <a class="auth-forgot-link" href="{{ route('password.request') }}">Lupa password?</a>
    <div class="login-footer">Gunakan akun yang telah diberikan administrator.</div>
  </main>
</body>
</html>
