@extends('layouts.app')

@section('title', $post->title)

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md my-8 border border-gray-200">
        <a href="{{ route('posts.index') }}" class="text-blue-500 hover:underline text-sm">&larr; Back to all posts</a>

        <h1 class="text-4xl font-bold text-gray-900 mt-4 mb-2">{{ $post->title }}</h1>

        <div class="flex text-sm text-gray-500 space-x-4 mb-6">
            <span>By <strong>{{ $post->user->name ?? 'Anonymous' }}</strong></span>
            <span>&bull;</span>
            <span>Views: {{ $post->view_count }}</span>
        </div>

        <div class="text-gray-800 leading-relaxed whitespace-pre-line">
            {{ $post->content }}
        </div>
    </div>
@endsection
