@extends('layouts.master')

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            $('.delete-like').click(function (e) {
                e.preventDefault();
                var username = $(this).data('username');
                var media = $(this).data('media');
                $('#div-like-' + media).fadeOut();
                dislikeMedia(username, media);
            });

            $('.authorize').click(function () {
                var html = '<img src="http://instagram.com/accounts/logout/" width="0" height="0" />';
                $('#logout').html(html);
                $('#logout').fadeOut();
            });
            $('.load-more').click(function(e){
                e.preventDefault();
                var username = $(this).data('username');
                var nextUrl = $(this).data('next-url');
                alert(nextUrl);
            });
        });
    </script>
@endsection

@section('content')

    <h1>Insta Accounts</h1>
    <div id="logout"></div>
    <div class="list-group">
        @if(count($instagrams))
            @foreach($instagrams as $instagram)
                <li class="list-group-item">
                    @if($instagram->access_token)
                        <i class="fa fa-succes fa-check green"></i>
                    @endif
                    <a href="{{action('InstagramsController@show',$instagram->id) }}">
                        {{$instagram->fullname}}
                    </a>
                    <span class="pull-right text-muted small authorize">
                        <a href="{{ action('InstagramsController@authorize',$instagram->id) }}">
                            @if($instagram->access_token)
                                Re
                            @endif
                            Authenticate
                        </a>
                    </span>

                </li>

            @endforeach
        @else
            <a href="{{action('InstagramsController@create')}}">Add Instagram Accounts</a>
        @endif
    </div>

    <div class="row">

        @foreach($likes as $username=>$like)
            @if(isset($like->data) && !(empty($like->data)))
                <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title text-center">
                                {{$username }}
                            </h3>
                        </div>
                        <div class="panel-body">
                            @foreach($like->data as $data)
                                @if($data->type == 'image')
                                    <div id="div-like-{{$data->id}}">
                                        <div class="col-md-4 p-bottom10 text-center">
                                            <a href="{{$data->link}}" target="_blank"><img
                                                        src="{{$data->images->thumbnail->url}}"
                                                        class="img-responsive img-thumbnail"></a>
                                            <a class="top-button delete-like red" data-username="{{$username}}"
                                               data-media="{{$data->id}}"
                                               href="#" data-toggle="tooltip" data-placement="top" title="Delete">
                                                <i class="fa fa-1x fa-close"></i> </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="panelf-footer text-center p-bottom10">
                            <div class="btn btn-primary load-more text-center" data-username="{{$username}}"
                                 data-next-url="{{$like->pagination->next_url}}">
                                load more
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        @endforeach

    </div>

@endsection