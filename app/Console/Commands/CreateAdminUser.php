<?php
namespace App\Console\Commands;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
class CreateAdminUser extends Command {
 protected $signature = 'app:create-admin {email?}';
 protected $description = 'Create or promote an administrator without storing a password in code';
 public function handle(): int {
  $email = $this->argument('email') ?: $this->ask('Admin email');
  $password = $this->secret('Admin password (min 12 chars)');
  if (strlen($password) < 12) { $this->error('Password minimal 12 karakter.'); return self::FAILURE; }
  $user = User::firstOrNew(['email' => $email]);
  $user->name = $user->exists ? $user->name : $this->ask('Nama admin');
  $user->password = Hash::make($password); $user->role = 'admin'; $user->save();
  $this->info('Admin berhasil dibuat/diperbarui.'); return self::SUCCESS;
 }
}
