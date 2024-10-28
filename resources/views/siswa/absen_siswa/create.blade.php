<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto my-6 p-4 bg-white shadow-lg rounded-lg">
        <form action="{{ route('absen_siswa.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="tgl" class="block text-sm font-medium text-gray-700">Tanggal</label>
                <input type="date" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('tgl') border-red-500 @enderror" id="tgl" name="tgl" value="{{ old('tgl') }}" style="padding-left: 10px;">
                @error('tgl')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="overflow-y-auto max-h-80">
                <table class="min-w-full bg-white border-collapse border border-gray-300 rounded-lg table-auto">
                    <thead class="bg-green-500 text-white">
                        <tr>
                            <th class="px-4 py-2">No</th>
                            <th class="px-4 py-2">Nama Siswa</th>
                            <th class="px-4 py-2">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_siswa as $item)
                            <tr class="text-center border-t">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 text-left">{{ $item->nama_siswa }}</td>
                                <td class="px-4 py-2">
                                    <div class="flex justify-center space-x-4">
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-green-500" name="siswa[{{ $item->id }}][keterangan]" value="Sakit" onclick="toggleRadio(this)">
                                            <span class="ml-2">Sakit</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-green-500" name="siswa[{{ $item->id }}][keterangan]" value="Izin" onclick="toggleRadio(this)">
                                            <span class="ml-2">Izin</span>
                                        </label>
                                        <label class="inline-flex items-center">
                                            <input type="radio" class="form-radio text-green-500" name="siswa[{{ $item->id }}][keterangan]" value="Alpha" onclick="toggleRadio(this)">
                                            <span class="ml-2">Alpha</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 flex justify-end">
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Simpan</button>
            </div>
        </form>
    </div>

    <script>
        function toggleRadio(radio) {
            if (radio.hasAttribute("data-waschecked")) {
                radio.checked = false;
                radio.removeAttribute("data-waschecked");
            } else {
                radio.setAttribute("data-waschecked", "true");
            }
        }
    </script>
</x-layout>