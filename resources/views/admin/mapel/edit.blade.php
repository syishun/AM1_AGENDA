<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg flex flex-col md:flex-row">

        <!-- Bagian Kiri: Form -->
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('mapel/' . $mapel->id) }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @method('PUT')
                @csrf

                <div>
                    <label for="nama_mapel" class="block text-sm font-medium text-gray-700">Nama Mata Pelajaran</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('nama_mapel') border-red-500 @enderror" id="nama_mapel" name="nama_mapel" value="{{ old('nama_mapel', $mapel->nama_mapel) }}" style="padding-left: 10px;">
                    @error('nama_mapel')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kode_guru" class="block text-sm font-medium text-gray-700">Kode Guru</label>
                    <select class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('kode_guru') border-red-500 @enderror" name="kode_guru" id="kode_guru" style="padding-left: 10px;">
                        @foreach ($data_guru as $item)
                            <option value="{{ $item->id }}" {{ $mapel->kode_guru == $item->id ? 'selected' : '' }}>{{ $item->kode_guru }}</option>
                        @endforeach
                    </select>
                    @error('kode_guru')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="mapel_id" class="block text-sm font-medium text-gray-700">ID Mata Pelajaran</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('mapel_id') border-red-500 @enderror" id="mapel_id" name="mapel_id" value="{{ old('mapel_id', $mapel->mapel_id) }}" style="padding-left: 10px;">
                    @error('mapel_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Update
                    </button>
                </div>
            </form>
        </div>

        <!-- Bagian Kanan: Gambar -->
        <div class="w-full md:w-1/2 flex justify-center items-center">
            <img src="{{ asset('assets/images/hero.png') }}" alt="Hero Image" class="w-72 h-auto rounded-lg">
        </div>

    </div>
</x-layout>
