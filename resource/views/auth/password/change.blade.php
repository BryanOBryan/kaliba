@if($message = flash()->error())
    <p>{{$message}}</p>
@elseif($message = flash()->success())
    <p>{{$message}}</p>
@endif
