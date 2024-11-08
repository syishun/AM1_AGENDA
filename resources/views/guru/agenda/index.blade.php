<x-layout>
    <x-slot:title>{{$title}}</x-slot:title>

    @if($kelas->isEmpty())
        <p class="text-center mt-4 text-green-50">Petugas belum menambahkan kelas.</p>
    @else
    <div class="container mx-auto my-8">
        <div class="flex justify-center">
            <h1 class="text-center text-2xl font-bold mb-6 inline-block border-b-2 border-green-100 pb-1 text-green-700">Pilih Kelas untuk Melihat Agenda</h1>
        </div>

            {{-- Grade X --}}
            <div class="flex justify-center mb-6 relative">
                <h2 class="text-xl font-semibold border-b-2 border-green-500 pb-1 text-green-700">Kelas X</h2>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-8 relative">
                @foreach ($kelas as $item)
                    @if (Str::startsWith(trim($item->kelas), 'X') && !Str::startsWith(trim($item->kelas), 'XI') && !Str::startsWith(trim($item->kelas), 'XII'))
                        <a href="{{ url('agenda/kelas/' . $item->id) }}" class="block text-center py-4 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                            {{ $item->kelas_id }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Grade XI --}}
            <div class="flex justify-center mb-6 relative">
                <h2 class="text-xl font-semibold border-b-2 border-green-500 pb-1 text-green-700">Kelas XI</h2>
            </div>
            <div class="grid grid-cols-3 gap-4 mb-8 relative">
                @foreach ($kelas as $item)
                    @if (Str::startsWith(trim($item->kelas), 'XI') && !Str::startsWith(trim($item->kelas), 'XII'))
                        <a href="{{ url('agenda/kelas/' . $item->id) }}" class="block text-center py-4 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                            {{ $item->kelas_id }}
                        </a>
                    @endif
                @endforeach
            </div>

            {{-- Grade XII --}}
            <div class="flex justify-center mb-6 relative">
                <h2 class="text-xl font-semibold border-b-2 border-green-500 pb-1 text-green-700">Kelas XII</h2>
            </div>
            <div class="grid grid-cols-3 gap-4 relative">
                @foreach ($kelas as $item)
                    @if (Str::startsWith(trim($item->kelas), 'XII'))
                        <a href="{{ url('agenda/kelas/' . $item->id) }}" class="block text-center py-4 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                            {{ $item->kelas_id }}
                        </a>
                    @endif
                @endforeach
            </div>

    </div>
    @endif
</x-layout>
