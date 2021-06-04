@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div id="app">
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
    </div>
@endsection

@section('js')
@endsection
