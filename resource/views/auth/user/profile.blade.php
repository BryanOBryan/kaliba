@php
    use Kaliba\Robas\Auth;
    $user = Auth::user();
@endphp

@if($message = flash()->error())
    <p>{{$message}}</p>
@elseif($message = flash()->success())
    <p>{{$message}}</p>
@endif

<a href={{route('dashboard')}}> Dashboard</a>

<p>name : {{$user->name}}</p>
<p>email : {{$user->email}}</p>
