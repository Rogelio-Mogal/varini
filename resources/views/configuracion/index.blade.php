
@extends('layouts.app', ['breadcrumb' => [
    [
        'name' => 'Home',
        'url' => route('dashboard')
    ],
    [
        'name' => 'Configuración'
    ]
]])

@section('css')

@stop

@section('content')

    <div class="shadow-md rounded-lg p-4 dark:bg-gray-800">
        <div class="grid grid-cols-1 lg:grid-cols-12 md:grid-cols-12 sm:grid-cols-12 gap-4">
            <div class="sm:col-span-12 lg:col-span-12 md:col-span-12">
                
                <div class="dark:bg-gray-800 shadow rounded-lg p-6 text-black dark:text-white">
                    <form action="{{ $metodo == 'edit' ? route('admin.configuracion.update', $configuracion->id) : route('admin.configuracion.store') }}"
                          method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        @if ($metodo == 'edit')
                            @method('PUT')
                        @endif

                        @include('configuracion._form')
                    </form>
                </div>

            </div>
        </div>

    </div>


@endsection

@section('js')
    <script>
        $(document).ready(function() {


        });
    </script>
@stop
