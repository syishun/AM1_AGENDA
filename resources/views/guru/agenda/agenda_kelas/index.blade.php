<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <!-- Action Buttons Section -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 p-4">
        @if(Auth::user()->role == 'Guru')
            <a href="{{ url('agenda/create/' . $kelas->id) }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200 mb-2 md:mb-0">
                Tambah Agenda
            </a>
        @endif

        <!-- Date Filter Form -->
        <form method="GET" action="{{ url('agenda/kelas/' . $kelas->id) }}" class="flex items-center space-x-2">
            <input type="date" id="date" name="date" value="{{ $filterDate ?? '' }}" class="py-2 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
            <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">
                Filter
            </button>
            <a href="{{ url('agenda/kelas/' . $kelas->id) }}" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600 transition duration-200">
                Reset
            </a>
        </form>
    </div>

    @if($agenda->isEmpty())
        <p class="text-center mt-4">Tidak ada agenda untuk kelas ini.</p>
    @else
        @php
            $groupedAgendas = $agenda->groupBy('tgl');
        @endphp

        <!-- Displaying Agenda Items Grouped by Date -->
        @foreach ($groupedAgendas as $date => $agendas)
            <h2 class="text-xl font-semibold mb-2 mt-4 text-green-600">{{ \Carbon\Carbon::parse($date)->format('d M Y') }}</h2>

            <div class="overflow-y-auto max-h-80">
                <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                    <thead class="sticky top-0 bg-green-500 text-white">
                        <tr class="text-center">
                            <th class="py-3 px-6">No</th>
                            <th class="py-3 px-6">Guru</th>
                            <th class="py-3 px-6">Mapel</th>
                            <th class="py-3 px-6">Aktivitas</th>
                            <th class="py-3 px-6">Jam Masuk</th>
                            <th class="py-3 px-6">Jam Keluar</th>
                            @if(Auth::user()->role == 'Guru')
                            <th class="py-3 px-6">Aksi</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($agendas as $item)
                            <tr class="border-t border-gray-200 hover:bg-gray-100 text-center">
                                <td class="py-3 px-6">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-left">{{ $item->mapel->data_guru->nama_guru ?? 'Guru tidak ditemukan' }}</td>
                                <td class="py-3 px-6">{{ $item->mapel->nama_mapel }}</td>
                                <td class="py-3 px-6">{{ $item->aktivitas }}</td>
                                <td class="py-3 px-6">{{ $item->jam_msk }}</td>
                                <td class="py-3 px-6">{{ $item->jam_keluar }}</td>
                                @if(Auth::user()->role == 'Guru')
                                <td class="py-3 px-6 flex justify-center space-x-2">
                                    <a href="{{ url('agenda/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600 transition duration-200">Edit</a>
                                    <form action="{{ url('agenda/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition duration-200">Delete</button>
                                    </form>
                                </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</x-layout>
