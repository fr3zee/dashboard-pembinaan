@extends('master.layout')
@section('title','Katalog Laporan & Materi - SRIKANDI')
@section('header','Katalog Laporan & Materi')
@section('content')
<div class="catalog-page report-catalog-page">
  <div class="page-header catalog-header"><div><h2 class="page-title">Katalog Laporan &amp; Materi Pembinaan</h2><p class="page-subtitle">Lihat laporan dan materi kegiatan sesuai hak akses.</p></div></div>
  <form class="filter-bar catalog-filter" method="get">
    <input class="input search-input" name="q" placeholder="Cari instansi, layanan, lokasi, laporan, atau materi..." value="{{ request('q') }}">
    <select class="input filter-select" name="visibilitas"><option value="">Semua Visibilitas</option><option value="Publikasi" @selected(request('visibilitas')==='Publikasi')>Publikasi</option>@if(in_array(auth()->user()->role, ['admin', 'pelaksana'], true))<option value="Private" @selected(request('visibilitas')==='Private')>Pribadi</option>@endif</select>
    <select class="input filter-select" name="instansi_id"><option value="">Semua Instansi</option>@foreach($instansi as $i)<option value="{{ $i->id }}" @selected(request('instansi_id')==$i->id)>{{ $i->nama }}</option>@endforeach</select><select class="input filter-select" name="layanan"><option value="">Semua Layanan</option>@foreach(['Sosialisasi Srikandi','Integrasi','Pendampingan Teknis','Troubleshooting','Audit/Evaluasi','Bimbingan Teknis'] as $service)<option value="{{ $service }}" @selected(request('layanan')===$service)>{{ $service }}</option>@endforeach</select><select class="input filter-select" name="status_dokumen"><option value="">Semua Status</option>@foreach(['Belum Menggunakan','Tahap Uji Coba','Live'] as $status)<option value="{{ $status }}" @selected(request('status_dokumen')===$status)>{{ $status }}</option>@endforeach</select>
    <button class="btn btn-secondary" type="submit">Filter</button>
  </form>
  <div class="catalog-card-grid">
    @forelse($items as $item)
      @php
        $reports = $item->files->where('jenis', 'laporan');
        $materials = $item->files->where('jenis', 'materi');
        $profile = $item->pegawai->first(fn ($pegawai) => filled($pegawai->foto)) ?? $item->pegawai->first();
      @endphp
      <article class="catalog-activity-card" data-href="{{ route('pembinaan-files.materi.show', $item) }}" role="link" tabindex="0" aria-label="Lihat katalog {{ $item->instansi?->nama ?? 'instansi' }}">
        <div class="catalog-card-topline">
          <span class="catalog-card-kicker">{{ $item->layanan ?: 'Pembinaan' }}</span>
          <span class="catalog-card-arrow" aria-hidden="true">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg>
          </span>
        </div>
        <h3>{{ $item->instansi?->nama ?? 'Instansi' }}</h3>
        <p class="catalog-card-summary">
          @if($item->tanggal_mulai) <span class="catalog-date">{{ $item->tanggal_mulai->translatedFormat('d M Y') }}</span> @endif
          @if($item->lokasi) <span class="catalog-separator">-</span> {{ $item->lokasi }} @else <span class="catalog-separator">-</span> Kegiatan pembinaan SRIKANDI @endif
        </p>
        <div class="catalog-card-status"><span class="badge {{ $item->status_dokumen === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $item->status_dokumen }}</span></div>
        @if(auth()->user()->role === 'monitoring' && $item->private_material_exists)
          <p class="catalog-private-notice">Materi bersifat privat, silakan hubungi narasumber bersangkutan.</p>
        @endif
        <div class="catalog-card-docs">
          <span class="catalog-doc {{ $reports->isNotEmpty() ? 'is-ready' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            {{ $reports->count() }} Laporan
          </span>
          <span class="catalog-doc {{ $materials->isNotEmpty() ? 'is-ready' : '' }}">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
            {{ $materials->count() }} Materi @if($materials->isNotEmpty()) &middot; {{ $materials->contains(fn ($file) => $file->visibilitas === 'Publikasi') ? 'Publikasi' : 'Pribadi' }}@endif
          </span>
        </div>
        <div class="catalog-card-footer catalog-profile-footer">
          <div class="catalog-profile">
            <div class="catalog-profile-avatar">@if($profile?->foto)<img src="{{ asset('storage/'.$profile->foto) }}" alt="Foto {{ $profile->nama_lengkap }}">@else{{ strtoupper(mb_substr($profile?->nama_lengkap ?? 'T', 0, 1)) }}@endif</div>
            <div><strong>{{ $profile?->nama_lengkap ?? 'Tim Pembinaan' }}</strong><span>Pelaksana pembinaan</span></div>
          </div>
          <a class="catalog-view-btn" href="{{ route('pembinaan-files.materi.show', $item) }}" onclick="event.stopPropagation()">Lihat detail</a>
        </div>
      </article>
    @empty
      <div class="empty card-grid-empty">Belum ada laporan atau materi yang sesuai.</div>
    @endforelse
  </div>
  {{ $items->links() }}
</div>
@endsection
@push('scripts')
<script>document.querySelectorAll('.catalog-activity-card').forEach(card=>{const open=()=>window.location.href=card.dataset.href;card.addEventListener('click',event=>{if(event.target.closest('a,button'))return;open()});card.addEventListener('keydown',event=>{if(event.key==='Enter'||event.key===' '){event.preventDefault();open()}})});</script>
@endpush


