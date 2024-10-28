<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="mx-auto my-6 p-4 bg-white shadow-lg rounded-lg">
        @if(Auth::user()->role == 'Guru')
        <div class="flex justify-end mb-6">
            <a href="{{ url('absen_guru/create/' . $kelas->id) }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah absensi</a>
        </div>
        @endif

        @if($absen_guru->isEmpty())
            <p class="text-center mt-4">Tidak ada absensi untuk kelas ini.</p>
        @else
        <div class="overflow-y-auto max-h-80">
            <table class="min-w-full bg-white border-collapse border border-gray-300 rounded-lg table-auto">
                <thead>
                    <tr class="bg-green-500 text-white">
                        <th class="px-4 py-2">No</th>
                        <th class="px-4 py-2">Guru</th>
                        <th class="px-4 py-2">Mapel</th>
                        <th class="px-4 py-2">Tanggal</th>
                        <th class="px-4 py-2">Keterangan</th>
                        <th class="px-4 py-2">Tugas</th>
                        @if(Auth::user()->role == 'Guru')
                        <th class="px-4 py-2">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absen_guru as $item)
                        <tr class="text-center border-t">
                            <td class="px-4 py-2">{{ $loop->iteration }}</td>
                            <td class="px-4 py-2 text-left">
                                {{ $item->mapel->data_guru->nama_guru ?? 'Guru tidak ditemukan' }}
                            </td>
                            <td class="px-4 py-2">{{$item->mapel->nama_mapel}}</td>
                            <td class="px-4 py-2">{{$item->tgl}}</td>
                            <td class="px-4 py-2">{{$item->keterangan}}</td>
                            <td class="px-4 py-2">
                                @if($item->tugas)
                                    <a href="{{ asset('storage/' . $item->tugas) }}" download class="text-blue-500 hover:underline">
                                        {{ \Illuminate\Support\Str::limit(basename($item->tugas), 20) }}
                                    </a>
                                @else
                                    -
                                @endif
                            </td>                
                            @if(Auth::user()->role == 'Guru')                                      
                            <td class="px-4 py-2">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ url('absen_guru/' . $item->id . '/edit') }}" class="bg-yellow-500 text-white px-3 py-1 rounded-md hover:bg-yellow-600">Edit</a>
                                    <form action="{{ url('absen_guru/' . $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-md hover:bg-red-600">Delete</button>
                                    </form>
                                </div>
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    
</x-layout>
