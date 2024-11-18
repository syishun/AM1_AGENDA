<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Agenda;
use App\Models\Kelas;
use App\Models\Mapel;
use DB;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        // Tentukan awal dan akhir tahun ajaran
        if ($currentMonth >= 7) { // Juli hingga Desember
            $startYear = $currentYear;
            $endYear = $currentYear + 1;
        } else { // Januari hingga Juni
            $startYear = $currentYear - 1;
            $endYear = $currentYear;
        }

        $currentAcademicYear = "$startYear/$endYear";

        // Periksa apakah admin ingin melihat semua data
        $showAll = $request->query('show_all', false);

        if (auth()->user()->role === 'Admin' && $showAll) {
            // Admin dapat melihat semua data
            $kelas = Kelas::with('jurusan')->get();
        } else {
            // Guru atau admin default melihat data tahun ajaran berjalan
            $kelas = Kelas::with('jurusan')
                ->where('thn_ajaran', $currentAcademicYear)
                ->get();
        }

        return view('guru.agenda.index', compact('kelas'), ['title' => 'Daftar Kelas']);
    }

    public function agendaByClass(Request $request, $id)
    {
        $kelas = Kelas::findOrFail($id);
        $filterDate = $request->query('date') ?? Carbon::today()->toDateString();

        $agendaQuery = Agenda::where('kelas_id', $id)->orderBy('tgl', 'desc');

        // Filter berdasarkan tanggal jika diperlukan
        if ($filterDate) {
            $agendaQuery->whereDate('tgl', $filterDate);
        }

        // Jika user adalah Guru, terapkan filter berdasarkan `kode_guru`
        if (auth()->user()->role === 'Guru') {
            $kode_guru = auth()->user()->kode_guru;
            $assignedMapels = DB::table('guru_mapel')
                ->where('data_guru_id', $kode_guru)
                ->pluck('mapel_id');

            $agendaQuery->whereIn('mapel_id', $assignedMapels);
        }

        $agenda = $agendaQuery->get();

        return view('guru.agenda.agenda_kelas.index', compact('agenda', 'kelas', 'filterDate'), [
            'title' => 'Agenda Harian Kelas ' . $kelas->kelas . ' ' . $kelas->jurusan->jurusan_id . ' ' . $kelas->kelas_id
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // Ubah fungsi create agar menerima $kelas_id dari route.
    public function create($kelas_id)
    {
        $kode_guru = auth()->user()->kode_guru;
        $assignedMapels = DB::table('guru_mapel')
            ->where('data_guru_id', $kode_guru)
            ->pluck('mapel_id');

        // Retrieve subjects based on the IDs from `guru_mapel`
        $mapel = Mapel::whereIn('id', $assignedMapels)->get();

        $kelas = Kelas::findOrFail($kelas_id);

        return view('guru.agenda.agenda_kelas.create', [
            'kelas_id' => $kelas_id,
            'mapel' => $mapel
        ], ['title' => 'Tambah Agenda Harian Kelas ' . $kelas->kelas . ' ' . $kelas->jurusan->jurusan_id . ' ' . $kelas->kelas_id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl' => [
                'required',
                function ($attribute, $value, $fail) {
                    $date = Carbon::parse($value);
                    if ($date->dayOfWeek == Carbon::SATURDAY || $date->dayOfWeek == Carbon::SUNDAY) {
                        $fail('Tidak dapat menambahkan data pada hari Sabtu atau Minggu.');
                    }
                    if ($value !== Carbon::today()->toDateString()) {
                        $fail('Tanggal harus diisi dengan tanggal hari ini.');
                    }
                },
            ],
            'mapel_id' => 'required',
            'aktivitas' => 'required',
            'jam_msk' => 'required',
            'jam_keluar' => 'required',
        ]);

        $add = new Agenda;
        $add->tgl = $request->tgl;
        $add->kelas_id = $request->kelas_id;
        $add->mapel_id = $request->mapel_id;
        $add->aktivitas = $request->aktivitas;
        $add->jam_msk = $request->jam_msk;
        $add->jam_keluar = $request->jam_keluar;
        $add->save();

        return redirect('agenda/kelas/' . $request->kelas_id)->with('status', 'Data berhasil ditambah');
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
        $agenda = Agenda::findOrFail($id);
        $kode_guru = auth()->user()->kode_guru;
        $assignedMapels = DB::table('guru_mapel')
            ->where('data_guru_id', $kode_guru)
            ->pluck('mapel_id');

        // Retrieve subjects based on the IDs from `guru_mapel`
        $mapel = Mapel::whereIn('id', $assignedMapels)->get();
        $kelas = Kelas::findOrFail($agenda->kelas_id);
        $kelas_id = $kelas->id;

        return view('guru.agenda.agenda_kelas.edit', compact('agenda', 'kelas_id', 'mapel'), [
            'title' => 'Edit Agenda Harian Kelas ' . $kelas->kelas . ' ' . $kelas->jurusan->jurusan_id . ' ' . $kelas->kelas_id
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'mapel_id' => 'required',
            'aktivitas' => 'required',
            'jam_msk' => 'required',
            'jam_keluar' => 'required',
        ]);

        $agenda = Agenda::findOrFail($id);

        // Ensure that 'kelas_id' is included in the request
        $agenda->kelas_id = $request->kelas_id;  // Check if 'kelas_id' is null here
        // $agenda->tgl = $request->tgl; // Komentar atau hapus baris ini untuk menjaga tanggal tetap
        $agenda->mapel_id = $request->mapel_id;
        $agenda->aktivitas = $request->aktivitas;
        $agenda->jam_msk = $request->jam_msk;
        $agenda->jam_keluar = $request->jam_keluar;

        $agenda->save();

        return redirect('agenda/kelas/' . $request->kelas_id)->with('status', 'Data berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $agenda = Agenda::findOrFail($id);
        $kelas_id = $agenda->kelas_id;
        $agenda->delete();

        return redirect('agenda/kelas/' . $kelas_id)->with('status', 'Data berhasil dihapus');
    }
}
