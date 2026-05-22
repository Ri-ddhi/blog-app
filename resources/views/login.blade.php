@extends('layouts.app')
@section('title' , 'Login')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section ('content')
    <div class="auth-container">
        <h1>Login</h1>
        <form method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" placeholder="Enter your email" value="{{ old('email') }}">
                @error('email') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">
                @error('password') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">Login</button>
        </form>

        <p class="auth-footer">New Here? <a href="{{ route('register') }}">Register here</a></p>
    </div>
@endsection
