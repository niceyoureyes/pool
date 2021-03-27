@extends('layouts.app')

@section('head')
@endsection

@section('content')
    <!-- <div style="display:none"> $exercises </div> -->
    <form action="">
        <div id="app">
            <to_graph :url="{{$url}}"
                      >
            </to_graph>
            <br>
            <div class="row justify-content-center">
                <div class="col-9">
                    <ttable :columns="{{$names}}"
                            :raws="{{$exercises}}"
                            :url= "{{$url}}"
                            :settings="1"
                            @if (isset($indexes) && $indexes != null)
                                    :indexes= "{{$indexes}}"
                            @endif
                            @if (isset($filters) && $filters != null)
                                    :filters= "{{$filters}}"
                            @endif
                            >
                    </ttable>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('js')
@endsection
