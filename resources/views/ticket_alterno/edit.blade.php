@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ticket alterno',
        'url' => route('admin.ticket.alterno.index')
    ],
    [
        'name' => $cotizacion->clienteDocumento->full_name
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.ticket.alterno.update', $cotizacion->id) }}" method="POST">
            @csrf
            @method('PUT')
            @include('ticket_alterno._form')
        </form>
    </div>
@stop