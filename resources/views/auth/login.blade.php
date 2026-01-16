@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="login-header">
    <h1>Welcome back</h1>
    <p>Enter your credentials to access your account</p>
</div>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label for="email" class="form-label">Email</label>
        <input 
            id="email" 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            name="email" 
            value="{{ old('email') }}" 
            placeholder="name@example.com"
            required 
            autocomplete="email" 
            autofocus
        >
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password" class="form-label">Password</label>
        <input 
            id="password" 
            type="password" 
            class="form-control @error('password') is-invalid @enderror" 
            name="password" 
            placeholder="Enter your password"
            required 
            autocomplete="current-password"
        >
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
            <label class="form-check-label" for="remember">Remember me</label>
        </div>
    </div>

    <button type="submit" class="btn-primary">
        Sign In
    </button>
</form>

<p class="footer-text">
    By signing in, you agree to our <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a>
</p>
@endsection
