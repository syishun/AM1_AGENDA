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
    public function index()
    {
        $mapel = Mapel::with('data_guru')->get(); // Include related data_guru to access kode_guru

        // Convert to array to sort
        $mapelArray = $mapel->toArray();

        // Sort the array based on kode_guru
        usort($mapelArray, function ($a, $b) {
            // Extract numeric and letter parts of kode_guru using regex
            preg_match('/(\d+)([a-z]*)/', $a['data_guru']['kode_guru'], $matchesA);
            preg_match('/(\d+)([a-z]*)/', $b['data_guru']['kode_guru'], $matchesB);

            // Sort numerically first
            if ($matchesA[1] == $matchesB[1]) {
                return strcmp($matchesA[2], $matchesB[2]); // Sort alphabetically for letters
            }

            return $matchesA[1] - $matchesB[1]; // Sort by number part
        });

        // Convert back to collection after sorting
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
