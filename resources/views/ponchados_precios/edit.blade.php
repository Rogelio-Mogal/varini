@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Pedidos ponchados',
        'url' => route('admin.precio.ponchado.index')
    ],
    [
        'name' => $ponchado->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.precio.ponchado.update', $ponchado->id) }}" 
            method="POST"
            id="formulario-precios" name="formulario-precios">
            @csrf
            @method('PUT')
            @include('ponchados_precios._form')
        </form>
    </div>
@stop