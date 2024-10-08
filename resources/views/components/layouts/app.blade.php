<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? 'Page Title' }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js',])
        @livewireStyles
    </head>
    <body class="font-poppins">
        <x-notifications />
        {{ $slot }}
        @livewireScripts
        <wireui:scripts />
        @livewire('wire-elements-modal')
        @stack('scripts')
        {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    </body>
</html>

