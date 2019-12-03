@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <h3 class="text-center">Упражнения</h3>
    <div id="app">
        <div class="row justify-content-center">
            <div class="col-9">
                <ttable :columns="{{$names}}"
                        :raws="{{$exercises}}">
                </ttable>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
