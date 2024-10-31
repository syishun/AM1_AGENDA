<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

        // Fetch agenda items for the class, optionally filtering by date and ordering by the latest date
        $agendaQuery = Agenda::where('kelas_id', $id)
            ->orderBy('tgl', 'desc');

        if ($filterDate) {
            $agendaQuery->whereDate('tgl', $filterDate);
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
        $mapel = Mapel::all();
        $kelas = Kelas::findOrFail($kelas_id);
        return view('guru.agenda.agenda_kelas.create', compact('mapel', 'kelas_id'), ['title' => 'Tambah Agenda Harian Kelas ' . $kelas->kelas_id]);
    }

    // Ubah fungsi store agar tidak perlu menerima kelas_id dari input.
    public function store(Request $request)
    {
        $request->validate(
            [
                'tgl' => 'required',
                'mapel_id' => 'required',
                'aktivitas' => 'required',
                'jam_msk' => 'required',
                'jam_keluar' => 'required',
            ]
        );

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
        $mapel = Mapel::all();
        $kelas = Kelas::findOrFail($agenda->kelas_id);  // Fetch the full Kelas object
        $kelas_id = $kelas->id;
        return view('guru.agenda.agenda_kelas.edit', compact('agenda', 'kelas_id', 'mapel'), ['title' => 'Edit Agenda Harian Kelas ' . $kelas->kelas_id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tgl' => 'required',
            'mapel_id' => 'required',
            'aktivitas' => 'required',
            'jam_msk' => 'required',
            'jam_keluar' => 'required',
        ]);

        $agenda = Agenda::findOrFail($id);

        // Ensure that 'kelas_id' is included in the request
        $agenda->kelas_id = $request->kelas_id;  // Check if 'kelas_id' is null here
        $agenda->tgl = $request->tgl;
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
