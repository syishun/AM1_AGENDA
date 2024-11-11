<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    <div class="container mx-auto p-6 flex flex-col md:flex-row">
        <div class="w-full md:w-1/2 mb-4 md:mb-0">
            <form action="{{ url('user') }}" method="post" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('name') border-red-500 @enderror" id="name" name="name" value="{{ old('name') }}" style="padding-left: 10px;">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <div class="relative">
                        <input type="password" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('password') border-red-500 @enderror" id="password" name="password" value="{{ old('password') }}" style="padding-left: 10px;">
                        
                        <!-- Eye Icon -->
                        <span class="absolute inset-y-0 right-3 flex items-center cursor-pointer" onclick="togglePasswordVisibility('password', 'togglePasswordIcon')">
                            <i id="togglePasswordIcon" class="fas fa-eye text-gray-600"></i>
                        </span>
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                    <div class="mt-2 flex items-center space-x-4 gap-2">
                        <div>
                            <!-- Admin Role with Icon -->
                            <input type="radio" id="role_admin" name="role" value="Admin" {{ old('role') == 'Admin' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="role_admin" class="text-sm text-gray-700">
                                <i class="fas fa-user-shield mr-2"></i> Admin
                            </label>
                        </div>
                        <div>
                            <!-- Guru Role with Icon -->
                            <input type="radio" id="role_guru" name="role" value="Guru" {{ old('role') == 'Guru' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="role_guru" class="text-sm text-gray-700">
                                <i class="fas fa-chalkboard-teacher mr-2"></i> Guru
                            </label>
                        </div>
                        <div>
                            <!-- Perwakilan Kelas Role with Icon -->
                            <input type="radio" id="role_perwakilan_kelas" name="role" value="Perwakilan Kelas" {{ old('role') == 'Perwakilan Kelas' ? 'checked' : '' }} class="text-green-500 focus:ring-green-500">
                            <label for="role_perwakilan_kelas" class="text-sm text-gray-700">
                                <i class="fas fa-users mr-2"></i> Perwakilan Kelas
                            </label>
                        </div>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div id="kode-guru-section" style="display: none;">
                    <label for="kode_guru" class="block text-sm font-medium text-gray-700">Kode Guru</label>
                    <select id="kode_guru" name="kode_guru" class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('kode_guru') border-red-500 @enderror" style="padding-left: 10px;">
                        <option value="">--Pilih--</option>
                        @foreach ($data_guru as $item)
                            <option value="{{ $item->id }}">{{ $item->kode_guru }}</option>
                        @endforeach
                    </select>
                    @error('kode_guru')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Dropdown Kelas -->
                <div id="kelas-section" style="display: none;">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700">Kelas</label>
                    <select class="mt-1 block w-full h-10 bg-gray-200 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 sm:text-sm @error('kelas_id') border-red-500 @enderror" name="kelas_id" id="kelas_id" style="padding-left: 10px;">
                        <option value="">--Pilih--</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}">{{ $item->kelas_id }}</option>
                        @endforeach
                    </select>
                    @error('kelas_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 transition duration-200">Simpan</button>
            </form>
        </div>
        <!-- Bagian Kanan: Gambar -->
        <div class="w-full md:w-1/2 flex justify-center items-center">
            <img src="{{ asset('assets/images/hero.png') }}" alt="Hero Image" class="w-72 h-auto rounded-lg">
        </div>
    </div>

    <!-- Tambahkan script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const kelasSection = document.getElementById('kelas-section');

            roleInputs.forEach(roleInput => {
                roleInput.addEventListener('change', function() {
                    if (this.value === 'Perwakilan Kelas') {
                        kelasSection.style.display = 'block';
                    } else {
                        kelasSection.style.display = 'none';
                    }
                });
            });

            // Pastikan kelas select muncul jika role 'Perwakilan Kelas' sudah dipilih pada awal load
            const checkedRole = document.querySelector('input[name="role"]:checked');
            if (checkedRole && checkedRole.value === 'Perwakilan Kelas') {
                kelasSection.style.display = 'block';
            }
        });

        function togglePasswordVisibility(passwordFieldId, iconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(iconId);
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const roleInputs = document.querySelectorAll('input[name="role"]');
            const kodeGuruSection = document.getElementById('kode-guru-section');

            roleInputs.forEach(roleInput => {
                roleInput.addEventListener('change', function() {
                    if (this.value === 'Guru') {
                        kodeGuruSection.style.display = 'block';
                    } else {
                        kodeGuruSection.style.display = 'none';
                    }
                });
            });

            // Ensure 'Kode Guru' dropdown appears if 'Guru' role is selected on page load
            const checkedRole = document.querySelector('input[name="role"]:checked');
            if (checkedRole && checkedRole.value === 'Guru') {
                kodeGuruSection.style.display = 'block';
            }
        });

        function togglePasswordVisibility(passwordFieldId, iconId) {
            const passwordField = document.getElementById(passwordFieldId);
            const toggleIcon = document.getElementById(iconId);
            passwordField.type = passwordField.type === 'password' ? 'text' : 'password';
            toggleIcon.classList.toggle('fa-eye');
            toggleIcon.classList.toggle('fa-eye-slash');
        }
    </script>
</x-layout>
