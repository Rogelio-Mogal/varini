@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Ponchados',
        'url' => route('admin.ponchados.index')
    ],
    [
        'name' => $ponchado->nombre
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.ponchados.update', $ponchado->id) }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('ponchados._form')
        </form>
    </div>
@stop