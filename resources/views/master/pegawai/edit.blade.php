@extends('master.layout')
@section('title', (isset($item) ? 'Edit' : 'Tambah').' Pegawai ? SRIKANDI')
@section('header','Data Master / Pegawai')
@section('content')
<div class="pegawai-page">
  <div class="breadcrumb"><a href="{{ route('pegawai.index') }}">&larr; Kembali ke Data Pegawai</a></div>
  <div class="pegawai-page-heading"><div><span class="pegawai-eyebrow">DATA MASTER</span><h2>{{ isset($item) ? 'Edit Profil Pegawai' : 'Tambah Pegawai' }}</h2><p>Kelola identitas pegawai yang dapat menjadi narasumber atau tim pembinaan.</p></div></div>
  <div class="pegawai-form-card">
    <div class="pegawai-form-card__intro"><div class="pegawai-avatar-large">@if($item?->foto)<img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama_lengkap }}">@else{{ strtoupper(mb_substr($item->nama_lengkap ?? 'P',0,1)) }}@endif</div><div><h3>Informasi Profil</h3><p>Data ini digunakan pada kegiatan pembinaan SRIKANDI.</p></div></div>
    <form method="post" action="{{ isset($item) ? route('pegawai.update',$item) : route('pegawai.store') }}" enctype="multipart/form-data">@csrf @isset($item) @method('PUT') @endisset
      <div class="pegawai-form-grid">
        <div class="pegawai-field"><label for="nama_lengkap">Nama Lengkap <b>*</b></label><input id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap',$item->nama_lengkap ?? '') }}" placeholder="Masukkan nama lengkap" required>@error('nama_lengkap')<small class="error">{{ $message }}</small>@enderror</div>
        <div class="pegawai-field"><label for="nip">NIP <span>Opsional</span></label><input id="nip" name="nip" value="{{ old('nip',$item->nip ?? '') }}" placeholder="Masukkan NIP">@error('nip')<small class="error">{{ $message }}</small>@enderror</div>
        <div class="pegawai-field"><label for="email">Email <span>Opsional</span></label><input id="email" name="email" type="email" value="{{ old('email',$item->email ?? '') }}" placeholder="nama@instansi.go.id">@error('email')<small class="error">{{ $message }}</small>@enderror</div>
        <div class="pegawai-field"><label for="nomor_telepon">Nomor Telepon <span>Opsional</span></label><input id="nomor_telepon" name="nomor_telepon" type="tel" value="{{ old('nomor_telepon',$item->nomor_telepon ?? '') }}" placeholder="08xxxxxxxxxx">@error('nomor_telepon')<small class="error">{{ $message }}</small>@enderror</div>
        <div class="pegawai-field pegawai-field--full"><label for="foto">Foto Profil <span>Opsional ? Maks. 2 MB</span></label><input id="foto" type="file" name="foto" accept="image/*">@error('foto')<small class="error">{{ $message }}</small>@enderror</div>
      </div>
      <label class="pegawai-active-toggle"><input type="checkbox" name="status_aktif" value="1" @checked(old('status_aktif',$item->status_aktif ?? true))><span><strong>Pegawai aktif</strong><small>Izinkan pegawai dipilih pada form pembinaan.</small></span></label>
      <div class="pegawai-form-actions"><a class="btn btn-secondary" href="{{ route('pegawai.index') }}">Batal</a><button class="btn btn-primary" type="submit">{{ isset($item) ? 'Simpan Perubahan' : 'Simpan Pegawai' }}</button></div>
    </form>
  </div>
</div>
@endsection
