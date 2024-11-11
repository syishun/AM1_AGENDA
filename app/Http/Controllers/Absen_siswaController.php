<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
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
        $user = Auth::user(); // Get the logged-in user
        $search = $request->get('search');
        $filterDate = $request->get('date'); // Get date filter
        $searchMessage = null;

        if ($user->role == 'Perwakilan Kelas') {
            // Logic khusus untuk perwakilan kelas
            $kelasId = $user->kelas_id;
            $kelas = Kelas::findOrFail($kelasId);
            $title = 'Absensi Siswa Kelas ' . $kelas->kelas_id;

            if ($search) {
                // Mencari semua siswa dalam kelas yang namanya mengandung string pencarian
                $data_siswa = Data_siswa::where('kelas_id', $kelasId)
                    ->where('nama_siswa', 'LIKE', "%{$search}%")
                    ->pluck('id'); // Mengambil ID dari semua siswa yang sesuai

                if ($data_siswa->isEmpty()) {
                    $searchMessage = 'Siswa tidak berada di kelas ini.';
                    return view('siswa.absen_siswa.index', compact('title', 'searchMessage'))->with('absen_siswa', collect());
                }

                // Mendapatkan data absensi untuk semua siswa yang sesuai dengan pencarian
                $absen_siswa = Absen_siswa::with('data_siswa')
                    ->whereIn('nisn_id', $data_siswa)
                    ->when($filterDate, fn($query) => $query->whereDate('tgl', $filterDate))
                    ->orderBy('tgl', 'desc')
                    ->get()
                    ->groupBy('tgl');

                if ($absen_siswa->isEmpty()) $searchMessage = 'Siswa ini belum pernah absen.';

                return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
            }

            $absen_siswa = Absen_siswa::with('data_siswa')
                ->whereHas('data_siswa', fn($query) => $query->where('kelas_id', $kelasId))
                ->when($filterDate, fn($query) => $query->whereDate('tgl', $filterDate))
                ->orderBy('tgl', 'desc')
                ->get()
                ->groupBy('tgl');

            return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
        }

        // Logic untuk Admin: bisa cari siswa atau kelas
        $absen_siswa = Absen_siswa::with(['data_siswa', 'kelas'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('data_siswa', fn($query) => $query->where('nama_siswa', 'LIKE', "%{$search}%"))
                    ->orWhereHas('kelas', fn($query) => $query->where('kelas_id', 'LIKE', "%{$search}%"));
            })
            ->when($filterDate, fn($query) => $query->whereDate('tgl', $filterDate))
            ->orderBy('tgl', 'desc')
            ->get()
            ->groupBy('tgl');

        $title = 'Absensi Siswa';

        return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
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
        // Dapatkan tanggal hari ini
        $today = Carbon::today()->toDateString();

        // Validasi input
        $request->validate([
            'tgl' => [
                'required',
                'date',
                function ($attribute, $value, $fail) use ($today) {
                    if ($value !== $today) {
                        $fail('Tanggal hanya boleh diisi dengan hari ini.');
                    }
                },
            ],
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
            'keterangan' => 'nullable', // Allow keterangan to be nullable
        ]);

        // Find the attendance record (absen_siswa) by its ID
        $absen_siswa = Absen_siswa::findOrFail($id);

        // Update the attendance record
        //$absen_siswa->tgl = $request->tgl; // Update the date
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
