@extends('layouts.master')

@section('content')
    <style>
        .pBottom10{
            padding-bottom: 10px;
        }
    </style>
    <h1>{{ $instagram->fullname }}</h1>

    <a href="https://instagram.com/oauth/authorize/?client_id={{ $clientID }}&redirect_uri={{ $redirectURI }}">Authenticate</a>

@endsection