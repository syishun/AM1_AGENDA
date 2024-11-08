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
      <a href="{{ url('data_guru') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('data_guru') || Request::is('data_guru/create') || Request::is('data_guru/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Guru</a>
      <a href="{{ url('mapel') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('mapel') || Request::is('mapel/create') || Request::is('mapel/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Mapel</a>
      <a href="{{ url('kelas') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('kelas') || Request::is('kelas/create') || Request::is('kelas/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Kelas</a>
      <a href="{{ url('data_siswa') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('data_siswa') || Request::is('data_siswa/create') || Request::is('data_siswa/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Siswa</a>
      <a href="{{ url('user') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('user') || Request::is('user/create') || Request::is('user/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">User</a>
      <div class="relative">
        <button onclick="toggleDropdown()" class="w-full text-left px-4 py-2 rounded-md text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'hover:bg-green-500 text-gray-300' }}">
          Lainnya
          <svg class="inline w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M5.25 6.25L10 10.75L14.75 6.25H5.25Z"></path>
          </svg>
        </button>
        
        <!-- Dropdown Content -->
        <div id="dropdownContent" class="hidden absolute left-0 mt-1 w-full bg-white shadow-md rounded-md z-10">
          <a href="{{ url('agenda') }}" class="block px-4 py-2 text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">Agenda</a>
          <a href="{{ url('absen_guru') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">Absensi Guru</a>
          <a href="{{ url('absen_siswa.admin_index') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100' }}">Absensi Siswa</a>
        </div>
      </div>
    @elseif (Auth::user()->role == 'Guru')
      <a href="{{ url('guru') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('guru') ? 'bg-green-500' : 'hover:bg-green-500' }}">Beranda</a>
      <a href="{{ url('agenda') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('agenda/create/*') || Request::is('agenda/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Agenda</a>
      <a href="{{ url('absen_guru') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_guru/create/*') || Request::is('absen_guru/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Absensi</a>
    @elseif (Auth::user()->role == 'Perwakilan Kelas')
      <a href="{{ url('siswa') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('siswa') ? 'bg-green-500' : 'hover:bg-green-500' }}">Beranda</a>
      <a href="{{ url('absen_siswa') }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('absen_siswa') || Request::is('absen_siswa/create') || Request::is('absen_siswa/*/edit') ? 'bg-green-500' : 'hover:bg-green-500' }}">Absensi</a>
      <a href="{{ $kelasId ? url('absen_guru/kelas/' . $kelasId) : '#' }}" class="block px-4 py-2 rounded-md text-sm {{ Request::is('absen_guru/kelas/' . $kelasId) ? 'bg-green-500' : 'hover:bg-green-500' }}">Tugas</a>
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