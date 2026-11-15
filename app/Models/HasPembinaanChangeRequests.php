<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
trait HasPembinaanChangeRequests { public function changeRequests(): HasMany { return $this->hasMany(PembinaanChangeRequest::class); } }