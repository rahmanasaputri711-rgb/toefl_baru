<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect()
    {
        // Cek apakah Google OAuth sudah dikonfigurasi
        if (empty(config('services.google.client_id')) || 
            config('services.google.client_id') === 'your-google-client-id-here') {
            return redirect('/login')->with('error', 
                'Login Google belum dikonfigurasi. Gunakan login email/password atau hubungi admin untuk setup Google OAuth.');
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Login Google gagal: ' . $e->getMessage());
        }

        // Cari user berdasarkan google_id dulu, lalu email
        $user = User::where('google_id', $googleUser->getId())->first()
              ?? User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update data Google jika login pertama kali pakai Google
            $user->update([
                'google_id'     => $googleUser->getId(),
                'avatar'        => $googleUser->getAvatar(),
                'last_login_at' => now(),
            ]);
        } else {
            // Buat akun baru
            $user = User::create([
                'name'          => $googleUser->getName(),
                'email'         => $googleUser->getEmail(),
                'google_id'     => $googleUser->getId(),
                'avatar'        => $googleUser->getAvatar(),
                'password'      => bcrypt(\Illuminate\Support\Str::random(24)),
                'is_active'     => 1,
                'role'          => 'user',
            ]);
        }

        Auth::login($user, true);

        return redirect($user->role === 'admin' ? '/admin' : '/dashboard');
    }
}
