@extends('layouts.auth')

@section('content')
<p class="login-box-msg" style="font-size: 1.1rem; color: #555;">Sign in to start your session</p>

@if (session('status'))
    <div class="alert alert-success" role="alert">
        {{ session('status') }}
    </div>
@endif

<form method="POST" action="{{ route('login') }}">
    @csrf
    
    <div class="input-group mb-4">
        <input type="text" 
               class="form-control @error('username') is-invalid @enderror" 
               name="username" 
               value="{{ old('username') }}" 
               placeholder="Username"
               required 
               autocomplete="username" 
               autofocus
               style="height: 50px;">
        <div class="input-group-append">
            <div class="input-group-text" style="width: 45px; justify-content: center;">
                <span class="fas fa-user"></span>
            </div>
        </div>
        @error('username') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
    </div>

    <div class="input-group mb-4">
        <input type="password" 
               class="form-control @error('password') is-invalid @enderror" 
               name="password" 
               placeholder="Password"
               required 
               autocomplete="current-password"
               style="height: 50px;">
        <div class="input-group-append">
            <div class="input-group-text" style="width: 45px; justify-content: center;">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @error('password') <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span> @enderror
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </button>
        </div>
    </div>
</form>

<div class="text-center mt-4">
    <small class="text-muted" style="font-size: 13px;">
        &copy; {{ date('Y') }} SPARTAN System. All rights reserved.
    </small>
</div>
@endsection