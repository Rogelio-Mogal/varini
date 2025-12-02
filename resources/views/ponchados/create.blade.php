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
        'name' => 'Nuevo'
    ]
]])

@section('content')
    <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
        <form action="{{ route('admin.ponchados.store') }}" 
            method="POST"
            enctype="multipart/form-data">
            @csrf
            @include('ponchados._form')
        </form>
    </div>
@stop