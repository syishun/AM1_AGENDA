<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absen_guru;
use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\User;
use App\Notifications\YourCustomNotification;
use App\Events\AbsenGuruSaved;

class Absen_guruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $absen_guru = Absen_guru::all();
        $kelas = Kelas::all();
        return view('guru.absen_guru.index', compact('absen_guru', 'kelas'), ['title' => 'Absensi Guru']);
    }

    public function absen_guruByClass(Request $request, $id)
    {
        $kelas = Kelas::find($id);

        // Retrieve the user's role
        $userRole = auth()->user()->role;

        // Set the title based on the user's role
        $title = ($userRole === 'Guru' || $userRole === 'Admin') ? 'Absensi' : 'Tugas';

        // Check for date filter
        $filterDate = $request->query('date');

        // Fetch attendance records for the class, optionally filtering by date and ordering by the latest date
        $absenGuruQuery = Absen_guru::where('kelas_id', $id)
            ->orderBy('tgl', 'desc');

        if ($filterDate) {
            $absenGuruQuery->whereDate('tgl', $filterDate);
        }

        $absen_guru = $absenGuruQuery->get();

        return view('guru.absen_guru.absen_guru_kelas.index', compact('absen_guru', 'kelas', 'filterDate'), ['title' => $title . ' di Kelas ' . $kelas->kelas_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($kelas_id)
    {
        $absen_guru = Absen_guru::all();
        $mapel = Mapel::all();
        $kelas = Kelas::findOrFail($kelas_id);
        return view('guru.absen_guru.absen_guru_kelas.create', compact('absen_guru', 'mapel', 'kelas_id'), ['title' => 'Tambah Absensi di Kelas ' . $kelas->kelas_id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi form input
        $request->validate([
            'mapel_id' => 'required',
            'tgl' => 'required',
            'keterangan' => 'required',
            'tugas.*' => 'nullable|mimes:pdf|max:15360', // Validasi untuk setiap file tugas
        ]);

        $absen_guru = new Absen_guru;
        $absen_guru->mapel_id = $request->mapel_id;
        $absen_guru->tgl = $request->tgl;
        $absen_guru->kelas_id = $request->kelas_id;
        $absen_guru->keterangan = $request->keterangan;

        // Simpan semua file tugas jika ada
        $tugasFiles = [];
        if ($request->hasFile('tugas')) {
            foreach ($request->file('tugas') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/tugas', $fileName, 'public');
                $tugasFiles[] = $filePath;
            }
        }

        // Simpan array file path sebagai JSON
        $absen_guru->tugas = json_encode($tugasFiles);

        $absen_guru->save();

        // Data notifikasi
        $data = [
            'title' => 'Absensi Terbaru',
            'message' => 'Absensi baru telah ditambahkan untuk kelas Anda.',
            'link' => url('/absen_guru/kelas/' . $request->kelas_id),
        ];

        // Kirim notifikasi ke perwakilan kelas
        $users = User::where('role', 'Perwakilan Kelas')
            ->where('kelas_id', $request->kelas_id)
            ->get();

        foreach ($users as $user) {
            $user->notify(new YourCustomNotification($data));
        }

        return redirect('absen_guru/kelas/' . $request->kelas_id)->with('status', 'Data berhasil ditambah');
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
        $absen_guru = Absen_guru::findOrFail($id);
        $mapel = Mapel::all();
        $kelas = Kelas::findOrFail($absen_guru->kelas_id);
        $kelas_id =  $kelas->id;
        return view('guru.absen_guru.absen_guru_kelas.edit', compact('absen_guru', 'mapel', 'kelas_id'), ['title' => 'Edit Absensi di Kelas ' . $kelas->kelas_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'mapel_id' => 'required',
            'tgl' => 'required',
            'keterangan' => 'required',
            'tugas.*' => 'nullable|mimes:pdf|max:15360', // Validasi untuk multiple files
        ]);

        $absen_guru = Absen_guru::findOrFail($id);

        $absen_guru->mapel_id = $request->mapel_id;
        $absen_guru->tgl = $request->tgl;
        $absen_guru->kelas_id = $request->kelas_id;
        $absen_guru->keterangan = $request->keterangan;

        // Ambil tugas yang ada jika ada, decode menjadi array
        $existingTugas = $absen_guru->tugas ? json_decode($absen_guru->tugas, true) : [];

        // Proses setiap file yang diunggah
        if ($request->hasFile('tugas')) {
            foreach ($request->file('tugas') as $file) {
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('uploads/tugas', $fileName, 'public');
                $existingTugas[] = $filePath; // Tambahkan file baru ke dalam array existingTugas
            }
        }

        // Update kolom tugas dengan semua file yang ada dalam bentuk JSON
        $absen_guru->tugas = json_encode($existingTugas);

        $absen_guru->save();
        return redirect('absen_guru/kelas/' . $request->kelas_id)->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $absen_guru = Absen_guru::findOrFail($id);
        $kelas_id = $absen_guru->kelas_id;
        $absen_guru->delete();

        return redirect('absen_guru/kelas/' . $kelas_id)->with('status', 'Data berhasil dihapus');
    }
}
