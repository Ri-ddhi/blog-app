@extends('layouts.app')
@section('title', 'Account Suspended')
@section('content')
    <div style="text-align:center; padding: 50px;">
        <h1>Account Suspended</h1>

        {{-- $errors is automatically available in all Blade views --}}
        {{-- It holds any error messages we flashed with ->withErrors() --}}
        @if ($errors->has('account'))
            <p style="color:red;">{{ $errors->first('account') }}</p>
        @endif

        <p>Please contact support to reactivate your account.</p>
    </div>
@endsection
