<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['email' => ['required', 'email']]);
        $data['email'] = Str::lower(trim($data['email']));

        $status = Password::sendResetLink($data);

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', 'Link reset password sudah dikirim ke email.')
            : back()->withErrors(['email' => $status === Password::INVALID_USER
                ? 'Email belum terdaftar sebagai akun login. Gunakan email pada menu Manajemen Pengguna.'
                : __($status)]);
    }
}
