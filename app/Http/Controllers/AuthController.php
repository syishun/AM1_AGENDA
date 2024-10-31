<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'name' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on the user's role
            $user = Auth::user();
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admin.index'); // Change route accordingly
                case 'Guru':
                    return redirect()->route('guru.index'); // Change route accordingly
                case 'Perwakilan Kelas':
                    return redirect()->route('siswa.index'); // Change route accordingly
                default:
                    return redirect('/');
            }
        }

        return back()->withErrors([
            'name' => 'Nama pengguna atau kata sandi salah.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('login');
    }
}
