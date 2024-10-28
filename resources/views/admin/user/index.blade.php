<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg">
        <div class="flex justify-between items-center mb-6">
            <!-- Form Pencarian -->
            <form action="{{ url('user') }}" method="GET" id="filterForm" class="flex space-x-4">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari username..." class="py-2 px-4 border border-gray-300 rounded">
                <select name="filterRole" id="filterRole" onchange="document.getElementById('filterForm').submit();" class="py-2 px-4 border border-gray-300 rounded">
                    <option value="">Role</option>
                    <option value="Admin" {{ request('filterRole') == 'Admin' ? 'selected' : '' }}>Admin</option>
                    <option value="Guru" {{ request('filterRole') == 'Guru' ? 'selected' : '' }}>Guru</option>
                    <option value="Perwakilan Kelas" {{ request('filterRole') == 'Perwakilan Kelas' ? 'selected' : '' }}>Perwakilan Kelas</option>
                </select>
                <button type="submit" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded">Cari</button>
            </form>
            <a href="{{ url('user/create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Tambah data</a>
        </div>

        @if($user->isEmpty())
            <p class="text-center mt-4">Data pengguna belum ditambahkan.</p>
        @else

        <!-- Tabel data pengguna -->
        <div class="overflow-y-auto max-h-80">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg table-auto">
                <thead class="sticky top-0 bg-green-500 text-white">
                    <tr class="text-center">
                        <th class="py-3 px-6">No</th>
                        <th class="py-3 px-6">Username</th>
                        <th class="py-3 px-6">Role</th>
                        <th class="py-3 px-6">Kelas</th>
                        <th class="py-3 px-6">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($user as $item)
                    <tr class="border-t border-gray-200 hover:bg-gray-100 text-center">
                        <td class="py-3 px-6">{{ $loop->iteration }}</td>
                        <td class="py-3 px-6 text-left">{{ $item->name }}</td> <!-- Menampilkan name (username) -->
                        <td class="py-3 px-6">{{ $item->role }}</td>
                        <td class="py-3 px-6">
                            @if($item->kelas)
                                {{ $item->kelas->kelas_id }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="py-3 px-6 flex justify-center space-x-2">
                            <a href="{{ url('user/' . $item['id'] . '/edit') }}" class="bg-yellow-500 text-white py-1 px-3 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ url('user/' . $item['id']) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
