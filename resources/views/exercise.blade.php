@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <h3 class="text-center">Упражнение {{ $id }}</h3>
    <br>
    <div id="app">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-9">
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
                        <h4 class="text-center">{{"Длина бассейна: ".$pool_length." м. Полная дистанция: ".$total_distance." м"}}</h4>
                        <br>
                        <ttable :columns="['#', 'Длительность', 'Интервал', 'Количество гребков', 'Тип гребка']"
                                :raws="{{$addl_data_string}}">
                        </ttable>
                        <br>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
