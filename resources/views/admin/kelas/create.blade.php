<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 bg-white shadow-md rounded-lg flex flex-col md:flex-row">

        <!-- Bagian Kiri: Form -->
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('kelas') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kelas</label>
                    <div class="flex space-x-4 mt-2">
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-green-500 @error('kelas') border-red-500 @enderror" type="radio" name="kelas" id="kelasX" value="X" {{ old('kelas') == 'X' ? 'checked' : '' }}>
                            <label for="kelasX" class="ml-2 text-gray-700">X</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-green-500 @error('kelas') border-red-500 @enderror" type="radio" name="kelas" id="kelasXI" value="XI" {{ old('kelas') == 'XI' ? 'checked' : '' }}>
                            <label for="kelasXI" class="ml-2 text-gray-700">XI</label>
                        </div>
                        <div class="flex items-center">
                            <input class="form-radio h-4 w-4 text-green-500 @error('kelas') border-red-500 @enderror" type="radio" name="kelas" id="kelasXII" value="XII" {{ old('kelas') == 'XII' ? 'checked' : '' }}>
                            <label for="kelasXII" class="ml-2 text-gray-700">XII</label>
                        </div>
                    </div>
                    @error('kelas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="angkatan" class="block text-sm font-medium text-gray-700">Angkatan</label>
                    <input type="number" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('angkatan') border-red-500 @enderror" id="angkatan" name="angkatan" value="{{ old('angkatan') }}" style="padding-left: 10px;">
                    @error('angkatan')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">ID Kelas</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm @error('kelas_id') border-red-500 @enderror" id="kelas_id" name="kelas_id" value="{{ old('kelas_id') }}" style="padding-left: 10px;">
                    @error('kelas_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Simpan
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
