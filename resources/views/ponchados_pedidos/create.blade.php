@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Pedidos ponchados',
        'url' => route('admin.pedidos.ponchados.index')
    ],
    [
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.pedidos.ponchados.store') }}" 
            method="POST"
            id="formulario-pedidos" name="formulario-pedidos">
            @csrf
            @include('ponchados_pedidos._form')
        </form>
    </div>
@stop