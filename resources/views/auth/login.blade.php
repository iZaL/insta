@extends('layouts.one_col')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div class="login-panel panel panel-default">
                <div class="panel-heading"><h3>Please Sign In</h3></div>
                <div class="panel-body">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There were some problems with your input.<br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form role="form" method="POST" action="{{ url('/auth/login') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">

                        <fieldset>
                            <div class="form-group">
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" placeholder="E-mail" autofocus>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                            <button type="submit" class="btn btn-primary">Login</button>

                            <a class="btn btn-link" href="{{ url('/password/email') }}">Forgot Your
                                Password?</a>
                            <!-- Change this to a button or input when using this as a form -->
                        </fieldset>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

