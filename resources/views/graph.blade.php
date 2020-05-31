@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div id="app">
        <h1>График выбранных величин</h1>
        <div class="row">
            <div class="col-3"></div>
            <div class="col-1 align-self-center"><h3>{{$label_y}}</h3></div>
            <div class="col-4">
                <graph :input_xy="{{$input_data}}"
                       >
                </graph>
                <center><h3>{{$label_x}}</h3></center>
            </div>
            <div class="col-4"></div>
        </div>
    </div>
@endsection

@section('js')
@endsection
