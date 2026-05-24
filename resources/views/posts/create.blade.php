@extends('layouts.app')

@section('title', 'Create Post')

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md my-8 border border-gray-200">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Create a New Post</h1>

        <form action="{{ route('posts.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" class="w-full p-2 border rounded focus:outline-blue-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Slug</label>
                <input type="text" name="slug" class="w-full p-2 border rounded focus:outline-blue-500" placeholder="e.g., my-first-post" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Body</label>
                <textarea name="body" rows="5" class="w-full p-2 border rounded focus:outline-blue-500" required></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Status</label>
                <select name="status" class="w-full p-2 border rounded focus:outline-blue-500">
                    <option value="submitted">Published</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('posts.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Post</button>
            </div>
        </form>
    </div>
@endsection
