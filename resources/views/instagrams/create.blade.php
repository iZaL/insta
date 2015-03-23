@extends('layouts.master')

@section('content')
    <h1>Add an Instagram Account</h1>
    <form role="form" method="POST" action="{{action('InstagramsController@store')}}">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <div class="form-group">
            <label>Instagram Username</label>
            <input class="form-control" name="username" value="{{ old('username') }}">
        </div>
        {{--<div class="form-group">--}}
            {{--<label>Instagram Client ID</label>--}}
            {{--<input class="form-control" name="client_id" value="{{ old('client_id') }}">--}}
            {{--<p class="help-block">Go to <a href="http://jelled.com/instagram/access-token" target="_blank">this link</a>  for more details about how to get access token</p>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<label>Instagram Client Secret</label>--}}
            {{--<input class="form-control" name="client_secret" value="{{ old('client_secret') }}">--}}
            {{--<p class="help-block">Go to <a href="http://jelled.com/instagram/access-token" target="_blank">this link</a>  for more details about how to get access token</p>--}}
        {{--</div>--}}
        <button type="submit" class="col-lg-12 btn btn-primary btn-lg">Save</button>
    </form>
@endsection