@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <h3 class="text-center">Упражнение {{ $id }}</h3>
    <br>
    <div id="app">
        <ttable :columns="['#', 'Старт', 'Длительность (сек)', 'Дистанция (м)', 'Средняя скорость']"
                :raws="{{$exercise}}">
        </ttable>
        <br>
        <h3 class="text-center">Данные (между подходами 1 мин)</h3>
        <br>
        <ttable :columns="['#', 'Калории', 'Дистанция', 'Скорость']"
                :raws="{{$blob}}">
        </ttable>
    </div>
@endsection

@section('js')
@endsection
