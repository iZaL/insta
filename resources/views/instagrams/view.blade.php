@extends('layouts.master')

@section('script')
    @parent
    <script>
        $(document).ready(function () {

            $('.delete-feed').click(function (e) {
                e.preventDefault();
                var username = $(this).data('username');
                var media = $(this).data('media');
                $('#div-feed-' + media).fadeOut();
                dislikeMedia(username, media);
            });

            $('.delete-like').click(function (e) {
                e.preventDefault();
                var username = $(this).data('username');
                var media = $(this).data('media');
                $('#div-like-' + media).fadeOut();
                dislikeMedia(username, media);
            });

        });
    </script>
@endsection

@section('content')
    @if($instagramUser->data)
        <div class="col-md-4">
            <div class="row text-center">
                <div class="col-md-6">
                    <img src="{{ $instagramUser->data->profile_picture }}" class="img-thumbnail img-responsive">
                </div>
                <div class="col-md-6">
                    <h1 style="text-align: center; vertical-align: middle">{{ $instagramUser->data->full_name }}</h1>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <h4 style="text-align: center">FOLLOWINGS {{ count($follows->data) }}</h4>
                </div>
                <div class="col-md-6">
                    <h4 style="text-align: center">FOLLOWERS {{ count($followers->data) }}</h4>
                </div>
            </div>

        </div>
    @endif
    <div class="col-md-8">
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        Latest Feeds
                    </h3>
                </div>
                <div class="panel-body">
                    @if(isset($feeds->data))
                        @foreach($feeds->data as $data)
                            @if($data->type == 'image')
                                <div id="div-feed-{{$data->id}}">
                                    <div class="col-md-3 p-bottom10 ">
                                        <a href="{{$data->link}}" target="_blank"><img
                                                    src="{{$data->images->thumbnail->url}}"
                                                    class="img-responsive img-thumbnail"></a>
                                        @if($data->user_has_liked)
                                            <a class="top-button delete-feed "
                                               data-username="{{$instagramUser->data->username}}"
                                               data-media="{{$data->id}}"
                                               href="#"><i class="fa fa-1x fa-close red"></i> </a>
                                        @else
                                            <a class="top-button make-like "
                                               data-username="{{$instagramUser->data->username}}"
                                               data-media="{{$data->id}}"
                                               href="#"><i class="fa fa-1x fa-check"></i> </a>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        Latest Uploads
                    </h3>
                </div>
                <div class="panel-body">
                    @if(isset($medias->data))
                        @foreach($medias->data as $data)
                            @if($data->type == 'image')
                                <div class="col-md-3 p-bottom10">
                                    <a href="{{$data->link}}" target="_blank"><img
                                                src="{{$data->images->thumbnail->url}}"
                                                class="img-responsive img-thumbnail"></a>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title text-center">
                        Latest Media Likes
                    </h3>
                </div>
                <div class="panel-body">
                    @if(isset($likes->data))
                        @foreach($likes->data as $data)
                            @if($data->type == 'image')
                                <div id="div-like-{{$data->id}}">
                                    <div class="col-md-3 p-bottom10">
                                        <a href="{{$data->link}}" target="_blank"><img
                                                    src="{{$data->images->thumbnail->url}}"
                                                    class="img-responsive img-thumbnail"></a>
                                        <a class="top-button delete-like red"
                                           data-username="{{$instagramUser->data->username}}" data-media="{{$data->id}}"
                                           href="#"><i class="fa fa-1x fa-close"></i> </a>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        <hr>


    </div>

@endsection