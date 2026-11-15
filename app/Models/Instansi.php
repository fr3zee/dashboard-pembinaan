<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Instansi extends Model {
    use HasFactory;
    protected $table = 'instansi';
    protected $fillable = ['kategori_instansi_id', 'nama', 'status_aktif'];
    protected function casts(): array { return ['status_aktif' => 'boolean']; }
    public function kategoriInstansi(): BelongsTo { return $this->belongsTo(KategoriInstansi::class, 'kategori_instansi_id'); }
    public function pembinaan(): HasMany { return $this->hasMany(Pembinaan::class, 'instansi_id'); }
}
