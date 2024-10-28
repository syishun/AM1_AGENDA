<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kelas;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan data filter dari request
        $filterKelas = $request->get('filterKelas');

        // Query awal
        $kelasQuery = Kelas::query();

        // Jika filter kelas tidak kosong
        if (!empty($filterKelas)) {
            $kelasQuery->where('kelas', $filterKelas);
        }

        // Eksekusi query dan dapatkan hasil
        $kelas = $kelasQuery->get();

        // Menampilkan data ke view
        return view('admin.kelas.index', compact('kelas'), ['title' => 'Data Kelas']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('admin.kelas.create', compact('kelas'), ['title' => 'Tambah Data Kelas']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'kelas' => 'required',
                'angkatan' => 'required|integer|min:1',
                'kelas_id' => 'required',
            ],
            [
                'kelas' => 'Kelas tidak boleh kosong',
                'angkatan.required' => 'Angkatan tidak boleh kosong',
                'angkatan.min' => 'Angkatan harus lebih besar atau sama dengan 1',
                'kelas_id' => 'ID kelas tidak boleh kosong',
            ]
        );

        $add = new Kelas;

        $add->kelas = $request->kelas;
        $add->angkatan = $request->angkatan;
        $add->kelas_id = $request->kelas_id;

        $add->save();
        return redirect('kelas')->with('status', 'Data berhasil ditambah');
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
        $kelas = Kelas::findOrFail($id);
        return view('admin.kelas.edit', compact('kelas'),  ['title' => 'Edit Data Kelas']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'kelas' => 'required',
            'angkatan' => 'required|integer|min:1',
            'kelas_id' => 'required',
        ], [
            'kelas' => 'Kelas tidak boleh kosong',
            'angkatan.required' => 'Angkatan tidak boleh kosong',
            'angkatan.min' => 'Angkatan harus lebih besar atau sama dengan 1',
            'kelas_id' => 'ID kelas tidak boleh kosong',
        ]);

        $kelas = Kelas::findOrFail($id);

        $kelas->kelas = $request->kelas;
        $kelas->angkatan = $request->angkatan;
        $kelas->kelas_id = $request->kelas_id;

        $kelas->save();

        return redirect('kelas')->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();

        return redirect('kelas')->with('status', 'Data berhasil dihapus');
    }
}
