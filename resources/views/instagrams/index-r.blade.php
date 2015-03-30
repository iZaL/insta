
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
            <div class="btn btn-primary  load-more  text-center" data-username="{{$username}}"
                 data-nexturl="{{$like->pagination->next_url}}">load more
            </div>
        </div>
    </div>
</div>