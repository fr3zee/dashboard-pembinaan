<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable {
 use HasFactory, Notifiable;
 protected $fillable = ['name','email','nomor_telepon','nip','unit_kerja','instansi','role','status_aktif','avatar','password'];
 protected $hidden = ['password','remember_token'];
 protected function casts(): array { return ['email_verified_at'=>'datetime','password'=>'hashed','status_aktif'=>'boolean']; }
 public function pembinaan(): HasMany { return $this->hasMany(Pembinaan::class, 'created_by'); }
 public function uploadedFiles(): HasMany { return $this->hasMany(PembinaanFile::class, 'uploaded_by'); }
}
