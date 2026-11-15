@extends('master.layout')
@section('title','Pegawai ? SRIKANDI')
@section('header','Data Master / Pegawai')
@section('content')
<div class="pegawai-list-page">
  <div class="pegawai-list-heading"><div><span class="pegawai-eyebrow">DATA MASTER</span><h2>Profil Pegawai</h2><p>Kelola profil pegawai yang dapat menjadi narasumber atau tim pembinaan.</p></div><a class="btn btn-primary" href="{{ route('pegawai.create') }}">+ Tambah Pegawai</a></div>
  <div class="pegawai-list-card">
    <div class="pegawai-list-card__head"><div><h3>Daftar Pegawai</h3><span>{{ $items->total() }} profil terdaftar</span></div></div>
    <div class="table-wrap"><table class="table pegawai-table"><thead><tr><th>Profil</th><th>NIP</th><th>Kontak</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
    @forelse($items as $item)
      <tr><td><div class="pegawai-table-profile"><div class="pegawai-table-avatar">@if($item->foto)<img src="{{ asset('storage/'.$item->foto) }}" alt="Foto {{ $item->nama_lengkap }}">@else{{ strtoupper(mb_substr($item->nama_lengkap,0,1)) }}@endif</div><div><strong>{{ $item->nama_lengkap }}</strong><small>Pegawai / Narasumber</small></div></div></td><td>{{ $item->nip ?: '-' }}</td><td><div>{{ $item->email ?: '-' }}</div><small>{{ $item->nomor_telepon ?: '-' }}</small></td><td><span class="badge {{ $item->status_aktif ? 'ok':'off' }}">{{ $item->status_aktif ? 'Aktif':'Nonaktif' }}</span></td><td><div class="actions"><a class="btn btn-secondary" href="{{ route('pegawai.edit',$item) }}">Edit</a><form method="post" action="{{ route('pegawai.destroy',$item) }}" onsubmit="return confirm('Nonaktifkan pegawai ini?')">@csrf @method('DELETE')<button class="btn btn-danger" type="submit">Nonaktifkan</button></form></div></td></tr>
    @empty<tr><td class="empty" colspan="5">Belum ada pegawai.</td></tr>@endforelse
    </tbody></table></div>{{ $items->links() }}
  </div>
</div>
@endsection
