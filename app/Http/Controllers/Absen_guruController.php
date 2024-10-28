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

    public function absen_guruByClass($id)
    {
        $absen_guru = Absen_guru::where('kelas_id', $id)->get();
        $kelas = Kelas::find($id);

        // Ambil role pengguna yang sedang login
        $userRole = auth()->user()->role;

        // Tentukan title berdasarkan role pengguna
        $title = $userRole === 'Guru' ? 'Absensi' : 'Tugas';

        return view('guru.absen_guru.absen_guru_kelas.index', compact('absen_guru', 'kelas'), ['title' => $title . ' di Kelas ' . $kelas->kelas_id]);
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
        // Validasi dan penyimpanan absen
        $request->validate([
            'mapel_id' => 'required',
            'tgl' => 'required',
            'keterangan' => 'required',
            'tugas' => 'nullable|mimes:pdf|max:15360',
        ]);

        $absen_guru = new Absen_guru;
        $absen_guru->mapel_id = $request->mapel_id;
        $absen_guru->tgl = $request->tgl;
        $absen_guru->kelas_id = $request->kelas_id;
        $absen_guru->keterangan = $request->keterangan;

        if ($request->hasFile('tugas')) {
            $fileName = time() . '_' . $request->file('tugas')->getClientOriginalName();
            $filePath = $request->file('tugas')->storeAs('uploads/tugas', $fileName, 'public');
            $absen_guru->tugas = $filePath;
        }

        $absen_guru->save();

        // Siapkan data notifikasi
        $data = [
            'title' => 'Absensi Terbaru',
            'message' => 'Absensi baru telah ditambahkan untuk kelas Anda.',
            'link' => url('/absen_guru/kelas/' . $request->kelas_id),
        ];

        // Siapkan data notifikasi
        $kelas_id = $request->kelas_id; // Ambil kelas_id dari request untuk memastikan kelas benar
        $data = [
            'title' => 'Absensi Terbaru',
            'message' => 'Absensi baru telah ditambahkan untuk kelas Anda.',
            'link' => url('/absen_guru/kelas/' . $kelas_id), // Gunakan kelas_id yang benar di sini
        ];

        // Kirim notifikasi hanya ke perwakilan kelas dari kelas terkait
        $users = User::where('role', 'Perwakilan Kelas')
            ->where('kelas_id', $kelas_id) // Pastikan kelas_id sesuai
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
            'tugas' => 'nullable|mimes:pdf|max:15360',
        ]);

        $absen_guru = Absen_guru::findOrFail($id);

        $absen_guru->mapel_id = $request->mapel_id;
        $absen_guru->tgl = $request->tgl;
        $absen_guru->kelas_id = $request->kelas_id;
        $absen_guru->keterangan = $request->keterangan;

        if ($request->hasFile('tugas')) {
            $fileName = time() . '_' . $request->file('tugas')->getClientOriginalName();
            $filePath = $request->file('tugas')->storeAs('uploads/tugas', $fileName, 'public');
            $absen_guru->tugas = $filePath;
        }

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
