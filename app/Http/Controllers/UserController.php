<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // Mendapatkan nilai filter role dan pencarian username dari request
        $role = $request->query('filterRole');
        $search = $request->query('search');

        // Query pengguna dengan filter dan pencarian
        $userQuery = User::query();

        // Jika filter role diisi, tambahkan kondisi where
        if (!empty($role)) {
            $userQuery->where('role', $role);
        }

        // Jika ada pencarian username, tambahkan kondisi where untuk name
        if (!empty($search)) {
            $userQuery->where('name', 'like', '%' . $search . '%');
        }

        // Eksekusi query dan ambil hasil
        $user = $userQuery->get();

        // Mendapatkan data kelas untuk dropdown (jika diperlukan)
        $kelas = Kelas::all();

        // Menampilkan data ke view
        return view('admin.user.index', compact('user', 'kelas'), ['title' => 'Data Pengguna']);
    }

    public function create()
    {
        $user = User::all();
        $kelas = Kelas::all();
        return view('admin.user.create', compact('user', 'kelas'), ['title' => 'Tambah Pengguna']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'password' => 'required|min:6',
            'role' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id|required_if:role,Perwakilan Kelas',
        ]);

        // Pastikan kelas_id hanya bisa diisi oleh Perwakilan Kelas
        if ($request->role !== 'Perwakilan Kelas' && $request->filled('kelas_id')) {
            return redirect()->back()->withErrors(['kelas_id' => 'Kelas hanya dapat dipilih oleh pengguna dengan peran Perwakilan Kelas.']);
        }

        $add = new User;
        $add->name = $request->name;
        $add->password = Hash::make($request->password);
        $add->role = $request->role;
        $add->kelas_id = $request->kelas_id;

        $add->save();

        return redirect('user')->with('status', 'Data berhasil ditambah');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.user.edit', compact('user', 'kelas'), ['title' => 'Edit Pengguna']);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:users,name,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id|required_if:role,Perwakilan Kelas',
        ]);

        // Pastikan kelas_id hanya bisa diisi oleh Perwakilan Kelas
        if ($request->role !== 'Perwakilan Kelas' && $request->filled('kelas_id')) {
            return redirect()->back()->withErrors(['kelas_id' => 'Kelas hanya dapat dipilih oleh pengguna dengan peran Perwakilan Kelas.']);
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->role;
        $user->kelas_id = $request->kelas_id;
        $user->save();

        return redirect('user')->with('status', 'Data berhasil diupdate');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect('user')->with('status', 'Data berhasil dihapus');
    }
}
