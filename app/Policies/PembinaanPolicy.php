<?php
namespace App\Policies;
use App\Models\Pembinaan;
use App\Models\User;
class PembinaanPolicy {
 public function viewAny(User $user): bool { return in_array($user->role, ['admin','pelaksana','monitoring'], true); }
  public function view(User $user, Pembinaan $pembinaan): bool { return in_array($user->role, ['admin','pelaksana','monitoring'], true); }
 public function create(User $user): bool { return in_array($user->role, ['admin','pelaksana'], true); }
  public function update(User $user, Pembinaan $pembinaan): bool { return $user->role === 'admin' || ($user->role === 'pelaksana' && (int) $pembinaan->created_by === (int) $user->id && $pembinaan->workflow_status === 'draft'); }
 public function delete(User $user, Pembinaan $pembinaan): bool { return $user->role === 'admin'; }
}
