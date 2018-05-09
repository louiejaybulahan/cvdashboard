@extends('layouts.login')

@section('content')
<div class="loginerror"><p>Invalid username or password</p></div>
<form class="form-horizontal" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}
    <p>
        <label for="username" class="bebas">username</label>
        <input type="text" id="username" name="username" class="radius2" value="{{ old('username') }}" required autofocus/>
        @if ($errors->has('username'))
        <span class="help-block">
            <strong>{{ $errors->first('username') }}</strong>
        </span>
        @endif
    </p>
    <p>
        <label for="password" class="bebas">password</label>
        <input id="password" type="password" class="form-control" name="password" required>
        @if ($errors->has('password'))
        <span class="help-block">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </p>
    <p>
        <button class="radius3 bebas" type="submit">Sign in</button>                            
    </p>                        
    <p><a href="{{ route('password.request') }}" class="whitelink small">Can't access your account?</a></p>                                         
</form>

@endsection
