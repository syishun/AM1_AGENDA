<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 flex flex-col md:flex-row">

        <!-- Bagian Kiri: Form -->
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('data_siswa/' . $data_siswa->id) }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @method('PUT')
                @csrf

                <div>
                    <label for="nama_siswa" class="block text-sm font-medium text-gray-700">Nama Siswa</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('nama_siswa') border-red-500 @enderror" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $data_siswa->nama_siswa) }}" style="padding-left: 10px;">
                    @error('nama_siswa')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nisn_id" class="block text-sm font-medium text-gray-700">NISN</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('nisn_id') border-red-500 @enderror" id="nisn_id" name="nisn_id" value="{{ old('nisn_id', $data_siswa->nisn_id) }}" style="padding-left: 10px;">
                    @error('nisn_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div>
                            <input type="radio" id="gender_pria" name="gender" value="Pria" {{ old('gender', $data_siswa->gender) == 'Pria' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="gender_pria" class="ml-2 text-sm text-gray-700">
                                <span class="text-blue-500">&#9794;</span> Laki-laki
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="gender_wanita" name="gender" value="Wanita" {{ old('gender', $data_siswa->gender) == 'Wanita' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="gender_wanita" class="ml-2 text-sm text-gray-700">
                                <span class="text-pink-500">&#9792;</span> Perempuan
                            </label>
                        </div>
                    </div>
                    @error('gender')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('kelas_id') border-red-500 @enderror" name="kelas_id" id="kelas_id" style="padding-left: 10px;">
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}" {{ $data_siswa->kelas_id == $item->id ? 'selected' : '' }}>{{ $item->kelas_id }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Update</button>
                </div>
            </form>
        </div>

        <!-- Bagian Kanan: Gambar -->
        <div class="w-full md:w-1/2 flex justify-center items-center">
            <img src="{{ asset('assets/images/hero.png') }}" alt="Hero Image" class="w-72 h-auto rounded-lg">
        </div>

    </div>
</x-layout>
