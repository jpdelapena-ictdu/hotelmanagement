@extends('main')
@section('styles')
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection
@section('content')
<div class="row">
<div class="form" id="form">
  <form method="POST" action="{{ route('admin.login.submit') }}" aria-label="{{ __('Login') }}">
                        @csrf
  <div class="field email">
    <div class="icon"></div>
    <input id="email" type="email" class="input form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus/>
    @if ($errors->has('email'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
    @endif
  </div>
  <div class="field password">
    <div class="icon"></div>
    <input id="password" type="password" class="input form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required/>
    @if ($errors->has('password'))
        <span class="invalid-feedback" role="alert">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
    @endif
                                    
  </div>
  <button class="button" id="submit">LOGIN
    <div class="side-top-bottom"></div>
    <div class="side-left-right"></div>
  </button>
  <small><a href="#"></small>
  </form>
</div>
</div>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>

  

    <script  src="{{ asset('js/index.js') }}"></script>
@endsection
