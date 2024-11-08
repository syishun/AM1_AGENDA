<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mapel;
use App\Models\Data_guru;

class MapelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search'); // Ambil input pencarian

        // Query untuk mendapatkan data dengan relasi data_guru
        $mapel = Mapel::with('data_guru')
            ->when($search, function ($query, $search) {
                // Filter berdasarkan nama guru atau nama mapel
                $query->whereHas('data_guru', function ($query) use ($search) {
                    $query->where('nama_guru', 'like', '%' . $search . '%');
                })->orWhere('nama_mapel', 'like', '%' . $search . '%');
            })
            ->get();

        // Convert ke array dan sort berdasarkan kode_guru
        $mapelArray = $mapel->toArray();

        usort($mapelArray, function ($a, $b) {
            preg_match('/(\d+)([a-z]*)/', $a['data_guru']['kode_guru'], $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b['data_guru']['kode_guru'], $matchesB);

            if ($matchesA[1] == $matchesB[1]) {
                return strcmp($matchesA[2], $matchesB[2]);
            }

            return $matchesA[1] - $matchesB[1];
        });

        // Convert kembali ke koleksi setelah sorting
        $mapelSorted = collect($mapelArray);

        return view('admin.mapel.index', compact('mapelSorted'), ['title' => 'Mata Pelajaran']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Ambil semua data_guru dan urutkan berdasarkan kode_guru
        $data_guru = Data_guru::all()->sortBy(function ($guru) {
            // Pisahkan angka dan huruf dari kode_guru
            preg_match('/(\d+)([a-z]*)/', $guru->kode_guru, $matches);
            return [
                (int) $matches[1], // Urutkan berdasarkan angka
                $matches[2],       // Urutkan berdasarkan huruf
            ];
        });

        return view('admin.mapel.create', compact('data_guru'), ['title' => 'Tambah Mata Pelajaran']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_mapel' => 'required',
                'kode_guru' => 'required',
                'mapel_id' => 'required',
            ],
            [
                'nama_mapel' => 'Nama mapel tidak boleh kosong',
                'kode_guru' => 'Kode guru tidak boleh kosong',
                'mapel_id' => 'ID mapel tidak boleh kosong',
            ]
        );

        $add = new Mapel;

        $add->nama_mapel = $request->nama_mapel;
        $add->kode_guru = $request->kode_guru;
        $add->mapel_id = $request->mapel_id;

        $add->save();
        return redirect('mapel')->with('status', 'Data berhasil ditambah');
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
        $mapel = Mapel::findOrFail($id);
        $data_guru = Data_guru::all()->sortBy(function ($guru) {
            // Pisahkan angka dan huruf dari kode_guru
            preg_match('/(\d+)([a-z]*)/', $guru->kode_guru, $matches);
            return [
                (int) $matches[1], // Urutkan berdasarkan angka
                $matches[2],       // Urutkan berdasarkan huruf
            ];
        });
        return view('admin.mapel.edit', compact('mapel', 'data_guru'), ['title' => 'Edit Mata Pelajaran']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_mapel' => 'required',
            'kode_guru' => 'required',
            'mapel_id' => 'required',
        ]);

        $mapel = Mapel::findOrFail($id);

        $mapel->nama_mapel = $request->nama_mapel;
        $mapel->kode_guru = $request->kode_guru;
        $mapel->mapel_id = $request->mapel_id;
        $mapel->save();

        return redirect('mapel')->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $mapel = Mapel::findOrFail($id);
        $mapel->delete();

        return redirect('mapel')->with('status', 'Data berhasil dihapus');
    }
}
