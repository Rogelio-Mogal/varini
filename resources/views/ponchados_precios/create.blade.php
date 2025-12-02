@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Precios ponchados',
        'url' => route('admin.precio.ponchado.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.precio.ponchado.store') }}" 
            method="POST"
            id="formulario-precios" name="formulario-precios">
            @csrf
            @include('ponchados_precios._form')
        </form>
    </div>
@stop