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

        $remember = $request->has('remember'); // Check if "Remember Me" is selected

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect based on the user's role
            $user = Auth::user();
            switch ($user->role) {
                case 'Admin':
                    return redirect()->route('admin.index');
                case 'Guru':
                    return redirect()->route('guru.index');
                case 'Sekretaris':
                    return redirect()->route('siswa.index');
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
