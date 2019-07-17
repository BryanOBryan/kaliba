@if($message = flash()->error())
    <p>{{$message}}</p>
@elseif($message = flash()->success())
    <p>{{$message}}</p>
@endif

<form class="login-form" action="{{route('auth/password/forgot')}}" method="post">
    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>FORGOT PASSWORD</h3>
    {{csrf_field()}}
    <div class="form-group">
        <label class="control-label">EMAIL ADDRESS</label>
        <input class="form-control" name="email" type="email"  placeholder="Email"  autofocus required>
    </div>

    <div class="form-group btn-container">
        <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>Submit</button>
    </div>
</form>