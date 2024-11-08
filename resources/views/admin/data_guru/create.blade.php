<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 flex flex-col md:flex-row">

        <!-- Bagian Kiri: Form -->
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('data_guru') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="nama_guru" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('nama_guru') border-red-500 @enderror" id="nama_guru" name="nama_guru" value="{{ old('nama_guru') }}" style="padding-left: 10px;">
                    @error('nama_guru')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kode_guru" class="block text-sm font-medium text-gray-700">Kode Guru</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('kode_guru') border-red-500 @enderror" id="kode_guru" name="kode_guru" value="{{ old('kode_guru') }}" style="padding-left: 10px;">
                    @error('kode_guru')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                    <div class="mt-2 flex items-center space-x-4">
                        <div>
                            <input type="radio" id="gender_pria" name="gender" value="Pria" {{ old('gender') == 'Pria' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="gender_pria" class="ml-2 text-sm text-gray-700">
                                <span class="text-blue-500">&#9794;</span> Laki-laki
                            </label>
                        </div>
                        <div>
                            <input type="radio" id="gender_wanita" name="gender" value="Wanita" {{ old('gender') == 'Wanita' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
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
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Simpan</button>
                </div>
            </form>
        </div>

        <!-- Bagian Kanan: Gambar -->
        <div class="w-full md:w-1/2 flex justify-center items-center">
            <img src="{{ asset('assets/images/hero.png') }}" alt="Hero Image" class="w-72 h-auto rounded-lg">
        </div>

    </div>
</x-layout>
