@extends('master.layout')


@section('title','Export Laporan Ã¢â‚¬â€ SRIKANDI')


@section('header','Export Laporan')


@section('content')


<div class="export-hero"><div><span class="eyebrow">LAPORAN PEMBINAAN</span><h2 class="page-title">Export Laporan Pembinaan</h2><p class="page-subtitle">Pilih periode, susun filter, lalu preview laporan sebelum diunduh.</p></div><div class="export-hero-mark" aria-hidden="true">&#128196;</div></div>


<form method="get" class="filter-section export-filter"><div class="section-heading"><div><span class="eyebrow">KONFIGURASI</span><h3>Pilih Jenis Laporan</h3></div></div><label class="export-option"><input type="radio" name="jenis" value="kegiatan" @checked($jenis==='kegiatan')><span><strong>Laporan Kegiatan Pembinaan</strong><small>Daftar kegiatan lengkap berdasarkan periode dan filter.</small></span></label><label class="export-option"><input type="radio" name="jenis" value="narasumber" @checked($jenis==='narasumber')><span><strong>Rekap Kinerja Narasumber</strong><small>Ringkasan kegiatan berdasarkan narasumber.</small></span></label><div class="section-heading period-heading"><div><span class="eyebrow">PERIODE</span><h3>Pilih Periode Laporan</h3></div></div><div class="period-option"><input type="radio" name="mode_periode" value="triwulan" @checked(request('mode_periode', 'triwulan')==='triwulan')><strong>Triwulan</strong><select class="input" name="triwulan"><option value="">Semua Triwulan</option>@for($i=1;$i<=4;$i++)<option value="{{$i}}" @selected(request('triwulan')==$i)>Triwulan {{$i}} (TW{{$i}})</option>@endfor</select><select class="input" name="tahun"><option value="">Semua Tahun</option>@foreach($years as $y)<option value="{{$y}}" @selected(request('tahun')==$y)>{{$y}}</option>@endforeach</select></div><div class="period-option"><input type="radio" name="mode_periode" value="manual" @checked(request('mode_periode')==='manual')><strong>Manual</strong><input class="input" type="date" name="mulai" value="{{request('mulai')}}"><span class="text-muted">s/d</span><input class="input" type="date" name="selesai" value="{{request('selesai')}}"></div><div class="export-extra-filters"><select class="input" name="instansi_id"><option value="">Semua Instansi</option>@foreach($instansi as $i)<option value="{{$i->id}}" @selected(request('instansi_id')==$i->id)>{{$i->nama}}</option>@endforeach</select><select class="input" name="pegawai_id"><option value="">Semua Narasumber</option>@foreach($pegawai as $p)<option value="{{$p->id}}" @selected(request('pegawai_id')==$p->id)>{{$p->nama_lengkap}}</option>@endforeach</select><input class="input" name="layanan" placeholder="Semua Jenis Pembinaan" value="{{request('layanan')}}"></div><button class="btn btn-primary" type="submit">Terapkan Filter</button></form>


<section class="preview-section"><div class="section-heading"><div><span class="eyebrow">PREVIEW</span><h3>Preview Laporan {{ $jenis==='narasumber'?'Kinerja Narasumber':'Kegiatan Pembinaan' }}</h3></div><span class="export-ready">&#10003; Siap diunduh</span></div><p class="text-muted export-meta">Total Data: {{ $items->count() }} pembinaan | Jumlah Instansi: {{ $items->pluck('instansi_id')->unique()->count() }}@if(request('triwulan')) | Periode: Q{{ request('triwulan') }} &middot; {{ ['1'=>'Jan s/d Mar','2'=>'Apr s/d Jun','3'=>'Jul s/d Sep','4'=>'Okt s/d Des'][request('triwulan')] ?? '' }} {{ request('tahun') }}@endif</p><div class="table-container"><div class="table-wrap"><table class="table"><thead>@if($jenis==='narasumber')<tr><th>Narasumber</th><th>Total Kegiatan</th><th>Sosialisasi</th><th>Integrasi</th><th>Kegiatan Terakhir</th></tr>@else<tr><th>Instansi</th><th>Tanggal</th><th>Layanan</th><th>Metode</th><th>Narasumber</th><th>Status</th></tr>@endif</thead><tbody>@if($jenis==='narasumber')@forelse($rows as $row)<tr><td><strong>{{ $row['nama'] }}</strong></td><td><span class="badge badge-primary">{{ $row['total'] }}</span></td><td>{{ $row['sosialisasi'] }}</td><td>{{ $row['integrasi'] }}</td><td>{{ $row['terakhir'] }}</td></tr>@empty<tr><td colspan="5" class="empty">Tidak ada data narasumber sesuai filter.</td></tr>@endforelse @else @forelse($rows as $item)<tr><td>{{ $item->instansi->nama }}</td><td>{{ $item->tanggal_mulai->format('d M Y') }}</td><td>{{ $item->layanan }}</td><td>{{ $item->metode_pelaksanaan }}</td><td>{{ $item->pegawai->pluck('nama_lengkap')->join(', ') ?: '-' }}</td><td>{{ $item->status_dokumen }}</td></tr>@empty<tr><td colspan="6" class="empty">Tidak ada data sesuai filter.</td></tr>@endforelse @endif</tbody></table></div></div><a class="btn btn-success export-download" href="{{route('export-laporan.excel',request()->query())}}">Download Excel</a></section>


@endsection


<script>document.addEventListener('DOMContentLoaded',()=>{const radios=document.querySelectorAll('input[name="mode_periode"]'),dates=document.querySelectorAll('input[name="mulai"],input[name="selesai"]');function sync(){const manual=document.querySelector('input[name="mode_periode"]:checked')?.value==='manual';dates.forEach(input=>{input.disabled=!manual;input.required=manual;input.closest('.period-option')?.classList.toggle('period-active',manual)});}radios.forEach(radio=>radio.addEventListener('change',sync));sync();});</script>



