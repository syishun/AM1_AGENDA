    @php
        $kelasId = Auth::user()->kelas_id ?? null;
    @endphp
    <nav class="bg-gradient-to-r from-green-700 to-green-500 shadow-lg" x-data="{ isOpen: false, isNotificationOpen: false }">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <div class="flex items-center">
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <!-- Admin Navigation -->
                            @if (Auth::user()->role == 'Admin')
                                <a href="{{ url('admin') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('admin') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Beranda</a>
                                <a href="{{ url('data_guru') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('data_guru') || Request::is('data_guru/create') || Request::is('data_guru/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Guru</a>
                                <a href="{{ url('mapel') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('mapel') || Request::is('mapel/create') || Request::is('mapel/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Mapel</a>
                                <a href="{{ url('kelas') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('kelas') || Request::is('kelas/create') || Request::is('kelas/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Kelas</a>
                                <a href="{{ url('data_siswa') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('data_siswa') || Request::is('data_siswa/create') || Request::is('data_siswa/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Siswa</a>
                                <a href="{{ url('user') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('user') || Request::is('user/create') || Request::is('user/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">User</a>
                                <div class="relative inline-block text-left">
                                    <button class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}" id="dropdownMenuButton">
                                        Lainnya
                                        <svg class="ml-1 h-5 w-5 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M5.25 6.25L10 10.75L14.75 6.25H5.25Z"></path>
                                        </svg>
                                    </button>
                                    <!-- Dropdown Content -->
                                    <div class="absolute hidden mt-2 w-48 bg-white shadow-lg rounded-md z-50" id="dropdownMenuContent">
                                        <a href="{{ url('agenda') }}" class="block px-4 py-2 text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100 hover:text-gray-900' }}">Agenda</a>
                                        <a href="{{ url('absen_guru') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100 hover:text-gray-900' }}">Absensi Guru</a>
                                        <a href="{{ url('absen_siswa.admin_index') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white' : 'text-gray-700 hover:bg-green-100 hover:text-gray-900' }}">Absensi Siswa</a>
                                    </div>
                                </div>
        
                            <!-- Guru Navigation -->
                            @elseif (Auth::user()->role == 'Guru')
                                <a href="{{ url('guru') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('guru') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Beranda</a>
                                <a href="{{ url('agenda') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('agenda/create/*') || Request::is('agenda/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Agenda</a>
                                <a href="{{ url('absen_guru') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_guru/create/*') || Request::is('absen_guru/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Absensi</a>
        
                            <!-- Siswa Navigation -->
                            @elseif (Auth::user()->role == 'Perwakilan Kelas')
                            <a href="{{ url('siswa') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('siswa') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Beranda</a>
                            <a href="{{ url('absen_siswa') }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('absen_siswa') || Request::is('absen_siswa/create') || Request::is('absen_siswa/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Absensi</a>
                            <a href="{{ $kelasId ? url('absen_guru/kelas/' . $kelasId) : '#' }}" class="rounded-md px-3 py-2 text-sm font-medium {{ Request::is('absen_guru/kelas/' . $kelasId) ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Tugas</a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        @if (Auth::user()->role == 'Perwakilan Kelas')
                            <!-- Notification Button -->
                            <button @click="isNotificationOpen = !isNotificationOpen" class="relative rounded-full p-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800">
                                <span class="sr-only">View notifications</span>
                                <!-- Bell Icon -->
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                                <!-- Red Notification Badge -->
                                @if(Auth::user()->unreadNotifications->count())
                                    <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-400"></span>
                                @endif
                            </button>

                            <!-- Dropdown Notification Panel -->
                            <div x-show="isNotificationOpen" @click.outside="isNotificationOpen = false" class="absolute right-0 mt-2 w-64 bg-white rounded shadow-lg p-4 z-10 transition transform ease-out duration-150" x-cloak>
                                <h3 class="text-gray-800 font-semibold text-sm mb-2">Notifikasi</h3>
                                <hr class="mb-2">
                                @forelse (Auth::user()->unreadNotifications as $notification)
                                    <div id="notification-{{ $notification->id }}" class="p-2 bg-white rounded hover:bg-gray-100 cursor-pointer mb-1 shadow-sm">
                                        <p class="text-sm font-medium text-gray-800">{{ $notification->data['title'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $notification->data['message'] }}</p>
                                        <a href="{{ $notification->data['link'] ?? '#' }}" class="text-blue-500 text-xs mt-1 inline-block">Lihat Detail</a>
                                        <button onclick="deleteNotification('{{ $notification->id }}')" class="text-red-500 text-xs ml-2">Hapus</button>
                                    </div>                                
                                @empty
                                    <p class="text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                                @endforelse
                            </div>
                        @endif

                        <!-- Profile dropdown -->
                        <div class="relative ml-3">
                            <div>
                                <button type="button" @click="isOpen = !isOpen" class="relative flex max-w-xs items-center rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-1 focus:ring-offset-gray-800" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                    <span class="absolute -inset-1.5"></span>
                                    <span class="sr-only">Open user menu</span>
                                    <img class="h-8 w-8 rounded-full" src="{{ asset('assets/images/icon.png') }}" alt="">
                                </button>
                            </div>

                            <div 
                                x-show="isOpen"
                                x-transition:enter="transition ease-out duration-100 transform"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75 transform"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95" 
                                class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" tabindex="-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block px-4 py-2 text-sm text-gray-700" role="menuitem" tabindex="-1" id="user-menu-item-2">Log out</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="-mr-2 flex md:hidden">
                    <button type="button" @click="isOpen = !isOpen" class="relative inline-flex items-center justify-center rounded-md bg-green-800 p-2 text-gray-400 hover:bg-green-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-1 focus:ring-offset-gray-800" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="absolute -inset-0.5"></span>
                        <span class="sr-only">Open main menu</span>
                        <svg :class="{'hidden': isOpen, 'block': !isOpen}" class="block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg :class="{'block': isOpen, 'hidden': !isOpen}" class="hidden h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div x-show="isOpen" class="md:hidden" id="mobile-menu">
            <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
                <!-- Admin -->
                @if (Auth::user()->role == 'Admin')
                    <a href="{{ url('admin') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('admin') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}" aria-current="page">Beranda</a>
                    <a href="{{ url('data_guru') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('data_guru') || Request::is('data_guru/create') || Request::is('data_guru/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Guru</a>
                    <a href="{{ url('mapel') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('mapel') || Request::is('mapel/create') || Request::is('mapel/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Mapel</a>
                    <a href="{{ url('kelas') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('kelas') || Request::is('kelas/create') || Request::is('kelas/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Kelas</a>
                    <a href="{{ url('data_siswa') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('data_siswa') || Request::is('data_siswa/create') || Request::is('data_siswa/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Siswa</a>
                    <div class="relative">
                        <button onclick="toggleDropdown()" class="w-full text-left block rounded-md px-3 py-2 text-base font-medium {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_siswa.admin_index') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">
                            Lainnya
                        </button>
                        <!-- Dropdown Content -->
                        <div id="dropdownContent" class="hidden mt-1 space-y-1 bg-white rounded-md">
                            <a href="{{ url('agenda') }}" class="block px-4 py-2 text-sm {{ Request::is('agenda') || Request::is('agenda/kelas/*') ? 'bg-green-500 text-white'  : 'text-gray-700 hover:bg-green-500 hover:text-white'  }}">Agenda</a>
                            <a href="{{ url('absen_guru') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') ? 'bg-green-500 text-white'  : 'text-gray-700 hover:bg-green-500 hover:text-white'  }}">Absensi Guru</a>
                            <a href="{{ url('absen_siswa.admin_index') }}" class="block px-4 py-2 text-sm {{ Request::is('absen_siswa.admin_index') ? 'bg-green-500 text-white'  : 'text-gray-700 hover:bg-green-500 hover:text-white'  }}">Absensi Siswa</a>
                        </div>
                    </div>
                <!-- Guru -->
                @elseif (Auth::user()->role == 'Guru')
                    <a href="{{ url('guru') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('guru') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}" aria-current="page">Beranda</a>
                    <a href="{{ url('agenda') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('agenda') || Request::is('agenda/kelas/*') || Request::is('agenda/create/*') || Request::is('agenda/*/edit') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Agenda</a>
                    <a href="{{ url('absen_guru') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('absen_guru') || Request::is('absen_guru/kelas/*') || Request::is('absen_guru/create/*') || Request::is('absen_guru/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Absensi</a>
                <!-- Siswa -->
                @elseif (Auth::user()->role == 'Perwakilan Kelas')
                    <a href="{{ url('siswa') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('siswa') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}" aria-current="page">Beranda</a>
                    <a href="{{ url('absen_siswa') }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('absen_siswa') || Request::is('absen_siswa/create') || Request::is('absen_siswa/*/edit') ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Absensi</a>
                    <!-- Use the dynamic kelas ID to redirect directly to the class page -->
                    <a href="{{ $kelasId ? url('absen_guru/kelas/' . $kelasId) : '#' }}" class="block rounded-md px-3 py-2 text-base font-medium {{ Request::is('absen_guru/kelas/' . $kelasId) ? 'text-white bg-green-500' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">Tugas</a>
                @endif
            </div>
            <div class="border-t border-black pb-3 pt-4">
                <div class="flex items-center px-5">
                  <div class="flex-shrink-0">
                    <img class="h-10 w-10 rounded-full" src="{{ asset('assets/images/icon.png') }}" alt="">
                  </div>
                    @if (Auth::user()->role == 'Perwakilan Kelas')
                        <!-- Notification Button -->
                        <button 
                        type="button" 
                        class="relative ml-auto flex-shrink-0 rounded-full bg-green-800 p-1 text-gray-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800" 
                        @click="isNotificationOpen = !isNotificationOpen">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                        </svg>
                        <!-- Red Notification Badge -->
                        @if(Auth::user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full ring-2 ring-white bg-red-400"></span>
                        @endif
                        </button>

                        <!-- Dropdown Notification Panel (Responsive) -->
                        <div 
                            x-show="isNotificationOpen" 
                            @click.outside="isNotificationOpen = false" 
                            class="absolute right-0 mt-2 w-64 bg-white rounded shadow-lg p-3 z-10 transition transform ease-out duration-150 md:w-80 lg:w-96" 
                            style="display: none;" 
                            x-cloak>

                            <h3 class="text-gray-800 font-semibold text-sm mb-2">Notifikasi</h3>

                            <!-- Notification Items -->
                            <div class="space-y-2">
                                @forelse (Auth::user()->unreadNotifications as $notification)
                                    <div class="p-2 bg-white rounded shadow flex items-start space-x-3">
                                        <div class="flex flex-col sm:flex-row items-start sm:items-center p-4 border-b border-gray-200">
                                            <div class="flex-1">
                                                <p class="text-sm sm:text-base font-medium">{{ $notification->data['title'] }}</p>
                                                <p class="text-xs sm:text-sm text-gray-500">{{ $notification->data['message'] }}</p>
                                                <a href="{{ $notification->data['link'] ?? '#' }}" class="text-blue-500 text-xs sm:text-sm" 
                                                   @click="markAsRead('{{ $notification->id }}')">Lihat Detail</a>
                                            </div>
                                            <div class="flex items-center mt-2 sm:mt-0">
                                                <!-- Delete button -->
                                                <button @click="deleteNotification('{{ $notification->id }}')"
                                                        class="text-red-500 text-xs sm:text-sm ml-2">Hapus</button>
                                            </div>
                                        </div>                                        
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                                @endforelse
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-3 space-y-1 px-2">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-green-500 hover:text-white">Log out</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>
    <script>
        function deleteNotification(notificationId) {
            fetch(`/deleteNotification/${notificationId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(response => {
                if (response.ok) {
                    // Hapus notifikasi dari tampilan
                    document.querySelector(`#notification-${notificationId}`).remove();
                } else {
                    alert("Gagal menghapus notifikasi");
                }
            });
        }

        // Tampilkan/ Sembunyikan Dropdown
        document.getElementById('dropdownMenuButton').addEventListener('click', function() {
            document.getElementById('dropdownMenuContent').classList.toggle('hidden');
        });
        function toggleDropdown() {
            document.getElementById("dropdownContent").classList.toggle("hidden");
        }
    </script>
    