@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <h3 class="text-center">Упражнение {{ $id }}</h3>
    <br>
    <div id="app">
        <ttable :columns="{{$names}}"
                :raws="{{$exercise}}">
        </ttable>
        <br>

        @if ($live_data_string != null)
            <h3 class="text-center">Пульс</h3>
            <br>
            <ttable :columns="['#', 'Время', 'Пульс']"
                    :raws="{{$live_data_string}}">
            </ttable>
            <br>
        @endif

        @if ($addl_data_string != null)
            <h3 class="text-center">Дополнительная информация</h3>
            <br>
            <h4 class="text-center">{{"Длина бассейна:".$pool_length." ".$pool_length_unit.". Полная дистанция: ".$total_distance}}</h4>
            <br>
            <ttable :columns="['#', 'Длительность (сек)', 'Интервал', 'Количество гребков', 'Тип гребка']"
                    :raws="{{$addl_data_string}}">
            </ttable>
            <br>
        @endif
    </div>
@endsection

@section('js')
@endsection
