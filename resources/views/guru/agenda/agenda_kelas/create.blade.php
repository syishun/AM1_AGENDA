<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 flex flex-col md:flex-row">
        
        <!-- Bagian Kiri: Form -->
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('agenda') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <!-- Hidden input for kelas_id -->
                <input type="hidden" name="kelas_id" value="{{ $kelas_id }}">

                <div>
                    <label for="tgl" class="block text-sm font-medium text-gray-700">Tanggal</label>
                    <input type="date" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('tgl') border-red-500 @enderror" 
                                id="tgl" name="tgl" value="{{ old('tgl', date('Y-m-d')) }}" 
                                min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}" style="padding-left: 10px;">
                    @error('tgl')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_mapel" class="block text-sm font-medium text-gray-700">Mapel</label>
                    <select class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('nama_mapel') border-red-500 @enderror" name="nama_mapel" id="nama_mapel" style="padding-left: 10px">
                        <option value="">--Pilih--</option>
                        @foreach ($mapel as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_mapel }}</option>
                        @endforeach
                    </select>
                    @error('nama_mapel')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>                

                <div>
                    <label for="aktivitas" class="block text-sm font-medium text-gray-700">Aktivitas</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('aktivitas') border-red-500 @enderror" id="aktivitas" name="aktivitas" value="{{ old('aktivitas') }}" style="padding-left: 10px;">
                    @error('aktivitas')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_msk" class="block text-sm font-medium text-gray-700">Jam Masuk</label>
                    <input type="time" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('jam_msk') border-red-500 @enderror" id="jam_msk" name="jam_msk" value="{{ old('jam_msk') }}" style="padding-left: 10px;">
                    @error('jam_msk')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="jam_keluar" class="block text-sm font-medium text-gray-700">Jam Keluar</label>
                    <input type="time" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('jam_keluar') border-red-500 @enderror" id="jam_keluar" name="jam_keluar" value="{{ old('jam_keluar') }}" style="padding-left: 10px;">
                    @error('jam_keluar')
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
            <img src="{{ asset('assets/images/hero.png') }}" alt="Hero Image" class="w-96 h-auto rounded-lg">
        </div>

    </div>
</x-layout>
