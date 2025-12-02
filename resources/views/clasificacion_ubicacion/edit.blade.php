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
        'name' => $caracteristica->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.clasificacion.ubicacion.update', $caracteristica->id) }}" 
            method="POST">
            @csrf
            @method('PUT')
            @include('clasificacion_ubicacion._form')
        </form>
    </div>
@stop