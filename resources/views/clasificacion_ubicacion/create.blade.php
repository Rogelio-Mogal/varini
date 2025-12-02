@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Clasificación/Ubicación',
        'url' => route('admin.clasificacion.ubicacion.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.clasificacion.ubicacion.store') }}" 
            method="POST">
            @csrf
            @include('clasificacion_ubicacion._form')
        </form>
    </div>
@stop