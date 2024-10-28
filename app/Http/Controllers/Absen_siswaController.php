<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen_siswa;
use App\Models\Kelas;
use App\Models\Data_siswa;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class Absen_siswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user(); // Mendapatkan data user yang sedang login
        $search = $request->get('search');
        $searchMessage = null;

        if ($user->role == 'Perwakilan Kelas') {
            // Ambil kelas berdasarkan `kelas_id` user
            $kelasId = $user->kelas_id;
            $kelas = Kelas::findOrFail($kelasId); // Dapatkan kelas untuk menampilkan di title
            $title = 'Absensi Siswa Kelas ' . $kelas->kelas_id;

            if ($search) {
                // Cari siswa berdasarkan nama dan kelas yang sesuai
                $data_siswa = Data_siswa::where('kelas_id', $kelasId)
                    ->where('nama_siswa', 'LIKE', "%{$search}%")
                    ->first();

                if (!$data_siswa) {
                    // Jika siswa tidak ada di kelas
                    $searchMessage = 'Siswa tidak berada di kelas ini.';
                    return view('siswa.absen_siswa.index', [
                        'absen_siswa' => collect(),
                        'title' => $title,
                        'searchMessage' => $searchMessage,
                    ]);
                }

                // Cari data absensi siswa berdasarkan nisn_id siswa yang ditemukan
                $absen_siswa = Absen_siswa::with('data_siswa')
                    ->where('nisn_id', $data_siswa->id)
                    ->get();

                if ($absen_siswa->isEmpty()) {
                    // Jika siswa ada di kelas tetapi belum absen
                    $searchMessage = 'Siswa ini belum pernah absen.';
                }

                return view('siswa.absen_siswa.index', [
                    'absen_siswa' => $absen_siswa,
                    'title' => $title,
                    'searchMessage' => $searchMessage,
                ]);
            }

            // Jika tidak ada pencarian, tampilkan semua absensi untuk kelas
            $absen_siswa = Absen_siswa::with('data_siswa')
                ->whereHas('data_siswa', function ($query) use ($kelasId) {
                    $query->where('kelas_id', $kelasId);
                })
                ->get();

            return view('siswa.absen_siswa.index', [
                'absen_siswa' => $absen_siswa,
                'title' => $title,
                'searchMessage' => $searchMessage,
            ]);
        }

        // Untuk user non-Perwakilan Kelas, tampilkan seluruh data absensi
        $absen_siswa = Absen_siswa::with('data_siswa')->get();
        $title = 'Absensi Siswa'; // Judul default untuk non-Perwakilan Kelas

        return view('siswa.absen_siswa.index', [
            'absen_siswa' => $absen_siswa,
            'title' => $title,
            'searchMessage' => $searchMessage,
        ]);
    }

    public function create()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login

        if ($user->role == 'Perwakilan Kelas') {
            $data_siswa = Data_siswa::where('kelas_id', $user->kelas_id)->get();
            $kelas = Kelas::findOrFail($user->kelas_id); // Mendapatkan kelas untuk title
        } else {
            $data_siswa = Data_siswa::all();
            $kelas = null;
        }

        $title = $kelas ? 'Tambah Absensi Siswa ' . $kelas->kelas_id : 'Tambah Absensi Siswa';

        return view('siswa.absen_siswa.create', compact('data_siswa', 'kelas'), ['title' => $title]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'tgl' => 'required|date',
            'siswa' => 'required|array',
            'siswa.*.keterangan' => 'nullable', // Allow keterangan to be nullable
        ]);

        // Loop melalui setiap siswa dari input form
        foreach ($request->siswa as $siswa_id => $data) {
            // Ambil data siswa berdasarkan ID siswa
            $siswa = Data_siswa::findOrFail($siswa_id); // Cari siswa berdasarkan ID

            // Simpan absen siswa dengan nama siswa, kelas_id, dan nisn_id
            Absen_siswa::create([
                'tgl' => $request->tgl,
                'nama_siswa' => $siswa->nama_siswa, // Simpan nama siswa dari model Data_siswa
                'keterangan' => $data['keterangan'] ?? null, // Allow nullable keterangan
                'kelas_id' => $siswa->kelas_id, // Simpan kelas_id dari model Data_siswa
                'nisn_id' => $siswa->id, // Simpan nisn_id dari model Data_siswa (ID siswa)
            ]);
        }

        // Redirect kembali dengan pesan sukses
        return redirect('absen_siswa')->with('status', 'Data berhasil ditambah');
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
        // Mengambil data absensi siswa beserta data siswa terkait
        $absen_siswa = Absen_siswa::with('data_siswa')->findOrFail($id);

        // Mendapatkan `kelas_id` dari data siswa terkait
        $kelas = Kelas::findOrFail($absen_siswa->data_siswa->kelas_id);

        // Menyertakan `kelas_id` di dalam title
        $title = 'Edit Absensi Siswa ' . $kelas->kelas_id;

        // Mengirim data absensi dan kelas ke view
        return view('siswa.absen_siswa.edit', compact('absen_siswa', 'kelas'), ['title' => $title]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the incoming request
        $request->validate([
            'tgl' => 'required|date',   // Ensure the date is valid
            'keterangan' => 'nullable', // Allow keterangan to be nullable
        ]);

        // Find the attendance record (absen_siswa) by its ID
        $absen_siswa = Absen_siswa::findOrFail($id);

        // Update the attendance record
        $absen_siswa->tgl = $request->tgl; // Update the date
        $absen_siswa->keterangan = $request->keterangan; // Allow nullable keterangan
        $absen_siswa->save(); // Save the changes

        // Redirect with a success message
        return redirect()->route('absen_siswa.index')->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absen_siswa = Absen_siswa::findOrFail($id);
        $absen_siswa->delete();

        return redirect('absen_siswa')->with('status', 'Data berhasil dihapus');
    }
}
