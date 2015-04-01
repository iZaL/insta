@extends('layouts.master')

@section('script')
    @parent
    <script>
        $(document).ready(function () {

            $('.authorize').click(function () {
                var html = '<img src="http://instagram.com/accounts/logout/" width="0" height="0" />';
                $('#logout').html(html);
                $('#logout').fadeOut();
            });

            $(document).on('click', '.delete-like', function (e) {
                e.preventDefault();
                var username = $(this).data('username');
                var media = $(this).data('media');
                $('#div-like-' + media).fadeOut();
                dislikeMedia(username, media);
            });

            $(document).on('click', '.load-more', function (e) {
                $(this).html('loading...');
                e.preventDefault();
                var username = $(this).data('username');
                var pagination = $(this).data('pagination');

                loadLikes(username, pagination);
//                loadLikes('zals88','1427710485934');
            });

        });


        loadAllLikes();

        function loadAllLikes() {
//            $('#instagram-likes').html(
//                    '<div class="loader"></div>'
//            );
            $.ajax({
                type: "GET",
                url: "/instagrams/loadmore",
                data: null,
                success: function (response) {
                    var template = '';
                    $.each(response, function (i, account) {
                        var filteredUsername = account.username.replace('.','-');
                        template +=
                                '<div class="col-md-6 text-center">' +
                                '<div class="panel panel-primary ">' +
                                '<div class="panel-heading">' + account.username + '</div>' +
                                '<div id="like-' + filteredUsername + '" class="panel-body">';
                        $.each(account.images, function (i, media) {
                            template +=
                                    '<div id="div-like-' + media.id + '">' +
                                    '<div class="col-md-4 p-bottom10">' +
                                    '<a href="' + media.url + '" target="_blank">' +
                                    '<img src="' + media.url + '" class="img-responsive img-thumbnail">' +
                                    '</a>' +
                                    '<a class="top-button delete-like red" data-username="' + account.username + '"' +
                                    'data-media="' + media.id + '"' +
                                    'href="#" data-toggle="tooltip" data-placement="top" title="Delete">' +
                                    '<i class="fa fa-1x fa-close"></i> </a>' +
                                    '</div>' +
                                    '</div>';
                        });
                        template +=
                                '</div>' +
                                '<div class="modal-footer">' +
                                '<div id="load-more-' + filteredUsername + '" class="btn btn-primary text-center load-more" data-username="' + account.username + '"' +
                                'data-pagination="' + account.pagination + '">load more</div>' +
                                '</div>' +
                                '</div>' +
                                '</div>';
                        var moreDiv = $('#load-more-' + filteredUsername);
                        if (account.pagination === undefined || account.pagination === null) {
                            $(moreDiv).html('No More Images');
                        } else {
                            $(moreDiv).html('load more');
                            $(moreDiv).data('pagination', account.pagination);
                        }
                    });

                    $('#instagram-likes').html(template);
                },
                error: function () {
                    //
                }

            });
        }

        function loadLikes(username, pagination) {
            var username = username;
            var pagination = pagination;
            var params = {
                "username": username,
                "pagination": pagination
            };

            $.ajax({
                type: "GET",
                url: "/instagrams/loadlike",
                data: params,
                success: function (account) {
                    var filteredUsername = account.username.replace('.','-');
                    var mediaDiv = $('#like-' + filteredUsername);
                    var moreDiv = $('#load-more-' + account.username);

                    if (account.images) {
                        var mediaTemplate = '';
                        $.each(account.images, function (i, media) {
                            mediaTemplate +=
                                    '<div id="div-like-' + media.id + '">' +
                                    '<div class="col-md-4 p-bottom10">' +
                                    '<a href="' + media.url + '" target="_blank">' +
                                    '<img src="' + media.url + '" class="img-responsive img-thumbnail">' +
                                    '</a>' +
                                    '<a class="top-button delete-like red" data-username="' + account.username + '"' +
                                    'data-media="' + media.id + '"' +
                                    'href="#" data-toggle="tooltip" data-placement="top" title="Delete">' +
                                    '<i class="fa fa-1x fa-close"></i> </a>' +
                                    '</div>' +
                                    '</div>';
                        });

                        // update pagination max like id
                        if(account.pagination) {
                            $(moreDiv).data('pagination', account.pagination);
                            $(moreDiv).html('load more');
                        } else {
                            $(moreDiv).data('pagination', null);
                            $(moreDiv).html('No More Data');
                        }
                        $(mediaDiv).append(mediaTemplate);
                        // update div with new images
                    } else {
                        $(moreDiv).data('pagination', null);
                        $(moreDiv).html('No More Data');
                    }

                },
                error: function () {
                    //
                }

            });
        }


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
        <div id="instagram-likes">

        </div>
    </div>

@endsection