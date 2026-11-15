@extends('master.layout')
@section('title', ($item->instansi?->nama ?? 'Pembinaan') . ' — Katalog SRIKANDI')
@section('header', 'Katalog Laporan')
@section('content')
<div class="kb-detail">
  <div class="kb-detail__toolbar">
    <a class="kb-back" href="{{ route('pembinaan-files.laporan') }}"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>Kembali</a>
    @can('update', $item)<a class="btn btn-primary kb-edit" href="{{ route('pembinaan.edit', $item) }}"><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>Edit</a>@endcan
  </div>

  <header class="kb-detail__header">
    <span class="kb-type">{{ $item->layanan ?: 'Pembinaan' }}</span><span class="badge {{ $item->status_dokumen === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $item->status_dokumen }}</span>
    <h1>{{ $item->instansi?->nama ?? 'Pembinaan SRIKANDI' }}</h1>
    <div class="kb-detail__meta">
      <span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>{{ $item->pegawai->pluck('nama_lengkap')->join(', ') ?: 'Tim Pembinaan SRIKANDI' }}</span>
      @if($item->tanggal_mulai)<span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4m-5 4h18"/></svg>{{ $item->tanggal_mulai->translatedFormat('d F Y') }}</span>@endif
      @if($item->lokasi)<span><svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 1 1 16 0Z"/><circle cx="12" cy="10" r="2.5"/></svg>{{ $item->lokasi }}</span>@endif
    </div>
  </header>

  @if($selectedFile)
    <section class="kb-preview">
      <div class="kb-preview__bar"><div><span class="kb-preview__eyebrow">DOKUMEN TERPILIH</span><strong>{{ $selectedFile->nama_asli }}</strong></div><a class="kb-download-main" href="{{ route('pembinaan.files.download', $selectedFile) }}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg>Unduh</a></div>
      @if($selectedFile->mime_type === 'application/pdf')
        <iframe class="kb-preview__frame" src="{{ route('pembinaan.files.preview', $selectedFile) }}" title="Pratinjau {{ $selectedFile->nama_asli }}"></iframe>
      @else
        <div class="kb-preview__empty"><div class="kb-file-icon">{{ strtoupper(pathinfo($selectedFile->nama_asli, PATHINFO_EXTENSION)) }}</div><strong>Pratinjau belum tersedia</strong><p>File ini dapat diakses melalui tombol Unduh.</p></div>
      @endif
    </section>
  @endif

  <section class="kb-attachments">
    <div class="kb-attachments__heading"><div><span class="kb-preview__eyebrow">DOKUMEN</span><h2>Lampiran</h2></div><span class="kb-file-count">{{ $reports->count() + $materials->count() }} file</span></div>
    @forelse($reports->concat($materials) as $file)
      @php($isReport = $file->jenis === 'laporan')
      <article class="kb-file {{ $selectedFile?->id === $file->id ? 'is-selected' : '' }}">
        <a class="kb-file__open" href="{{ route('pembinaan-files.materi.show', [$item, 'file' => $file->id]) }}"><span class="kb-file__icon {{ $isReport ? 'is-report' : 'is-material' }}"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"/><path d="M14 2v6h6"/></svg></span><span class="kb-file__copy"><strong>{{ $file->nama_asli }}</strong><small>{{ number_format($file->ukuran / 1048576, 2) }} MB · {{ strtoupper(pathinfo($file->nama_asli, PATHINFO_EXTENSION)) }}</small></span></a>
        <div class="kb-file__right"><span class="kb-file__tag {{ $isReport ? 'is-report' : 'is-material' }}">{{ $isReport ? 'Laporan' : 'Materi' }}</span>@if(! $isReport)<span class="kb-file__private">{{ $file->visibilitas === 'Private' ? 'Pribadi' : 'Publikasi' }}</span>@endif<a class="kb-file__download" href="{{ route('pembinaan.files.download', $file) }}" aria-label="Unduh {{ $file->nama_asli }}"><svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="m7 10 5 5 5-5"/><path d="M5 21h14"/></svg></a></div>
      </article>
    @empty
      <p class="kb-no-files">Belum ada lampiran pada pembinaan ini.</p>
    @endforelse
  </section>
</div>
@endsection
