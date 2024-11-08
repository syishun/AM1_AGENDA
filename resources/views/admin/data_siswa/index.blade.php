<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6 p-4 rounded-lg">
        <!-- Form Pencarian -->
        <form action="{{ url('data_siswa') }}" method="GET" id="searchForm" class="flex flex-col md:flex-row md:space-x-4 w-full md:w-auto mb-4 md:mb-0">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="py-2 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200">
            <select name="filterKelas" id="filterKelas" onchange="document.getElementById('searchForm').submit();" class="py-2 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 mt-2 md:mt-0">
                <option value="">Kelas</option>
                @foreach ($kelas as $item)
                    <option value="{{ $item->id }}" {{ request('filterKelas') == $item->id ? 'selected' : '' }}>
                        {{ $item->kelas_id }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded mt-2 md:mt-0 transition duration-200">Cari</button>
        </form>
        <a href="{{ url('data_siswa/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Tambah Data</a>
    </div>

    @if($data_siswa->isEmpty())
        <p class="text-center mt-4">Data siswa belum ditambahkan.</p>
    @else

    <!-- Tabel data siswa dengan scroll hanya untuk desktop -->
    <div class="overflow-x-auto mt-4">
        <div class="max-h-96 md:overflow-y-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="sticky top-0 bg-green-500 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-6">No</th>
                        <th class="py-3 px-6">Nama Siswa</th>
                        <th class="py-3 px-6">NISN</th>
                        <th class="py-3 px-6">Gender</th>
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_siswa as $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center transition duration-200">
                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 text-left">{{ $item->nama_siswa }}</td>
                        <td class="py-3 px-6">{{ $item->nisn_id }}</td>
                        <td class="py-3 px-6">
                            @if ($item->gender == 'Pria')
                                <span class="text-blue-500 text-3xl">&#9794;</span>
                            @else
                                <span class="text-pink-500 text-3xl">&#9792;</span>
                            @endif
                        </td>
                        <td class="py-3 px-6">{{ $item->kelas->kelas_id }}</td>
                        <td class="py-3 px-6 flex justify-center space-x-2">
                            <a href="{{ url('data_siswa/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600 transition duration-200">Edit</a>
                            <form action="{{ url('data_siswa/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
    </div>
    @endif
</x-layout>
