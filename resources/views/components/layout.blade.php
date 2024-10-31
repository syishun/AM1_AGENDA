<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{$title}}</title>
  <link rel="icon" href="{{ asset('assets/images/icon.png') }}">
  <link rel="stylesheet" href="/assets/css/layout.css">
  @vite('resources/css/app.css')

  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="h-full bg-gray-100 gradient-bg">

  <!-- Navigation -->
  <x-navigasi></x-navigasi>

  <!-- Header Section -->
  <x-header>{{ $title }}</x-header>

  <!-- Background Wrapper -->
  <div class="wrapper"></div>

  <!-- Main Content -->
  <main class="flex-grow">
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        {{$slot}}
      </div>
  </main>

  <!-- Floating Animations -->
  <div class="box">
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
      <div></div>
  </div>
</body>
</html>
