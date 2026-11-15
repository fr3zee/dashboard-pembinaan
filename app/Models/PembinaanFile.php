<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PembinaanFile extends Model {
    use HasFactory;
    protected $table = 'pembinaan_files';
    protected $fillable = ['pembinaan_id','uploaded_by','jenis','nama_asli','path','mime_type','ukuran','visibilitas'];
    protected function casts(): array { return ['ukuran' => 'integer']; }
    public function pembinaan(): BelongsTo { return $this->belongsTo(Pembinaan::class, 'pembinaan_id'); }
    public function uploader(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
