<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Pegawai extends Model {
    use HasFactory;
    protected $table = 'pegawai';
    protected $fillable = ['nama_lengkap', 'nip', 'email', 'nomor_telepon', 'foto', 'status_aktif'];
    protected function casts(): array { return ['status_aktif' => 'boolean']; }
    public function pembinaan(): BelongsToMany { return $this->belongsToMany(Pembinaan::class, 'pembinaan_pegawai', 'pegawai_id', 'pembinaan_id'); }
}
