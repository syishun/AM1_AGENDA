<x-layout>
    <x-slot:title>{{ $title }}</x-slot:title>

    <!-- Content Section -->
    <div class="flex justify-center items-center py-10">
        <!-- Left Character -->
        <div class="w-1/4 md:w-1/6">
            <img src="{{ asset('assets/images/liah.png') }}" alt="Liah" class="mx-auto h-48 md:h-80 w-auto object-contain"> <!-- Increased size for smaller screens -->
        </div>

        <!-- Main Text -->
        <div class="text-center mx-4 md:mx-10 w-full md:w-2/6">
            <img src="{{ asset('assets/images/icon.png') }}" alt="School Logo" class="mx-auto mb-4">
            <h2 class="text-lg md:text-xl font-bold text-white">SMK Amaliah 1 & 2 Ciawi</h2>
            <p class="italic text-white">Tauhid is Our Fundament</p>
        </div>

        <!-- Right Character -->
        <div class="w-1/4 md:w-1/6">
            <img src="{{ asset('assets/images/amal.png') }}" alt="Amal" class="mx-auto h-48 md:h-80 w-auto object-contain"> <!-- Increased size for smaller screens -->
        </div>
    </div>

    <!-- Footer Logos -->
    <div class="flex justify-center space-x-4 py-6">
        <img src="{{ asset('assets/images/LOGO-ANIMASI-01-500x500.png') }}" alt="AN" class="h-10">
        <img src="{{ asset('assets/images/TKJ-Baru-500x500.png') }}" alt="TJKT" class="h-10">
        <img src="{{ asset('assets/images/LOGO-RPL-01-500x500.png') }}" alt="PPLG" class="h-10">
        <img src="{{ asset('assets/images/LOGO-AKL-01-500x500.png') }}" alt="AKL" class="h-10">
        <img src="{{ asset('assets/images/BR-500x500.png') }}" alt="BR" class="h-10">
        <img src="{{ asset('assets/images/Logo-TB-OK-01-500x500.png') }}" alt="DPB" class="h-10">
        <img src="{{ asset('assets/images/LOGO-LPS-01-500x500.png') }}" alt="LPS" class="h-10">
        <img src="{{ asset('assets/images/MP-Baru-500x500.png') }}" alt="MP" class="h-10">
    </div>
</x-layout>