<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <!-- Form Pencarian -->
            <form action="{{ url('data_siswa') }}" method="GET" id="searchForm" class="flex space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari siswa..." class="py-2 px-4 border border-gray-300 rounded">
                <select name="filterKelas" id="filterKelas" onchange="document.getElementById('searchForm').submit();" class="py-2 px-4 border border-gray-300 rounded">
                    <option value="">Kelas</option>
                    @foreach ($kelas as $item)
                        <option value="{{ $item->id }}" {{ request('filterKelas') == $item->id ? 'selected' : '' }}>
                            {{ $item->kelas_id }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Cari</button>
            </form>
            <a href="{{ url('data_siswa/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah data</a>
        </div>

        @if($data_siswa->isEmpty())
            <p class="text-center mt-4">Data siswa belum ditambahkan.</p>
        @else

        <!-- Tabel data siswa -->
        <div class="overflow-y-auto max-h-80">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
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
                    @foreach ($data_siswa as $index => $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center">
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
                            <a href="{{ url('data_siswa/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ url('data_siswa/' . $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
