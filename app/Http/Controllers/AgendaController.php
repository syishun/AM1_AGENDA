<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Agenda;
use App\Models\Kelas;
use App\Models\Mapel;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agenda = Agenda::all();
        $kelas = Kelas::all();
        return view('guru.agenda.index', compact('agenda', 'kelas'), ['title' => 'Agenda Pembelajaran Harian']);
    }

    public function agendaByClass(Request $request, $id)
    {
        // Fetch class details
        $kelas = Kelas::find($id);

        // Check for date filter
        $filterDate = $request->query('date');

        // Query agenda for the class, optionally filtering by date and kode_guru for teachers
        $agendaQuery = Agenda::where('kelas_id', $id)->orderBy('tgl', 'desc');

        if ($filterDate) {
            $agendaQuery->whereDate('tgl', $filterDate);
        }

        // Filter agenda by kode_guru if the user is a teacher
        if (auth()->user()->role == 'Guru') {
            $kodeGuru = auth()->user()->kode_guru;
            $agendaQuery->whereHas('mapel', function ($query) use ($kodeGuru) {
                $query->where('kode_guru', $kodeGuru);
            });
        }

        $agenda = $agendaQuery->get();

        return view('guru.agenda.agenda_kelas.index', compact('agenda', 'kelas', 'filterDate'), ['title' => 'Agenda Harian Kelas ' . $kelas->kelas_id]);
    }

    /**
     * Show the form for creating a new resource.
     */
    // Ubah fungsi create agar menerima $kelas_id dari route.
    public function create($kelas_id)
    {
        $userKodeGuru = auth()->user()->kode_guru; // Ambil kode_guru dari user yang sedang login
        $mapel = Mapel::where('kode_guru', $userKodeGuru)->get(); // Ambil mapel yang diajarkan oleh guru ini
        $kelas = Kelas::findOrFail($kelas_id);

        return view('guru.agenda.agenda_kelas.create', compact('mapel', 'kelas_id'), ['title' => 'Tambah Agenda Harian Kelas ' . $kelas->kelas_id]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tgl' => ['required', function ($attribute, $value, $fail) {
                if ($value !== Carbon::today()->toDateString()) {
                    $fail('Tanggal harus diisi dengan tanggal hari ini.');
                }
            }],
            'mapel_id' => 'required',
            'aktivitas' => 'required',
            'jam_msk' => 'required',
            'jam_keluar' => 'required',
        ]);

        $add = new Agenda;
        $add->tgl = $request->tgl;
        $add->kelas_id = $request->kelas_id; // Kelas akan disimpan langsung dari parameter request
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
        $userKodeGuru = auth()->user()->kode_guru; // Ambil kode_guru dari user yang sedang login
        $mapel = Mapel::where('kode_guru', $userKodeGuru)->get(); // Ambil mapel yang diajarkan oleh guru ini
        $kelas = Kelas::findOrFail($agenda->kelas_id);  // Ambil data kelas
        $kelas_id = $kelas->id;

        return view('guru.agenda.agenda_kelas.edit', compact('agenda', 'kelas_id', 'mapel'), ['title' => 'Edit Agenda Harian Kelas ' . $kelas->kelas_id]);
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
