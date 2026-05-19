@extends('layouts.app')
@section('title' , 'Register')
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endpush

@section ('content')
    <div class="auth-container">
        <h1>Register</h1>
        <form method="POST" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" placeholder="Enter your name" value="{{ old('name') }}">
                @error('name') <p class="error-message">{{ $message }}</p> @enderror
            </div>

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

            <div class="form-group">
                <label for="password_confirmation">Confirm Password:</label>
                <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm your password">
                @error('password_confirmation') <p class="error-message">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn-submit">Register</button>
        </form>

        <p class="auth-footer">Already have an account? <a href="{{ route('login') }}">Login</a></p>
    </div>
@endsection
