@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div class="row">
        <div class="col-6">
            <h3 class="text-center">Загруженные файлы</h3>
        </div>
        <div class="col-6">
            <a class="btn btn-dark" href="{{ route('resolve') }}" role="button">Обработать файлы</a>
        </div>
    </div>
    <br>
    <div id="app">
        <ttable :columns="['#', 'Имя файла', 'Имя файла на сервере', 'Расширение']"
                :raws="{{$files}}">
        </ttable>
    </div>
@endsection

@section('js')
@endsection
