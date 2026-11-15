<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class PembinaanChangeRequest extends Model {
 protected $fillable=['pembinaan_id','requested_by','reviewed_by','reason','review_note','status'];
 public function pembinaan(): BelongsTo { return $this->belongsTo(Pembinaan::class); }
 public function requester(): BelongsTo { return $this->belongsTo(User::class,'requested_by'); }
 public function reviewer(): BelongsTo { return $this->belongsTo(User::class,'reviewed_by'); }
}