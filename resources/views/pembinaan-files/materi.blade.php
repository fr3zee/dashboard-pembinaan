@extends('master.layout')
@section('title','Katalog Materi - SRIKANDI')
@section('header','Katalog Materi')
@section('content')
<div class="catalog-page materi-catalog-page">
  <div class="page-header catalog-header">
    <div><h2 class="page-title">Katalog Materi Pembinaan</h2><p class="page-subtitle">Pilih artikel untuk melihat detail pembinaan dan materi.</p></div>
  </div>
  <form class="filter-bar catalog-filter" method="get">
    <input class="input search-input" name="q" placeholder="Cari instansi, layanan, atau materi..." value="{{ request('q') }}">
    <select class="input filter-select" name="visibilitas"><option value="">Semua Visibilitas</option><option value="Publikasi" @selected(request('visibilitas')==='Publikasi')>Publikasi</option><option value="Private" @selected(request('visibilitas')==='Private')>Private</option></select>
    <select class="input filter-select" name="instansi_id"><option value="">Semua Instansi</option>@foreach($instansi as $i)<option value="{{ $i->id }}" @selected(request('instansi_id')==$i->id)>{{ $i->nama }}</option>@endforeach</select>
    <button class="btn btn-secondary" type="submit">Filter</button>
  </form>
  <div class="materi-profile-grid">
    @forelse($items as $item)
      @php
        $materials = $item->files->where('jenis', 'materi');
        $profile = $item->pegawai->first(fn ($pegawai) => filled($pegawai->foto)) ?? $item->pegawai->first();
        $publishedAt = $item->created_at ?: $item->tanggal_mulai;
        $category = $item->layanan ?: 'Materi Pembinaan';
      @endphp
      <article class="materi-profile-card kb-catalog-card materi-profile-card-clickable" data-href="{{ route('pembinaan-files.materi.show', $item) }}" role="link" tabindex="0" aria-label="Lihat materi {{ $item->instansi?->nama ?? 'Instansi' }}">
        <div class="kb-catalog-card__visual">
          <div class="kb-catalog-card__doc-icon">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/><path d="M8 13h8M8 17h5"/></svg>
          </div>
          <span class="kb-catalog-card__count">{{ $materials->count() }} materi</span>
        </div>
        <div class="kb-catalog-card__body">
          <span class="kb-catalog-card__category">{{ $category }}</span>
          <h3>{{ $item->instansi?->nama ?? 'Instansi' }}</h3>
          <p class="kb-catalog-card__document">{{ $materials->first()?->nama_asli ?? 'Materi pembinaan belum tersedia' }}</p>
        </div>
        <div class="kb-catalog-card__footer">
          <div class="kb-catalog-card__author">
            <div class="kb-catalog-card__avatar">@if($profile?->foto)<img src="{{ asset('storage/'.$profile->foto) }}" alt="">@else{{ strtoupper(mb_substr($profile?->nama_lengkap ?? 'T', 0, 1)) }}@endif</div>
            <div><strong>{{ $profile?->nama_lengkap ?? 'Tim Pembinaan' }}</strong><span>{{ $publishedAt?->translatedFormat('d M Y') ?? 'Tanggal belum tersedia' }}</span></div>
          </div>
          <span class="kb-catalog-card__open" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m13 6 6 6-6 6"/></svg></span>
        </div>
      </article>
    @empty
      <div class="empty card-grid-empty">Belum ada profil materi yang sesuai.</div>
    @endforelse
  </div>
  {{ $items->links() }}
</div>
@endsection
@push('scripts')
<script>document.querySelectorAll('.materi-profile-card-clickable').forEach(card=>{const open=()=>window.location.href=card.dataset.href;card.addEventListener('click',event=>{if(event.target.closest('a,button'))return;open()});card.addEventListener('keydown',event=>{if(event.key==='Enter'||event.key===' '){event.preventDefault();open()}})});</script>
@endpush