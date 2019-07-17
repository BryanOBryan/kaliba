@if($message = flash()->error())
    <p>{{$message}}</p>
@elseif($message = flash()->success())
    <p>{{$message}}</p>
@endif

<form class="login-form" action="{{route('auth/password/reset')}}" method="post">
    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>RESET PASSWORD</h3>
    {{csrf_field()}}
    <input type="hidden" name="email" value="{{$email}}" >
    <div class="form-group">
        <label class="control-label">NEW PASSWORD</label>
        <input class="form-control" name="password" type="password"   autofocus required>
    </div>

    <div class="form-group btn-container">
        <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Submit</button>
    </div>
</form>