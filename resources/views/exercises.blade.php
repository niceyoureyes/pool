@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <h3 class="text-center">Упражнения</h3>
    <div id="app">
        <ttable :columns="{{$names}}"
                :raws="{{$exercises}}">
        </ttable>
    </div>
@endsection

@section('js')
@endsection
