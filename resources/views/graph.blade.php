@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <div id="app">
        <h1>График выбранных величин</h1>
        <graph :input_xy="{{$input_data}}"
               >
        </graph>
    </div>
@endsection

@section('js')
@endsection
