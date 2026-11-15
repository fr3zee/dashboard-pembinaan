@extends('master.layout')
@section('title','Rekap Pembinaan - SRIKANDI')
@section('header','Rekap / Report')
@section('content')
<div class="rekap-page">
  <header class="rekap-page__header">
    <div>
      <span class="rekap-eyebrow">ANALISIS PEMBINAAN</span>
      <h2>Rekap Pembinaan</h2>
      <p>Ringkasan kegiatan, instansi, dan narasumber berdasarkan periode terpilih.</p>
    </div>
    <a class="btn btn-primary" href="{{ route('export-laporan.index', request()->query()) }}">Export Laporan</a>
  </header>

  <section class="rekap-stat-grid" aria-label="Ringkasan rekap">
    <article class="rekap-stat-card rekap-stat-card--blue">
      <div class="rekap-stat-icon" aria-hidden="true">&#128202;</div>
      <div><span class="rekap-stat-card__label">Total Kegiatan</span><strong class="rekap-stat-card__value">{{ $total }}</strong><small class="rekap-stat-card__hint">Kegiatan terfilter</small></div>
    </article>
    <article class="rekap-stat-card rekap-stat-card--violet">
      <div class="rekap-stat-icon" aria-hidden="true">&#127970;</div>
      <div><span class="rekap-stat-card__label">Total Instansi</span><strong class="rekap-stat-card__value">{{ $instansi }}</strong><small class="rekap-stat-card__hint">Instansi unik</small></div>
    </article>
    <article class="rekap-stat-card rekap-stat-card--orange">
      <div class="rekap-stat-icon" aria-hidden="true">&#128101;</div>
      <div><span class="rekap-stat-card__label">Total Narasumber</span><strong class="rekap-stat-card__value">{{ $narasumber }}</strong><small class="rekap-stat-card__hint">Narasumber terlibat</small></div>
    </article>
  </section>

  <form class="rekap-filter-card" method="get">
    <div class="rekap-filter-card__heading"><div><span class="rekap-eyebrow">FILTER DATA</span><h3>Pilih periode laporan</h3></div><span class="rekap-active-period">Periode aktif: <strong>{{ $year ?: 'Semua Tahun' }}</strong> &middot; <strong>{{ $quarter ? 'Triwulan '.$quarter : 'Semua Triwulan' }}</strong></span></div>
    <div class="rekap-filter-grid">
      <label class="rekap-filter-field"><span>Tahun</span><select class="input" name="tahun"><option value="">Semua Tahun</option>@foreach($years as $availableYear)<option value="{{ $availableYear }}" @selected((string)$year === (string)$availableYear)>{{ $availableYear }}</option>@endforeach</select></label>
      <label class="rekap-filter-field"><span>Triwulan</span><select class="input" name="triwulan"><option value="">Semua Triwulan</option>@for($i=1;$i<=4;$i++)<option value="{{ $i }}" @selected((string)$quarter === (string)$i)>Triwulan {{ $i }} (TW{{ $i }})</option>@endfor</select></label>
      <button class="btn btn-secondary rekap-filter-submit" type="submit">Terapkan Filter</button>
    </div>
  </form>

  <div class="report-charts"><section class="report-chart"><h3 class="report-chart-title">Pembinaan per Periode</h3><div class="period-bars">@foreach($periodCounts as $period => $count)<div class="period-bar-wrap"><strong>{{ $count }}</strong><div class="period-bar" style="height:{{ max(4, $count * 28) }}px" title="{{ $count }} kegiatan"></div><span>TW{{ $period }}</span></div>@endforeach</div></section><section class="report-chart"><h3 class="report-chart-title">Distribusi Layanan</h3><div class="service-list">@foreach($serviceCounts as $service)@php($serviceClass = 'service-' . \Illuminate\Support\Str::slug($service->layanan))<div class="service-row {{ $serviceClass }}"><span><i class="service-dot" aria-hidden="true"></i>{{ $service->layanan }}</span><strong>{{ $service->jumlah }} kegiatan</strong><div class="service-track"><div class="service-fill" style="width:{{ $total ? round($service->jumlah / $total * 100) : 0 }}%"></div></div></div>@endforeach</div></section></div>
  <section class="table-container report-section"><h3 class="report-section-title">Rekap per Instansi</h3><div class="table-wrap"><table class="table"><thead><tr><th>Nama Instansi</th><th>Jumlah Pembinaan</th><th>Terakhir Dibina</th><th>Aksi</th></tr></thead><tbody>@forelse($instansiRows as $row)<tr><td>{{ $row->instansi->nama }}</td><td>{{ $row->jumlah }}</td><td>{{ \Carbon\Carbon::parse($row->terakhir)->format('d M Y') }}</td><td><a class="action-link" href="{{ route('pembinaan.index', ['instansi_id' => $row->instansi_id]) }}">Lihat</a></td></tr>@empty<tr><td colspan="4" class="empty">Belum ada data.</td></tr>@endforelse</tbody></table></div></section>
  <section class="table-container report-section"><h3 class="report-section-title">Rekap Narasumber{{ $year ? ' ('.$year.($quarter ? ' - Triwulan '. $quarter : ''). ')' : '' }}</h3><div class="table-wrap"><table class="table"><thead><tr><th>Nama Narasumber</th><th>Jumlah Pembinaan</th><th>Terakhir Bertugas</th></tr></thead><tbody>@forelse($narasumberRows as $row)<tr><td>{{ $row->nama_lengkap }}</td><td>{{ $row->jumlah }}</td><td>{{ \Carbon\Carbon::parse($row->terakhir)->format('d M Y') }}</td></tr>@empty<tr><td colspan="3" class="empty">Belum ada data.</td></tr>@endforelse</tbody></table></div></section>
  <div class="table-container"><h3 class="report-section-title">Daftar Kegiatan</h3><div class="table-wrap"><table class="table"><thead><tr><th>Periode</th><th>Instansi</th><th>Tanggal Kegiatan</th><th>Narasumber</th><th>Status Dokumen</th></tr></thead><tbody>@forelse($items as $item)<tr><td>Triwulan {{ intdiv($item->tanggal_mulai->month - 1, 3) + 1 }} (TW{{ intdiv($item->tanggal_mulai->month - 1, 3) + 1 }})</td><td>{{ $item->instansi->nama }}</td><td>{{ $item->tanggal_mulai->format('d M Y') }}</td><td>{{ $item->pegawai->pluck('nama_lengkap')->join(', ') ?: '-' }}</td><td><span class="badge {{ $item->status_dokumen === 'Selesai' ? 'badge-success' : 'badge-warning' }}">{{ $item->status_dokumen }}</span></td></tr>@empty<tr><td colspan="5" class="empty">Belum ada data rekap untuk periode ini.</td></tr>@endforelse</tbody></table></div></div>
  {{ $items->links() }}
</div>
@endsection

