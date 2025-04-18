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
    // Google Login: Redirect
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // Google Login: Callback
    public function callback()
    {
        try {
            $socialUser = Socialite::driver('google')->user();
            $registeredUser = User::where('email', $socialUser->email)->first();

            if (!$registeredUser) {
                $user = User::create([
                    'nama' => $socialUser->name,
                    'email' => $socialUser->email,
                    'role' => '2',
                    'status' => 1,
                    'password' => Hash::make(uniqid()),
                    'hp' => '-',
                ]);

                Customer::create([
                    'user_id' => $user->id,
                    'google_id' => $socialUser->id,
                    'google_token' => $socialUser->token
                ]);

                Auth::login($user);
            } else {
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

                Auth::login($registeredUser);
            }

            return redirect()->intended('beranda');
        } catch (\Exception $e) {
            Log::error('Google login error: ' . $e->getMessage());
            return redirect('/')->with('error', 'Terjadi kesalahan saat login dengan Google: ' . $e->getMessage());
        }
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'Anda telah berhasil logout.');
    }

    // Index / list data customer
    public function index()
    {
        $customer = Customer::with('user')->orderBy('id', 'desc')->get();
        return view('backend.v_customer.index', [
            'judul' => 'Customer',
            'sub' => 'Halaman Customer',
            'index' => $customer
        ]);
    }

    // Tampilkan form tambah data
    public function create()
    {
        $users = User::where('role', 2)->get();
        return view('backend.v_customer.create', [
            'judul' => 'Tambah Customer',
            'users' => $users
        ]);
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'google_id' => 'nullable|string',
            'google_token' => 'nullable|string',
        ]);

        Customer::create($request->all());

        return redirect()->route('backend.customer.index')->with('success', '...');
    }

    // Tampilkan detail customer
    public function show($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('backend.v_customer.show', [
            'judul' => 'Detail Customer',
            'customer' => $customer
        ]);
    }

    // Tampilkan form edit
    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        $users = User::where('role', 2)->get();
        return view('backend.v_customer.edit', [
            'judul' => 'Edit Customer',
            'customer' => $customer,
            'users' => $users
        ]);
    }

    // Update data
    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'google_id' => 'nullable|string',
            'google_token' => 'nullable|string',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['user_id', 'google_id', 'google_token']));

        return redirect()->route('backend.customer.index')->with('success', '...');
    }

    // Hapus data
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('backend.customer.index')->with('success', '...');
    }
}
