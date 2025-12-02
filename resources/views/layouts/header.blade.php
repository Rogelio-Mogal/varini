@props([
    'breadcrumb' => [],
])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <!-- Flowbite -->
    <link rel="stylesheet" href="{{ mix('node_modules/flowbite/dist/flowbite.min.css') }}">

    <!--datatables -->
    <link rel="stylesheet" href="{{ asset('datatable/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('datatable/css/datatables-dark-mode.css') }}">

    <!--select2 -->
    <link rel="stylesheet" href="{{ asset('select2/select2.min.css') }}">

    <!-- sweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Styles -->
    @livewireStyles
    @yield('css')
</head>

<?php
    $user = Auth::user();
    $menuColorClass = $user->menu_color ?? 'bg-indigo-400'; // Usa un valor predeterminado si no hay color guardado
    $themeClass = $user->theme ?? 'light';
?>

<body class="bg-indigo dark:bg-gray-800 {{ $themeClass === 'dark' ? 'dark' : '' }}">

    <div id="app" class="bg-indigo-400 dark:bg-gray-800 text-black dark:text-white">
        <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar"
            aria-controls="default-sidebar" type="button"
            class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
            <span class="sr-only">Open sidebar</span>
            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" fill-rule="evenodd"
                    d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z">
                </path>
            </svg>
        </button>

        <aside id="default-sidebar"
            class="fixed top-0 left-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0"
            aria-label="Sidenav">
            <div class="overflow-y-auto py-5 px-3 h-full {{ $menuColorClass }} border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
                id="menu">
                <ul class="space-y-2">
                    {{--
                    <li>
                        <div class="mb-4">
                            <button onclick="changeMenuColor('bg-red-500')"
                                class="p-2 bg-red-600 text-white rounded">Red</button>
                            <button onclick="changeMenuColor('bg-yellow-500')"
                                class="p-2 bg-yellow-600 text-white rounded">Yellow</button>
                            <button onclick="changeMenuColor('bg-blue-500')"
                                class="p-2 bg-blue-600 text-white rounded">Blue</button>
                            <button onclick="changeMenuColor('bg-pink-500')"
                                class="p-2 bg-pink-600 text-white rounded">Pink</button>
                        </div>
                    </li>
                    <li>
                        <button id="theme-toggle" type="button"
                            class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5">
                            <svg id="theme-toggle-dark-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                            </svg>
                            <svg id="theme-toggle-light-icon" class="hidden w-5 h-5" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                    fill-rule="evenodd" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </li>
                    --}}
                    <li>
                        <button type="button"
                            class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-cuenta"
                            data-collapse-toggle="dropdown-cuenta"
                            data-target="dropdown-cuenta">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3m-3 3h3m-3 3h3m-6 1c-.306-.613-.933-1-1.618-1H7.618c-.685 0-1.312.387-1.618 1M4 5h16a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V6a1 1 0 0 1 1-1Zm7 5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z"/>
                            </svg>                              
                            <span class="flex-1 ms-3 text-left rtl:text-right whitespace-nowrap">Cuenta</span>
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="m1 1 4 4 4-4" />
                            </svg>
                        </button>
                        <ul id="dropdown-cuenta" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.perfil.edit', ['perfil' => auth()->id()]) }}"
                                    class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.perfil.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                     Perfil
                                </a>
                            </li>
                            <li>
                                <form id="logout-form" method="POST" action="{{ route('logout') }}" x-data>
                                    @csrf
                                    <a href="#"
                                        class="flex items-center w-full p-2 text-gray-900 transition duration-75 rounded-lg pl-11 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        {{ __('Salir') }}
                                    </a>
                                </form>
                            </li>
                        </ul>
                    </li>
                    {{--
                    @can('Gestión de roles')
                        <li>
                            <a href="{{ route('admin.roles.index') }}"
                                class="flex items-center p-2 text-base font-normal text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                    {{ request()->routeIs('admin.roles.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="square" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 19H5a1 1 0 0 1-1-1v-1a3 3 0 0 1 3-3h2m10 1a3 3 0 0 1-3 3m3-3a3 3 0 0 0-3-3m3 3h1m-4 3a3 3 0 0 1-3-3m3 3v1m-3-4a3 3 0 0 1 3-3m-3 3h-1m4-3v-1m-2.121 1.879-.707-.707m5.656 5.656-.707-.707m-4.242 0-.707.707m5.656-5.656-.707.707M12 8a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                                <span class="ml-3">Roles</span>
                            </a>
                        </li>
                    @endcan
                    <li>
                        <a href="{{ route('admin.permissions.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.permissions.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 14v3m-3-6V7a3 3 0 1 1 6 0v4m-8 0h10a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" />
                            </svg>
                            <span class="ml-3">Permisos</span>
                        </a>
                    </li>
                    --}}
                    <li>
                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-800 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M16 19h4a1 1 0 0 0 1-1v-1a3 3 0 0 0-3-3h-2m-2.236-4a3 3 0 1 0 0-4M3 18v-1a3 3 0 0 1 3-3h4a3 3 0 0 1 3 3v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-10a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>

                            <span class="ml-3">Usuarios</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.configuracion.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.configuracion.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 13v-2a1 1 0 0 0-1-1h-.757l-.707-1.707.535-.536a1 1 0 0 0 0-1.414l-1.414-1.414a1 1 0 0 0-1.414 0l-.536.535L14 4.757V4a1 1 0 0 0-1-1h-2a1 1 0 0 0-1 1v.757l-1.707.707-.536-.535a1 1 0 0 0-1.414 0L4.929 6.343a1 1 0 0 0 0 1.414l.536.536L4.757 10H4a1 1 0 0 0-1 1v2a1 1 0 0 0 1 1h.757l.707 1.707-.535.536a1 1 0 0 0 0 1.414l1.414 1.414a1 1 0 0 0 1.414 0l.536-.535 1.707.707V20a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-.757l1.707-.708.536.536a1 1 0 0 0 1.414 0l1.414-1.414a1 1 0 0 0 0-1.414l-.535-.536.707-1.707H20a1 1 0 0 0 1-1Z" />
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2" d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                            </svg>

                            <span class="ml-3">Configuración</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.ventas.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.ventas.index') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4.5V19a1 1 0 0 0 1 1h15M7 14l4-4 4 4 5-5m0 0h-3.207M20 9v3.207" />
                            </svg>
                            <span class="ml-3">Ventas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.inventario.index') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.inventario.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4" />
                            </svg>
                            <span class="ml-3">Inventario</span>
                        </a>
                    </li>
                    {{--
                    <li>
                        <a href="{{ route('admin.ventas.create') }}"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                            {{ request()->routeIs('admin.ventas.create') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 4h1.5L8 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm.75-3H7.5M11 7H6.312M17 4v6m-3-3h6" />
                            </svg>
                            <span class="ml-3">VENDER</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="#"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group
                                {{ request()->routeIs('facturacion.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m14 9.006h-.335a1.647 1.647 0 0 1-1.647-1.647v-1.706a1.647 1.647 0 0 1 1.647-1.647L19 12M5 12v5h1.375A1.626 1.626 0 0 0 8 15.375v-1.75A1.626 1.626 0 0 0 6.375 12H5Zm9 1.5v2a1.5 1.5 0 0 1-1.5 1.5v0a1.5 1.5 0 0 1-1.5-1.5v-2a1.5 1.5 0 0 1 1.5-1.5v0a1.5 1.5 0 0 1 1.5 1.5Z" />
                            </svg>
                            <span class="ml-3">Facturación</span>
                        </a>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-finanzas" 
                            data-collapse-toggle="dropdown-finanzas"
                            data-target="dropdown-finanzas">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor"
                                viewBox="0 0 24 24">
                                <path fill-rule="evenodd"
                                    d="M9 15a6 6 0 1 1 12 0 6 6 0 0 1-12 0Zm3.845-1.855a2.4 2.4 0 0 1 1.2-1.226 1 1 0 0 1 1.992-.026c.426.15.809.408 1.111.749a1 1 0 1 1-1.496 1.327.682.682 0 0 0-.36-.213.997.997 0 0 1-.113-.032.4.4 0 0 0-.394.074.93.93 0 0 0 .455.254 2.914 2.914 0 0 1 1.504.9c.373.433.669 1.092.464 1.823a.996.996 0 0 1-.046.129c-.226.519-.627.94-1.132 1.192a1 1 0 0 1-1.956.093 2.68 2.68 0 0 1-1.227-.798 1 1 0 1 1 1.506-1.315.682.682 0 0 0 .363.216c.038.009.075.02.111.032a.4.4 0 0 0 .395-.074.93.93 0 0 0-.455-.254 2.91 2.91 0 0 1-1.503-.9c-.375-.433-.666-1.089-.466-1.817a.994.994 0 0 1 .047-.134Zm1.884.573.003.008c-.003-.005-.003-.008-.003-.008Zm.55 2.613s-.002-.002-.003-.007a.032.032 0 0 1 .003.007ZM4 14a1 1 0 0 1 1 1v4a1 1 0 1 1-2 0v-4a1 1 0 0 1 1-1Zm3-2a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Zm6.5-8a1 1 0 0 1 1-1H18a1 1 0 0 1 1 1v3a1 1 0 1 1-2 0v-.796l-2.341 2.049a1 1 0 0 1-1.24.06l-2.894-2.066L6.614 9.29a1 1 0 1 1-1.228-1.578l4.5-3.5a1 1 0 0 1 1.195-.025l2.856 2.04L15.34 5h-.84a1 1 0 0 1-1-1Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Finanzas</span>
                            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-finanzas" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.forma.pago.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.forma.pago.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Forma de pago
                                </a>
                            </li>
                        </ul>
                    </li>
                    --}}
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-clientes"
                            data-collapse-toggle="dropdown-clientes"
                            data-target="dropdown-clientes">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                    d="M4.5 17H4a1 1 0 0 1-1-1 3 3 0 0 1 3-3h1m0-3.05A2.5 2.5 0 1 1 9 5.5M19.5 17h.5a1 1 0 0 0 1-1 3 3 0 0 0-3-3h-1m0-3.05a2.5 2.5 0 1 0-2-4.45m.5 13.5h-7a1 1 0 0 1-1-1 3 3 0 0 1 3-3h3a3 3 0 0 1 3 3 1 1 0 0 1-1 1Zm-1-9.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Clientes</span>
                            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-clientes" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.clientes.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.clientes.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Clientes
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.creditos.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.creditos.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Créditos
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-ponchados" 
                            data-collapse-toggle="dropdown-ponchados"
                            data-target="dropdown-ponchados">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v5m-3 0h6M4 11h16M5 15h14a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1Z" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Ponchados</span>
                            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-ponchados" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.ponchados.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.ponchados.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Ponchados
                                </a>
                            </li>  
                            <li>
                                <a href="{{ route('admin.precio.ponchado.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.precio.ponchado.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Precios
                                </a>
                            </li> 
                            <li>
                                <a href="{{ route('admin.pedidos.ponchados.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.pedidos.ponchados.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Pedidos
                                </a>
                            </li>                         
                        </ul>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-productos" 
                            data-collapse-toggle="dropdown-productos" 
                            data-target="dropdown-productos">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 10V6a3 3 0 0 1 3-3v0a3 3 0 0 1 3 3v4m3-2 .917 11.923A1 1 0 0 1 17.92 21H6.08a1 1 0 0 1-.997-1.077L6 8h12Z" />
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Productos</span>
                            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-productos" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.precios.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                        {{ request()->routeIs('admin.precios.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Precios
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.proveedores.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                        {{ request()->routeIs('admin.proveedores.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Proveedores
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.producto.caracteristica.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                        {{ request()->routeIs('admin.producto.caracteristica.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Caracteristicas
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.clasificacion.ubicacion.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                        {{ request()->routeIs('admin.clasificacion.ubicacion.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                        Clasificación/Ubicación
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.producto.servicio.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                        {{ request()->routeIs('admin.producto.servicio.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Productos/Servicios
                                </a>
                            </li>

                            <li>
                                <a href="{{ route('admin.compras.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.compras.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Compras
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <button type="button"
                            class="flex items-center p-2 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700"
                            aria-controls="dropdown-documentos"
                            data-collapse-toggle="dropdown-documentos"
                            data-target="dropdown-documentos">
                            <svg aria-hidden="true" class="w-6 h-6 text-gray-800 dark:text-white" fill="currentColor"
                                viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd"
                                    d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="flex-1 ml-3 text-left whitespace-nowrap">Documentos</span>
                            <svg aria-hidden="true" class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                        <ul id="dropdown-documentos" class="hidden py-2 space-y-2">
                            <li>
                                <a href="{{ route('admin.cotizacion.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.cotizacion.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Cotizaciones
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.ticket.alterno.index') }}"
                                    class="flex items-center p-2 pl-11 w-full text-base font-normal text-gray-900 rounded-lg transition duration-75 group hover:bg-gray-100 dark:text-white dark:hover:bg-gray-700
                                    {{ request()->routeIs('admin.ticket.alterno.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                                    Ticket alterno
                                </a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"
                            class="flex items-center p-2 text-base font-normal text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 4h3a1 1 0 0 1 1 1v15a1 1 0 0 1-1 1H6a1 1 0 0 1-1-1V5a1 1 0 0 1 1-1h3m0 3h6m-6 7 2 2 4-4m-5-9v4h4V3h-4Z" />
                            </svg>
                            <span class="ml-3">Reportes</span>
                        </a>
                    </li>
                </ul>
            </div>

        </aside>
    </div>
    <script>

        function changeMenuColor(colorClass) {
            const menu = document.getElementById('menu');
            menu.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-pink-500');
            menu.classList.add(colorClass);

            const app = document.getElementById('app');
            app.classList.remove('bg-red-500', 'bg-yellow-500', 'bg-blue-500', 'bg-pink-500');
            app.classList.add(colorClass);

            // Envía el color elegido al servidor
            fetch('/update-menu-color', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify({ menu_color: colorClass }),
            });
        }
    </script>
