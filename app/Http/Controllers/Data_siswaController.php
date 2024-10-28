<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data_siswa;
use App\Models\Kelas;

class Data_siswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Mendapatkan data filter kelas_id dan search dari request
        $filterKelas = $request->get('filterKelas');
        $search = $request->get('search');

        // Query untuk mendapatkan data siswa
        $data_siswaQuery = Data_siswa::query();

        // Jika filter kelas_id tidak kosong, tambahkan kondisi where
        if (!empty($filterKelas)) {
            $data_siswaQuery->where('kelas_id', $filterKelas);
        }

        // Jika ada pencarian, tambahkan kondisi where untuk nama siswa
        if (!empty($search)) {
            $data_siswaQuery->where('nama_siswa', 'like', '%' . $search . '%');
        }

        // Eksekusi query dan dapatkan hasil
        $data_siswa = $data_siswaQuery->get();

        // Mendapatkan daftar kelas untuk dropdown
        $kelas = Kelas::all();

        // Menampilkan data ke view
        return view('admin.data_siswa.index', compact('data_siswa', 'kelas'), ['title' => 'Data Siswa']);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data_siswa = Data_siswa::all();
        $kelas = Kelas::all();
        return view('admin.data_siswa.create', compact('data_siswa', 'kelas'), ['title' => 'Tambah Data Siswa']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nama_siswa' => 'required',
                'nisn_id' => 'required',
                'gender' => 'required',
                'kelas_id' => 'required',
            ],
            [
                'nama_siswa' => 'Nama siswa tidak boleh kosong',
                'nisn_id' => 'NISN tidak boleh kosong',
                'gender' => 'Gender tidak boleh kosong',
                'kelas_id' => 'Kelas tidak boleh kosong',
            ]
        );

        $add = new Data_siswa;

        $add->nama_siswa = $request->nama_siswa;
        $add->nisn_id = $request->nisn_id;
        $add->gender = $request->gender;
        $add->kelas_id = $request->kelas_id;

        $add->save();
        return redirect('data_siswa')->with('status', 'Data berhasil ditambah');
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
        $data_siswa = Data_siswa::findOrFail($id);
        $kelas = Kelas::all();
        return view('admin.data_siswa.edit', compact('data_siswa', 'kelas'),  ['title' => 'Edit Data Siswa']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_siswa' => 'required',
            'nisn_id' => 'required',
            'gender' => 'required',
            'kelas_id' => 'required',
        ]);

        $data_siswa = Data_siswa::findOrFail($id);

        $data_siswa->nama_siswa = $request->nama_siswa;
        $data_siswa->nisn_id = $request->nisn_id;
        $data_siswa->gender = $request->gender;
        $data_siswa->kelas_id = $request->kelas_id;
        $data_siswa->save();

        return redirect('data_siswa')->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data_siswa = Data_siswa::findOrFail($id);
        $data_siswa->delete();

        return redirect('data_siswa')->with('status', 'Data berhasil dihapus');
    }
}
