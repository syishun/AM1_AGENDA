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
        $user = Auth::user();
        $search = $request->get('search');
        // Ambil tanggal dari filter atau gunakan tanggal hari ini
        $filterDate = $request->get('date') ?? Carbon::today()->format('Y-m-d');
        $searchMessage = null;

        if ($user->role == 'Perwakilan Kelas') {
            $kelasId = $user->kelas_id;
            $kelas = Kelas::findOrFail($kelasId);
            $title = 'Absensi Siswa Kelas ' . $kelas->kelas_id;

            if ($search) {
                $data_siswa = Data_siswa::where('kelas_id', $kelasId)
                    ->where('nama_siswa', 'LIKE', "%{$search}%")
                    ->pluck('id');

                if ($data_siswa->isEmpty()) {
                    $searchMessage = 'Siswa tidak berada di kelas ini.';
                    return view('siswa.absen_siswa.index', compact('title', 'searchMessage'))->with('absen_siswa', collect());
                }

                $absen_siswa = Absen_siswa::with('data_siswa')
                    ->whereIn('nis_id', $data_siswa)
                    ->whereDate('tgl', $filterDate)
                    ->orderBy('tgl', 'desc')
                    ->get()
                    ->groupBy('tgl');

                if ($absen_siswa->isEmpty()) $searchMessage = 'Siswa ini belum pernah absen.';

                return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
            }

            $absen_siswa = Absen_siswa::with('data_siswa')
                ->whereHas('data_siswa', fn($query) => $query->where('kelas_id', $kelasId))
                ->whereDate('tgl', $filterDate)
                ->orderBy('tgl', 'desc')
                ->get()
                ->groupBy('tgl');

            return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
        }

        $absen_siswa = Absen_siswa::with(['data_siswa', 'kelas'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('data_siswa', fn($query) => $query->where('nama_siswa', 'LIKE', "%{$search}%"))
                    ->orWhereHas('kelas', fn($query) => $query->where('kelas_id', 'LIKE', "%{$search}%"));
            })
            ->whereDate('tgl', $filterDate)
            ->orderBy('tgl', 'desc')
            ->get()
            ->groupBy('tgl');

        $title = 'Absensi Siswa';

        return view('siswa.absen_siswa.index', compact('absen_siswa', 'title', 'searchMessage', 'filterDate', 'search'));
    }

    public function create()
    {
        $user = Auth::user(); // Mendapatkan user yang sedang login

        if ($user->role == 'Sekretaris') {
            $data_siswa = Data_siswa::where('kelas_id', $user->kelas_id)->get();
            $kelas = Kelas::findOrFail($user->kelas_id); // Mendapatkan kelas untuk title
        } else {
            $data_siswa = Data_siswa::all();
            $kelas = null;
        }

        $title = $kelas ? 'Tambah Absensi Siswa ' . $kelas->kelas . ' ' . $kelas->jurusan->jurusan_id . ' ' . $kelas->kelas_id : 'Tambah Absensi Siswa';

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

        // Cek apakah sudah ada data absensi untuk tanggal ini dan kelas yang sama
        $existingRecord = Absen_siswa::where('tgl', $request->tgl)
            ->whereHas('data_siswa', function ($query) {
                $query->where('kelas_id', Auth::user()->kelas_id);
            })
            ->exists();

        if ($existingRecord) {
            // Jika data sudah ada, berikan pesan error dan hentikan proses
            return redirect()->back()->withErrors(['tgl' => 'Data absensi untuk tanggal ini sudah ada.']);
        }

        // Loop melalui setiap siswa dari input form
        foreach ($request->siswa as $siswa_id => $data) {
            // Ambil data siswa berdasarkan ID siswa
            $siswa = Data_siswa::findOrFail($siswa_id);

            // Tetapkan nilai default 'Hadir' jika keterangan tidak diisi
            $keterangan = $data['keterangan'] ?? 'Hadir';

            // Simpan absen siswa dengan nama siswa, kelas_id, dan nis_id
            Absen_siswa::create([
                'tgl' => $request->tgl,
                'nama_siswa' => $siswa->nama_siswa,
                'keterangan' => $keterangan,
                'kelas_id' => $siswa->kelas_id,
                'nis_id' => $siswa->id,
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
        $title = 'Edit Absensi Siswa ' . $kelas->kelas . ' ' . $kelas->jurusan->jurusan_id . ' ' . $kelas->kelas_id;

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
