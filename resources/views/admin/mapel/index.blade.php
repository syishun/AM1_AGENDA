<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-end mb-6">
            <a href="{{ url('mapel/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah data</a>
        </div>

    @if($mapelSorted->isEmpty())
        <p class="text-center mt-4">Mata pelajaran belum ditambahkan.</p>
    @else

        <div class="overflow-y-auto max-h-80">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                <thead class="sticky top-0 bg-green-500 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-6">Nama Guru</th>
                        <th class="py-3 px-6">Kode Guru</th>
                        <th class="py-3 px-6">Nama Mapel</th>
                        <th class="py-3 px-6">ID Mapel</th>
                        <th class="py-3 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mapelSorted as $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center">
                        <td class="py-3 px-6 text-left">{{ $item['data_guru']['nama_guru'] }}</td>
                        <td class="py-3 px-6">{{ $item['data_guru']['kode_guru'] }}</td>
                        <td class="py-3 px-6">{{ $item['nama_mapel'] }}</td>
                        <td class="py-3 px-6">{{ $item['mapel_id'] }}</td>
                        <td class="py-3 px-6 flex justify-center space-x-2">
                            <a href="{{ url('mapel/' . $item['id'] . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ url('mapel/' . $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
