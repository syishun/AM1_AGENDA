<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6 p-4 rounded-lg">
        <!-- Form Pencarian Kelas -->
        <form action="{{ url('kelas') }}" method="GET" id="filterForm" class="flex flex-col md:flex-row md:space-x-4 w-full md:w-auto mb-4 md:mb-0">
            <select name="filterKelas" id="filterKelas" onchange="document.getElementById('filterForm').submit();" class="py-2 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
                <option value="">Kelas</option>
                <option value="X" {{ request('filterKelas') == 'X' ? 'selected' : '' }}>X</option>
                <option value="XI" {{ request('filterKelas') == 'XI' ? 'selected' : '' }}>XI</option>
                <option value="XII" {{ request('filterKelas') == 'XII' ? 'selected' : '' }}>XII</option>
            </select>
        </form>
        <a href="{{ url('kelas/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Tambah Data</a>
    </div>

    @if($kelas->isEmpty())
        <p class="text-center mt-4">Data kelas belum ditambahkan.</p>
    @else
        <!-- Tabel data kelas dengan tampilan yang konsisten -->
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="sticky top-0 bg-green-500 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-6">No</th>
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6">ID Kelas</th>
                        <th class="py-3 px-6">Angkatan</th>
                        <th class="py-3 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kelas as $index => $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center transition duration-200">
                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6">{{ $item->kelas }}</td>
                        <td class="py-3 px-6">{{ $item->kelas_id }}</td>
                        <td class="py-3 px-6">{{ $item->angkatan }}</td>
                        <td class="py-3 px-6 flex justify-center space-x-2">
                            <a href="{{ url('kelas/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600 transition duration-200">Edit</a>
                            <form action="{{ url('kelas/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-3 rounded hover:bg-red-600 transition duration-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</x-layout>
