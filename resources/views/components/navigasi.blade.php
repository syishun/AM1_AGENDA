@php
  $kelasId = Auth::user()->kelas_id ?? null;
@endphp
<nav class="flex flex-col mt-4 space-y-2 px-4">
    <!-- Conditional Navigation Links based on User Role -->
    @if (Auth::user()->role == 'Admin')
      <a href="{{ url('admin') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('admin') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-heroicon-s-home class="w-5 h-5 mr-2" /> 
        <span>Beranda</span>
      </a>  
      <a href="{{ url('data_guru') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('data_guru') || Request::is('data_guru/create') || Request::is('data_guru/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-fas-chalkboard-teacher class="w-5 h-5 mr-2" /> 
        <span>Guru</span></a>
      <a href="{{ url('mapel') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('mapel') || Request::is('mapel/create') || Request::is('mapel/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-bxs-book class="w-5 h-5 mr-2" /> 
        <span>Mapel</span></a>
      <a href="{{ url('kelas') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('kelas') || Request::is('kelas/create') || Request::is('kelas/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-elemplus-school class="w-5 h-5 mr-2" /> 
        <span>Kelas</span></a>
      <a href="{{ url('data_siswa') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('data_siswa') || Request::is('data_siswa/create') || Request::is('data_siswa/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-pepicon-people class="w-5 h-5 mr-2" /> 
        <span>Siswa</span></a>
      <a href="{{ url('user') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('user') || Request::is('user/create') || Request::is('user/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-far-user-circle class="w-5 h-5 mr-2" /> 
        <span>User</span></a>
      <div class="relative">
        <button onclick="toggleDropdown()" class="w-full text-left px-4 py-2 rounded-md text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'hover:bg-green-500 text-gray-300' }}">
          Lainnya
          <svg class="inline w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.25 6.25L10 10.75L14.75 6.25H5.25Z"></path>
          </svg>
        </button>
        <!-- Dropdown Content -->
        <div id="dropdownContent" class="hidden absolute left-0 mt-1 w-full bg-white shadow-md rounded-md z-10">
          <a href="{{ url('agenda') }}" class="flex items-center px-4 py-2 text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">
            <x-lucide-clipboard-pen-line class="w-5 h-5 mr-2" /> 
            <span>Agenda</span></a></a>
          <a href="{{ url('absen_guru') }}" class="flex items-center px-4 py-2 text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">
            <x-fas-user-check class="w-5 h-5 mr-2" /> 
            <span>Absensi Guru</span></a></a>
          <a href="{{ url('absen_siswa.admin_index') }}" class="flex items-center px-4 py-2 text-sm {{ Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">
            <x-akar-clipboard class="w-5 h-5 mr-2" /> 
            <span>Absensi Siswa</span></a>
          </a>
        </div>
      </div>
    @elseif (Auth::user()->role == 'Guru')
      <a href="{{ url('guru') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('guru') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-heroicon-s-home class="w-5 h-5 mr-2" /> 
        <span>Beranda</span>
      </a>
      <a href="{{ url('agenda') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('agenda/create/*') || Request::is('agenda/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-lucide-clipboard-pen-line class="w-5 h-5 mr-2" /> 
        <span>Agenda</span></a>
      <a href="{{ url('absen_guru') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_guru/create/*') || Request::is('absen_guru/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-fas-user-check class="w-5 h-5 mr-2" /> 
        <span>Absensi</span></a>
    @elseif (Auth::user()->role == 'Perwakilan Kelas')
      <a href="{{ url('siswa') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('siswa') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-heroicon-s-home class="w-5 h-5 mr-2" /> 
        <span>Beranda</span>
      </a>
      <a href="{{ url('absen_siswa') }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('absen_siswa') || Request::is('absen_siswa/create') || Request::is('absen_siswa/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-akar-clipboard class="w-5 h-5 mr-2" /> 
        <span>Absensi</span></a>
      <a href="{{ $kelasId ? url('absen_guru/kelas/' . $kelasId) : '#' }}" class="flex items-center px-4 py-2 rounded-md text-sm {{ Request::is('absen_guru/kelas/' . $kelasId) ? 'bg-green-500' : 'hover:bg-green-500' }}">
        <x-carbon-task class="w-5 h-5 mr-2" /> 
        <span>Tugas</span></a>
    @endif
</nav>
<script>
  // Tampilkan/ Sembunyikan Dropdown
  document.getElementById('dropdownMenuButton').addEventListener('click', function() {
      document.getElementById('dropdownMenuContent').classList.toggle('hidden');
  });
  function toggleDropdown() {
      document.getElementById("dropdownContent").classList.toggle("hidden");
  }
</script>