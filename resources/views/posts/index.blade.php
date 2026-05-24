@extends('layouts.app')

@section('title', 'All Posts')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
    <div class="max-w-4xl mx-auto p-8">

        @if(session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6 shadow">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">All Published Posts</h1>

            {{-- Only management buttons remain here, layout handles guest login links --}}
            @auth
                <div class="flex items-center space-x-3">
                    <a href="{{ route('posts.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Create New Post</a>

                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>

        <div class="space-y-4">
            @forelse($posts as $post)
                <div class="bg-white p-6 rounded-lg shadow-md flex justify-between items-start border border-gray-200">
                    <div>
                        <h2 class="text-xl font-semibold text-blue-600 hover:underline">
                            <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">
                            By {{ $post->user->name ?? 'Anonymous' }}
                        </p>
                        <p class="text-gray-700 mt-2">{{ Str::limit($post->body, 150) }}</p>
                    </div>

                    {{-- Actions shown only to logged-in users --}}
                    @auth
                        <div class="flex space-x-2">
                            <a href="{{ route('posts.edit', $post) }}" class="text-sm bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">Edit</a>

                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Delete</button>
                            </form>
                        </div>
                    @endauth
                </div>
            @empty
                <p class="text-gray-600 text-center">No published posts found.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
@endsection
