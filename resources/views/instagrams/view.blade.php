@extends('layouts.master')

@section('content')
    <style>
        .pBottom10 {
            padding-bottom: 10px;
        }
    </style>
    <h1>{{ $instagram->fullname }}</h1>

    @foreach($likes as $image)
        @if($image['type'] == 'image')
            <div class="col-md-4 pBottom10">
                <img src="{{$image['images']['standard_resolution']['url']}}" class="img-responsive img-thumbnail">
            </div>
        @endif
    @endforeach

@endsection