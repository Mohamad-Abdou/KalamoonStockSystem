<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #dontPrint * {
                visibility: hidden;
            }

            #printableTable,
            #printableTable * {
                visibility: visible;
            }

            #printableTable {
                position: absolute;
                top: 0;
                left: 0;
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-grey">
        @include('layouts.navigation')
        @livewire('message-modal')
        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="mx-auto py-4 bg-second-color">
                    <h1 class="text-center font-bold">{{ $header }}</h1>
                </div>
            </header>
        @endisset
        <main>

            <div class="py-6">
                <div class="flex justify-center gap-4 px-8 py-2.5 max-y-full">
                    {{ $slot }}
                </div>
            </div>
        </main>
    </div>
    @livewireScripts
</body>

</html>

