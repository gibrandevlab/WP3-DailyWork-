<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class CustomerController extends Controller
{
    // Redirect ke Google
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Callback dari Google
    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            
            // Cek apakah email sudah terdaftar
            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                // Buat user baru
                $user = User::create([
                    'nama' => $socialUser->name,
                    'email' => $socialUser->email,
                    'role' => '2', // Role customer
                    'status' => 1, // Status aktif
                    'password' => Hash::make(uniqid()), // Password default
                    'hp' => '-', // Default value
                ]);

                // Buat data customer
                Customer::create([
                    'user_id' => $user->id,
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token
                ]);

                // Login pengguna baru
                Auth::login($user);
            } else {
                // Jika email sudah terdaftar, update google_id dan token
                $customer = Customer::where('user_id', $registeredUser->id)->first();
                if ($customer) {
                    $customer->update([
                        'google_id' => $socialUser->id,
                        'google_token' => $socialUser->token
                    ]);
                } else {
                    Customer::create([
                        'user_id' => $registeredUser->id,
                        'google_id' => $socialUser->id,
                        'google_token' => $socialUser->token
                    ]);
                }
                
                // Login pengguna yang sudah ada
                Auth::login($registeredUser);
            }

            // Redirect ke halaman utama
            return redirect()->intended('beranda');
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }
}