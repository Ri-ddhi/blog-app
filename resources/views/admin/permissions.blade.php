@extends('layouts.app')
@section('title', 'Manage Permissions')
@section('content')
    <div style="max-width:700px; margin:30px auto;">
        <h1>Manage Role Permissions</h1>

        {{-- Show success flash message if it exists --}}
        @if(session('success'))
            <p style="color:green;">{{ session('success') }}</p>
        @endif

        {{-- Loop through every role (admin, user, guest, etc.) --}}
        @foreach($roles as $role)
            <div style="border:1px solid #ccc; padding:20px; margin-bottom:20px; border-radius:8px;">
                <h2>Role: {{ $role->name }}</h2>

                {{-- Each role has its own form so we can submit them separately --}}
                <form action="{{ route('admin.permissions.update') }}" method="POST">
                    @csrf {{-- Security token: prevents cross-site request forgery --}}

                    {{-- Hidden field: tells the controller WHICH role we're updating --}}
                    <input type="hidden" name="role_id" value="{{ $role->id }}">

                    <p><strong>Select permissions for this role:</strong></p>

                    {{-- $permissions is ALL permissions from the DB --}}
                    @foreach($permissions as $permission)
                        <label style="display:block; margin:5px 0;">
                            <input
                                type="checkbox"
                                name="permission_ids[]"
                                value="{{ $permission->id }}"
                                {{--
                                    $role->permissions is the collection of permissions
                                    already assigned to this role (loaded with with('permissions'))
                                    ->contains('id', $permission->id) checks if this permission
                                    is already in that collection — if so, pre-check the box
                                --}}
                                {{ $role->permissions->contains('id', $permission->id) ? 'checked' : '' }}
                            >
                            {{ $permission->name }}
                        </label>
                    @endforeach

                    <button type="submit" style="margin-top:10px; padding:8px 16px;">
                        Save Permissions
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
