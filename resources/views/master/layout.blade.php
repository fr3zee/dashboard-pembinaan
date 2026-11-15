<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Sistem Pembinaan SRIKANDI Direktorat TIK">
  <title>@yield('title', 'SRIKANDI')</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @stack('head')
</head>
<body class="{{ request()->routeIs('pembinaan.*') ? 'pembinaan-page' : '' }}">
  <div class="app-layout">
    <aside class="sidebar">
      <div class="sidebar-logo">SRIKANDI</div>
      <div class="sidebar-subtitle">Direktorat TIK</div>
      <nav aria-label="Navigasi utama"><ul class="sidebar-menu">
  <li class="sidebar-section">Navigasi</li>
  <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 13h6V4H4v9Zm10 7h6V4h-6v16ZM4 20h6v-3H4v3Z"/></svg></span><span>Dashboard</span></a></li>
  <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('pembinaan.*') ? 'active' : '' }}" href="{{ route('pembinaan.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M5 4h14v16H5z"/><path d="M8 8h8M8 12h8M8 16h5"/></svg></span><span>Data Pembinaan</span></a></li>
  <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('pembinaan-files.laporan') ? 'active' : '' }}" href="{{ route('pembinaan-files.laporan') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M6 3h9l3 3v15H6z"/><path d="M14 3v4h4M9 12h6M9 16h6"/></svg></span><span>Katalog Laporan &amp; Materi</span></a></li>
  <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('rekap.index') ? 'active' : '' }}" href="{{ route('rekap.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 19V5M4 19h16"/><path d="m7 15 3-4 3 2 5-7"/></svg></span><span>Rekap / Report</span></a></li>
  <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('export-laporan.*') ? 'active' : '' }}" href="{{ route('export-laporan.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg></span><span>Export Laporan</span></a></li>
  @if(auth()->user()?->role === 'admin')
    <li class="sidebar-section">Data Master</li>
    <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('users.*') ? 'active' : '' }}" href="{{ route('users.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"/></svg></span><span>Manajemen Akun</span></a></li>
    <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('kategori-instansi.*') ? 'active' : '' }}" href="{{ route('kategori-instansi.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h6"/></svg></span><span>Kategori Instansi</span></a></li>
    <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('instansi.*') ? 'active' : '' }}" href="{{ route('instansi.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 21V5h16v16M8 9h2M14 9h2M8 13h2M14 13h2M8 17h8"/></svg></span><span>Instansi</span></a></li>
    <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('pegawai.*') ? 'active' : '' }}" href="{{ route('pegawai.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><circle cx="12" cy="8" r="3"/><path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"/></svg></span><span>Pegawai</span></a></li>
     <li class="sidebar-menu-item"><a class="sidebar-menu-link {{ request()->routeIs('change-requests.*') ? 'active' : '' }}" href="{{ route('change-requests.index') }}"><span aria-hidden="true"><svg class="nav-icon" viewBox="0 0 24 24"><path d="M4 5h16v14H4z"/><path d="M8 9h8M8 13h5"/></svg></span><span>Permintaan Perubahan</span></a></li>
   @endif
</ul></nav>
    </aside>
    <section class="main-content">
      <header class="topbar"><h1 class="topbar-title">@yield('header', 'Dashboard')</h1><div class="topbar-right"><div class="user-menu"><button class="user-menu-trigger profile-trigger" type="button" aria-expanded="false" aria-controls="user-menu-dropdown"><span class="profile-avatar" aria-hidden="true">@if(auth()->user()->avatar)<img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Foto profil {{ auth()->user()->name }}">@else{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}@endif</span><span class="profile-copy"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->role === 'pelaksana' ? 'User' : ucfirst(auth()->user()->role) }}</small></span><svg class="chevron-icon" viewBox="0 0 24 24" aria-hidden="true"><polyline points="6 9 12 15 18 9"/></svg></button><div id="user-menu-dropdown" class="user-menu-dropdown profile-dropdown"><div class="profile-dropdown-head"><span class="profile-avatar">@if(auth()->user()->avatar)<img src="{{ asset('storage/'.auth()->user()->avatar) }}" alt="Foto profil {{ auth()->user()->name }}">@else{{ strtoupper(mb_substr(auth()->user()->name,0,1)) }}@endif</span><div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></div></div><a class="user-menu-item profile-item" href="{{ route('profile.edit') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="8" r="3"/><path d="M5 21c.7-4 3-6 7-6s6.3 2 7 6"/></svg>Profil Saya</a><form method="post" action="{{ route('logout') }}">@csrf<button class="user-menu-item logout-item" type="submit"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 17l5-5-5-5M15 12H3M21 19V5a2 2 0 0 0-2-2h-6"/></svg>Keluar</button></form></div></div></div></header>
      <main class="content-area">
        @if(session('success'))<div class="notice" role="status">{{ session('success') }}</div>@endif
        @if($errors->any())<div class="notice" role="alert">@foreach($errors->all() as $error)<div class="error">{{ $error }}</div>@endforeach</div>@endif
        @yield('content')
      </main>
    </section>
  </div>
<script>document.addEventListener('DOMContentLoaded',()=>{const menu=document.querySelector('.user-menu'),trigger=document.querySelector('.user-menu-trigger');trigger?.addEventListener('click',e=>{e.stopPropagation();const open=menu.classList.toggle('open');trigger.setAttribute('aria-expanded',open)});document.addEventListener('click',()=>{menu?.classList.remove('open');trigger?.setAttribute('aria-expanded','false')})});</script>
@stack('scripts')
</body>
</html>


