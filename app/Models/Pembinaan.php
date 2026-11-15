<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembinaan extends Model {
    use HasFactory, HasPembinaanChangeRequests;
    protected $table = 'pembinaan';
    protected $fillable = ['instansi_id','kategori_instansi','created_by','nama_kegiatan','jenis_pembinaan','satker','tanggal_mulai','tanggal_selesai','metode_pelaksanaan','sumber_kegiatan','lokasi','provinsi','kota','layanan','integrasi_aplikasi','catatan_hasil','status_dokumen','workflow_status'];
    protected function casts(): array { return ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date']; }
    public function instansi(): BelongsTo { return $this->belongsTo(Instansi::class, 'instansi_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function pegawai(): BelongsToMany { return $this->belongsToMany(Pegawai::class, 'pembinaan_pegawai', 'pembinaan_id', 'pegawai_id')->withPivot('peran'); }
    public function files(): HasMany { return $this->hasMany(PembinaanFile::class, 'pembinaan_id'); }
}


