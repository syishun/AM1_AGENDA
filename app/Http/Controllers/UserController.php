<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kelas;
use App\Models\Data_guru;
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

        if (!empty($role)) {
            $userQuery->where('role', $role);
        }

        if (!empty($search)) {
            $userQuery->where('name', 'like', '%' . $search . '%');
        }

        $user = $userQuery->get();

        // Mendapatkan data kelas dan data guru dengan pengurutan kode_guru
        $kelas = Kelas::all();
        $data_guru = Data_guru::all()->sort(function ($a, $b) {
            preg_match('/(\d+)([a-z]*)/', $a->kode_guru, $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b->kode_guru, $matchesB);

            return $matchesA[1] === $matchesB[1]
                ? strcmp($matchesA[2], $matchesB[2])
                : $matchesA[1] - $matchesB[1];
        });

        return view('admin.user.index', compact('user', 'kelas', 'data_guru'), ['title' => 'Data Pengguna']);
    }

    public function create()
    {
        $user = User::all();
        $kelas = Kelas::orderByRaw("FIELD(kelas, 'X', 'XI', 'XII')")
            ->orderBy('kelas_id', 'asc')
            ->get();
        $data_guru = Data_guru::all()->sort(function ($a, $b) {
            preg_match('/(\d+)([a-z]*)/', $a->kode_guru, $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b->kode_guru, $matchesB);

            return $matchesA[1] === $matchesB[1]
                ? strcmp($matchesA[2], $matchesB[2])
                : $matchesA[1] - $matchesB[1];
        });

        return view('admin.user.create', compact('user', 'kelas', 'data_guru'), ['title' => 'Tambah Pengguna']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users,name',
            'password' => 'required|min:6',
            'role' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id|required_if:role,Sekretaris',
            'kode_guru' => 'nullable|exists:data_gurus,id|required_if:role,Guru',
        ]);

        // Pastikan kelas_id hanya bisa diisi oleh Sekretaris
        if ($request->role !== 'Sekretaris' && $request->filled('kelas_id')) {
            return redirect()->back()->withErrors(['kelas_id' => 'Kelas hanya dapat dipilih oleh pengguna dengan peran Sekretaris.']);
        }

        if ($request->role !== 'Guru' && $request->filled('kode_guru')) {
            return redirect()->back()->withErrors(['kode_guru' => 'Kode guru hanya dapat dipilih oleh pengguna dengan peran Guru.']);
        }

        $add = new User;
        $add->name = $request->name;
        $add->password = Hash::make($request->password);
        $add->role = $request->role;
        $add->kelas_id = $request->kelas_id;
        $add->kode_guru = $request->kode_guru;

        $add->save();

        return redirect('user')->with('status', 'Data berhasil ditambah');
    }

    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $kelas = Kelas::orderByRaw("FIELD(kelas, 'X', 'XI', 'XII')")
            ->orderBy('kelas_id', 'asc')
            ->get();
        $data_guru = Data_guru::all()->sort(function ($a, $b) {
            preg_match('/(\d+)([a-z]*)/', $a->kode_guru, $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b->kode_guru, $matchesB);

            return $matchesA[1] === $matchesB[1]
                ? strcmp($matchesA[2], $matchesB[2])
                : $matchesA[1] - $matchesB[1];
        });

        return view('admin.user.edit', compact('user', 'kelas', 'data_guru'), ['title' => 'Edit Pengguna']);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:users,name,' . $id,
            'password' => 'nullable|min:6',
            'role' => 'required',
            'kelas_id' => 'nullable|exists:kelas,id|required_if:role,Sekretaris',
            'kode_guru' => 'nullable|exists:data_gurus,id|required_if:role,Guru',
        ]);

        // Pastikan kelas_id hanya bisa diisi oleh Sekretaris
        if ($request->role !== 'Sekretaris' && $request->filled('kelas_id')) {
            return redirect()->back()->withErrors(['kelas_id' => 'Kelas hanya dapat dipilih oleh pengguna dengan peran Sekretaris.']);
        }

        if ($request->role !== 'Guru' && $request->filled('kode_guru')) {
            return redirect()->back()->withErrors(['kode_guru' => 'Kode guru hanya dapat dipilih oleh pengguna dengan peran Guru.']);
        }

        $user = User::findOrFail($id);
        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->role = $request->role;
        $user->kelas_id = $request->kelas_id;
        $user->kode_guru = $request->kode_guru;
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
