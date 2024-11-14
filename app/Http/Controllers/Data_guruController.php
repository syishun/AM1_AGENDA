<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data_guru;
use App\Models\Mapel;

class Data_guruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari request
        $search = $request->input('search');

        $data_guru = Data_guru::with('mapels')
            ->when($search, function ($query, $search) {
                return $query->where('nama_guru', 'LIKE', "%{$search}%");
            })
            ->get();

        // Convert to array for sorting if needed and re-collect after sorting
        $data_guru = $data_guru->toArray();

        usort($data_guru, function ($a, $b) {
            // Pisahkan angka dan huruf dari kode_guru
            preg_match('/(\d+)([a-z]*)/', $a['kode_guru'], $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b['kode_guru'], $matchesB);

            // Urutkan berdasarkan angka
            if ($matchesA[1] == $matchesB[1]) {
                // Jika angka sama, urutkan berdasarkan huruf (a, b, c, dst.)
                return strcmp($matchesA[2], $matchesB[2]);
            }

            return $matchesA[1] - $matchesB[1];
        });

        // Kembalikan ke collection setelah sorting
        $data_guru = collect($data_guru);

        return view('admin.data_guru.index', compact('data_guru', 'search'), ['title' => 'Data Guru']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data_guru = Data_guru::all();
        $mapel = Mapel::all();
        return view('admin.data_guru.create', compact('data_guru', 'mapel'), ['title' => 'Tambah Data Guru']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_guru' => 'required',
            'kode_guru' => 'required',
            'gender' => 'required',
            'mapel_ids' => 'required|array', // Ensure it's an array of subject IDs
        ]);

        $data_guru = Data_guru::create($request->only(['nama_guru', 'kode_guru', 'gender']));

        // Attach multiple subjects (mapel)
        $data_guru->mapels()->attach($request->mapel_ids);

        return redirect('data_guru')->with('status', 'Data berhasil ditambah');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data_guru = Data_guru::with('mapels')->findOrFail($id);
        $mapel = Mapel::all();
        return view('admin.data_guru.edit', compact('data_guru', 'mapel'),  ['title' => 'Edit Data Guru']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_guru' => 'required',
            'kode_guru' => 'required',
            'gender' => 'required',
            'mapel_ids' => 'required|array', // Ensure it's an array of subject IDs
        ]);

        $data_guru = Data_guru::findOrFail($id);
        $data_guru->update($request->only(['nama_guru', 'kode_guru', 'gender']));

        // Sync subjects (mapels)
        $data_guru->mapels()->sync($request->mapel_ids);

        return redirect('data_guru')->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data_guru = Data_guru::findOrFail($id);
        $data_guru->delete();

        return redirect('data_guru')->with('status', 'Data berhasil dihapus');
    }
}
