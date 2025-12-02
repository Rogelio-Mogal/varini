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
        'name' => $pedidoBase->referencia_cliente
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.pedidos.ponchados.update', $pedidoBase->referencia_cliente) }}" 
            method="POST">
            @csrf
            @method('PUT')
            @include('ponchados_pedidos._form')
        </form>
    </div>
@stop