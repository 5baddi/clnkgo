@extends('layouts.auth')

@section('title') Reset account password @endsection

@section('form')
    <h1 class="title1">Forgot password?</h1>
    <p class="text-muted">Enter your email and we’ll send you a reset link</p>
    <form method="POST" action="{{ route('auth.reset.token') }}">
        @csrf
        <div class="box-form-design1">
            <div class="text-start">
                @if(Session::has('error'))
                <p class="invalid-feedback">{{ Session::get('error') }}</p>
                @endif
                @if(Session::has('success'))
                <p class="valid-feedback" style="display: block;">{{ Session::get('success') }}</p>
                @endif
            </div>
            <div class="form-group-custom1">
                <label for="email" class="label-custom1">E-mail</label>
                <input id="email" name="email" value="{{ old('email') }}" type="email" class="input-custom1 @if($errors->has('email')) is-invalid @endif" placeholder="E-mail" autofocus required/>
                @if($errors->has('email'))
                <div class="invalid-feedback">
                    {{ $errors::first('email') }}
                </div>
                @endif
            </div>
            <div class="box-btn-submit">
                <button class="btn-design1" type="submit">Reset password</button>
            </div>
            <p class="have-account">
                back to <a href="{{ route('signin') }}" class="link-design1">Sign in</a> page
            </p>
        </div>
    </form> 
@endsection