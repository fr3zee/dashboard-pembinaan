<?php
namespace App\Policies;

use App\Models\PembinaanFile;
use App\Models\User;
class PembinaanFilePolicy {
 public function viewAny(User $user): bool { return true; }
 public function view(User $user,PembinaanFile $file): bool { return $file->jenis === 'laporan' || $file->visibilitas === 'Publikasi' || in_array($user->role,['admin','pelaksana'],true); }
 public function create(User $user): bool { return in_array($user->role,['admin','pelaksana'],true); }
 public function delete(User $user,PembinaanFile $file): bool { return $user->role === 'admin' || ($user->role === 'pelaksana' && (int) $file->pembinaan->created_by === (int) $user->id); }
}
