@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div id="app">
        <h1>Это главная страница Pool 1.0</h1>
        <br>
        <div class="row">
            <div class="col-5">
                <h2>Этот сайт поможет вам отслеживать ваши тренировки по плаванию в лучшей форме</h2>
                <br>
                @if($last_date !== null)
                <articles :mean_swolf="{{$mean_swolf}}"
                        :message="{{$message}}"
                        :best_mean_tempo_50="{{$best_mean_tempo_50}}"
                        :best_mean_tempo_100="{{$best_mean_tempo_100}}"
                        :best_mean_tempo_200="{{$best_mean_tempo_200}}"
                        :best_mean_tempo_1000="{{$best_mean_tempo_1000}}"
                        :last_date="{{$last_date}}"
                        :all_duration="{{$all_duration}}"
                        >
                </articles>
                @else
                <h2>{{$message}}</h2>
                @endif
            </div>
            <div class="col-5">
                @if( $input_data !== null )
                <center><h2><b>График прогресса</b></h2></center>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-1 align-self-center">
                            <h3>{{$label_y}}</h3>
                        </div>
                        <div class="col-11">
                        <graph_line_chart :input_xy="{{$input_data}}"
                                          :second_line="true"
                                        >
                        </graph_line_chart>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row justify-content-md-center">
                        <h3>{{$label_x}}</h3>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
