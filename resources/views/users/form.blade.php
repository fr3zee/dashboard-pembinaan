@extends('master.layout')
@section('title',isset($user)?'Edit Akun - SRIKANDI':'Tambah Akun - SRIKANDI')
@section('header','Manajemen Akun')
@section('content')

<div class="breadcrumb">
  <a href="{{ route('users.index') }}">
    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
    Manajemen Akun
  </a>
  &nbsp;/&nbsp; {{ isset($user) ? 'Edit Akun' : 'Tambah Akun' }}
</div>

<div class="page-header" style="margin-bottom:0">
  <div>
    <h2 class="page-title">{{ isset($user) ? 'Edit Akun' : 'Tambah Akun' }}</h2>
    <p class="page-subtitle">{{ isset($user) ? 'Perbarui data dan hak akses akun ini.' : 'Buat akun internal baru untuk sistem SRIKANDI.' }}</p>
  </div>
</div>

<div class="account-form-card">

  {{-- Avatar header --}}
  <div class="acf-hero">
    <div class="acf-avatar" id="acf-avatar-preview">
      {{ strtoupper(mb_substr($user->name ?? 'A', 0, 1)) }}
    </div>
    <div>
      <div class="acf-hero-name">{{ $user->name ?? 'Akun Baru' }}</div>
      <div class="acf-hero-sub">{{ isset($user) ? $user->email : 'Isi form di bawah untuk membuat akun.' }}</div>
    </div>
  </div>

  <form method="post" action="{{ isset($user) ? route('users.update',$user) : route('users.store') }}">
    @csrf
    @isset($user) @method('PUT') @endisset

    <div class="acf-body">

      <div class="acf-section">
        <div class="acf-section-title">Informasi Akun</div>
        <div class="acf-grid">
          <div class="acf-field">
            <label for="name">Nama Lengkap <span class="req">*</span></label>
            <input id="name" name="name" type="text" value="{{ old('name',$user->name??'') }}" required placeholder="Masukkan nama lengkap" class="acf-input">
          </div>
          <div class="acf-field">
            <label for="email">Alamat Email <span class="req">*</span></label>
            <input id="email" name="email" type="email" value="{{ old('email',$user->email??'') }}" required placeholder="nama@domain.com" class="acf-input">
          </div>
          <div class="acf-field acf-field-full">
            <label for="role">Hak Akses (Role) <span class="req">*</span></label>
            <div class="role-selector">
              @foreach((isset($user) ? ['admin'=>['Admin','Kelola seluruh sistem','#1d4ed8','#dbeafe'],'pelaksana'=>['User','Input & kelola data pembinaan','#15803d','#dcfce7'],'monitoring'=>['Monitoring','Pantau data & laporan','#a16207','#fef9c3']] : ['pelaksana'=>['User','Input & kelola data pembinaan','#15803d','#dcfce7'],'monitoring'=>['Monitoring','Pantau data & laporan','#a16207','#fef9c3']]) as $key=>[$label,$desc,$color,$bg])
              <label class="role-option {{ old('role',$user->role??'pelaksana')===$key ? 'selected' : '' }}" for="role_{{ $key }}" style="--rc:{{ $color }};--rb:{{ $bg }}">
                <input type="radio" id="role_{{ $key }}" name="role" value="{{ $key }}" {{ old('role',$user->role??'pelaksana')===$key ? 'checked' : '' }}>
                <span class="role-opt-dot"></span>
                <div>
                  <strong>{{ $label }}</strong>
                  <small>{{ $desc }}</small>
                </div>
              </label>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      <div class="acf-section">
        <div class="acf-section-title">
          Keamanan
          @isset($user)<span class="acf-optional">kosongkan jika tidak diubah</span>@endisset
        </div>
        <div class="acf-grid acf-security-grid">
          <div class="acf-field">
            <label for="password">{{ isset($user) ? 'Password Baru' : 'Password' }} @if(!isset($user))<span class="req">*</span>@endif</label>
            <div class="input-eye-wrap">
              <input id="password" name="password" type="password" {{ isset($user)?'':'required' }} minlength="8" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d]).{8,}" title="Minimal 8 karakter, berisi huruf besar, huruf kecil, angka, dan karakter khusus" placeholder="Minimal 8 karakter" class="acf-input" autocomplete="new-password">
              <button type="button" class="eye-toggle" tabindex="-1" onclick="togglePwd('password',this)" aria-label="Tampilkan password">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
            <small id="password-strength" class="acf-optional">Minimal 8 karakter + huruf besar, huruf kecil, angka, karakter khusus.</small>
          </div>
          <div class="acf-field">
            <label for="password_confirmation">Konfirmasi Password @if(!isset($user))<span class="req">*</span>@endif</label>
            <div class="input-eye-wrap">
              <input id="password_confirmation" name="password_confirmation" type="password" {{ isset($user)?'':'required' }} minlength="8" placeholder="Ulangi password" class="acf-input" autocomplete="new-password">
              <button type="button" class="eye-toggle" tabindex="-1" onclick="togglePwd('password_confirmation',this)" aria-label="Tampilkan konfirmasi">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

    </div>

    <div class="acf-footer">
      <a class="btn btn-secondary" href="{{ route('users.index') }}">Batal</a>
      <button class="btn btn-primary" type="submit">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Simpan Akun
      </button>
    </div>
  </form>
</div>

@push('scripts')
<script>
function togglePwd(id, btn) {
  const inp = document.getElementById(id);
  const show = inp.type === 'password';
  inp.type = show ? 'text' : 'password';
  btn.querySelector('svg').style.opacity = show ? '0.5' : '1';
}
// Live avatar initial from name input
const nameInp = document.getElementById('name');
const avatarEl = document.getElementById('acf-avatar-preview');
if(nameInp && avatarEl) {
  nameInp.addEventListener('input', () => {
    avatarEl.textContent = (nameInp.value.trim()[0] || 'A').toUpperCase();
  });
}
// Role option visual feedback
document.querySelectorAll('.role-option input').forEach(radio => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('.role-option').forEach(o => o.classList.remove('selected'));
    radio.closest('.role-option').classList.add('selected');
  });
});
</script>
@endpush
@endsection