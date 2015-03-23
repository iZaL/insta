@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
    @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('errors'))
<div class="alert alert-danger alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @foreach ($message->all() as $m)
      <li>{{ $m }}  </li>
    @endforeach
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
            @else
        {{ $message }}
    @endif
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
    @if(is_array($message))
        @foreach ($message as $m)
            {{ $m }}
        @endforeach
            @else
        {{ $message }}
    @endif
</div>
@endif
