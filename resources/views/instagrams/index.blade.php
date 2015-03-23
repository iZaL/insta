@extends('layouts.master')

@section('content')
    <h1>Insta Accounts</h1>

    <div class="list-group">
        @foreach($instagrams as $instagram)
            <a href="{{action('InstagramsController@show',$instagram->id) }}"
               class="list-group-item">
                {{$instagram->fullname}}
                <span class="pull-right">
                    <a href="https://instagram.com/oauth/authorize/?client_id={{ $clientID }}&redirect_uri={{ $redirectURI }}">Authenticate</a>
                </span>
            </a>
        @endforeach
    </div>

@endsection