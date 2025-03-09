<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Employee Dashboard - Manage Documents & Notifications">
    <meta name="author" content="Sharper Labs">
    <title>{{ $title ?? 'Employee Dashboard' }}</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-900 font-sans antialiased">

    {{-- <livewire:employee-navbar /> --}}
    @livewire('navigation-menu')

    <div class="container mx-auto px-4 sm:px-6 lg:px-8 mt-6">
        @if (isset($slot))
            {{ $slot }}
        @else
            @yield('content')
        @endif
    </div>

    <livewire:employee-footer />

    @livewireScripts
</body>
</html>
