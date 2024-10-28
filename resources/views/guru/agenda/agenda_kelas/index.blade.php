<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-end mb-6">
            <a href="{{ url('agenda/create/' . $kelas->id) }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah Agenda</a>
        </div>

        @if($agenda->isEmpty())
            <p class="text-center mt-4">Tidak ada agenda untuk kelas ini.</p>
        @else
            <!-- Membuat tabel dengan fixed header dan scrollable body -->
            <div class="overflow-y-auto max-h-80">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                    <thead class="sticky top-0 bg-green-500 text-white">
                        <tr class="text-center">
                            <th class="py-3 px-6">No</th>
                            <th class="py-3 px-6">Tanggal</th>
                            <th class="py-3 px06">Guru</th>
                            <th class="py-3 px-6">Mapel</th>
                            <th class="py-3 px-6">Aktivitas</th>
                            <th class="py-3 px-6">Jam Masuk</th>
                            <th class="py-3 px-6">Jam Keluar</th>
                            <th class="py-3 px-6">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($agenda as $item)
                            <tr class="border-t border-gray-200 hover:bg-gray-100 text-center">
                                <td class="py-3 px-6">{{ $loop->iteration }}</td>
                                <td class="py-3 px-6">{{ $item->tgl }}</td>
                                <td class="px-4 py-2 text-left">
                                    {{ $item->mapel->data_guru->nama_guru ?? 'Guru tidak ditemukan' }}
                                </td>
                                <td class="py-3 px-6">{{ $item->mapel->nama_mapel }}</td>
                                <td class="py-3 px-6">{{ $item->aktivitas }}</td>
                                <td class="py-3 px-6">{{ $item->jam_msk }}</td>
                                <td class="py-3 px-6">{{ $item->jam_keluar }}</td>
                                <td class="py-3 px-6 flex justify-center space-x-2">
                                    <a href="{{ url('agenda/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Edit</a>
                                    <form action="{{ url('agenda/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</x-layout>
