@extends('master.layout')
@section('title','Permintaan Perubahan')
@section('header','Permintaan Perubahan')
@section('content')
<div class="page-header"><div><h2 class="page-title">Permintaan Perubahan</h2><p class="page-subtitle">Review pengajuan perubahan data submitted.</p></div></div>
<div class="table-container"><div class="table-wrap"><table class="table"><thead><tr><th>Pengaju</th><th>Data</th><th>Alasan</th><th>Aksi</th></tr></thead><tbody>@forelse($items as $item)<tr><td>{{ $item->requester->name }}<br><small>{{ $item->requester->email }}</small></td><td>{{ $item->pembinaan->instansi->nama }}<br><small>{{ $item->pembinaan->layanan }}</small></td><td>{{ $item->reason }}</td><td><form method="post" action="{{ route('change-requests.approve',$item) }}" style="display:inline">@csrf @method('PATCH')<button class="btn btn-primary" type="submit">Setujui</button></form><form method="post" action="{{ route('change-requests.reject',$item) }}" style="margin-top:8px">@csrf @method('PATCH')<input class="input" name="review_note" placeholder="Alasan penolakan" required><button class="btn btn-secondary" type="submit">Tolak</button></form></td></tr>@empty<tr><td colspan="4" class="empty">Belum ada permintaan perubahan.</td></tr>@endforelse</tbody></table></div></div>
@endsection
