<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="flex flex-col md:flex-row md:justify-between items-center mb-6 p-4 rounded-lg">
        <!-- Form Pencarian Guru -->
        <form method="GET" action="{{ url('data_guru') }}" class="flex flex-col md:flex-row md:space-x-4 w-full md:w-auto mb-4 md:mb-0">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama guru..." class="py-2 px-4 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-green-500 transition duration-200 w-full md:w-64">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded transition duration-200 mt-2 md:mt-0">Cari</button>
        </form>    

        <a href="{{ url('data_guru/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Tambah Data</a>
    </div>

    @if(isset($search) && $data_guru->isEmpty())
        <p class="text-center mt-4">Tidak ada guru yang cocok dengan pencarian "{{ $search }}".</p>
    @elseif($data_guru->isEmpty())
        <p class="text-center mt-4">Data guru belum ditambahkan.</p>
    @else
        <!-- Tabel data guru dengan desain konsisten -->
        <div class="overflow-x-auto mt-4">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                <thead class="sticky top-0 bg-green-500 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-6">Nama Guru</th>
                        <th class="py-3 px-6">Kode Guru</th>
                        <th class="py-3 px-6">Gender</th>
                        <th class="py-3 px-6">Mata Pelajaran</th>
                        <th class="py-3 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data_guru as $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center transition duration-200">
                        <td class="py-3 px-6 text-left">{{ $item['nama_guru'] }}</td>
                        <td class="py-3 px-6">{{ $item['kode_guru'] }}</td>
                        <td class="py-3 px-6">
                            @if ($item['gender'] == 'Pria')
                                <span class="text-blue-500 text-3xl">&#9794;</span>
                            @else
                                <span class="text-pink-500 text-3xl">&#9792;</span>
                            @endif
                        </td>
                        <td class="py-3 px-6">
                            @foreach ($item['mapels'] as $mapel)
                                {{ $mapel['nama_mapel'] }}{{ !$loop->last ? ',' : '' }}<br>
                            @endforeach
                        </td>                        
                        <td class="py-3 px-6 flex justify-center space-x-2">
                            <a href="{{ url('data_guru/' . $item['id'] . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600 transition duration-200">Edit</a>
                            <form action="{{ url('data_guru/' . $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
