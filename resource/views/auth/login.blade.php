@if($message = flash()->error())
    <p>{{$message}}</p>
@elseif($message = flash()->success())
    <p>{{$message}}</p>
@endif

@if(count($errors))
    @foreach($errors as $error)
        {{$error}}<br/>
    @endforeach
@endif

<form class="login-form" action="{{route('auth/login')}}" method="post">
    <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>SIGN IN</h3>
    {{csrf_field()}}
    <div class="form-group">
        <label class="control-label">USERNAME</label>
        <input class="form-control" name="username" type="text"  placeholder="Email" value="{{$input->username}}"  autofocus >
    </div>
    <div class="form-group">
        <label class="control-label">PASSWORD</label>
        <input class="form-control" name="password" type="password"  placeholder="Password" >
    </div>
    <div class="form-group">
        <div class="utility">
            <div class="animated-checkbox">
                <label>
                    <input type="checkbox" name="remember"><span class="label-text">Remember me</span>
                </label>
            </div>
        </div>
    </div>
    <div class="form-group btn-container">
        <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>SIGN IN</button>
    </div>
</form>
<a href="{{route('auth/password/forgot')}}">Forgot Password</a>
