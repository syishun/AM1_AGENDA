<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data_guru;

class Data_guruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Ambil query pencarian dari request
        $search = $request->input('search');

        // Jika ada query pencarian, filter data_guru berdasarkan nama
        if ($search) {
            $data_guru = Data_guru::where('nama_guru', 'LIKE', "%{$search}%")->get();
        } else {
            // Jika tidak ada query pencarian, ambil semua data guru
            $data_guru = Data_guru::all();
        }

        // Sorting custom (seperti sebelumnya)
        $data_guru = $data_guru->toArray(); // Convert collection to array for sorting

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
        return view('admin.data_guru.create', compact('data_guru'), ['title' => 'Tambah Data Guru']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_guru' => 'required',
                'kode_guru' => 'required',
                'gender' => 'required',
            ],
            [
                'nama_guru' => 'Nama guru tidak boleh kosong',
                'kode_guru' => 'Kode guru tidak boleh kosong',
                'gender' => 'Gender tidak boleh kosong',
            ]
        );

        $add = new Data_guru;

        $add->nama_guru = $request->nama_guru;
        $add->kode_guru = $request->kode_guru;
        $add->gender = $request->gender;

        $add->save();
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
        $data_guru = Data_guru::findOrFail($id);
        return view('admin.data_guru.edit', compact('data_guru'),  ['title' => 'Edit Data Guru']);
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
        ]);

        $data_guru = Data_guru::findOrFail($id);

        $data_guru->nama_guru = $request->nama_guru;
        $data_guru->kode_guru = $request->kode_guru;
        $data_guru->gender = $request->gender;

        $data_guru->save();

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
