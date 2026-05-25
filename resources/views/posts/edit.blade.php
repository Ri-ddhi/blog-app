@extends('layouts.app')

@section('title', 'Edit Post - ' . $post->title)

@push('styles')
    <script src="https://cdn.tailwindcss.com"></script>
@endpush

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md my-8 border border-gray-200">
        <h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Post</h1>

        <form action="{{ route('posts.update', $post) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Title</label>
                <input type="text" name="title" value="{{ $post->title }}" class="w-full p-2 border rounded focus:outline-blue-500" >
                @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Slug</label>
                <input type="text" name="slug" value="{{ $post->slug }}" class="w-full p-2 border rounded focus:outline-blue-500" >
                @error('slug')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Categories</label>
                <div class="grid grid-cols-2 gap-2 p-3 border rounded bg-gray-50 max-h-40 overflow-y-auto @error('categories') border-red-500 @enderror">
                    @foreach($categories as $category)
                        <label class="inline-flex items-center space-x-2 text-gray-700 cursor-pointer">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}" class="rounded text-blue-600 focus:ring-blue-500" {{ is_array(old('categories')) && in_array($category->id, old('categories')) ? 'checked' : '' }}>
                            <span>{{ $category->name }}</span>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-1">You can select multiple categories for this post.</p>
                @error('categories')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Body</label>
                <textarea name="body" rows="5" class="w-full p-2 border rounded focus:outline-blue-500" >{{ $post->content }}</textarea>
                @error('body')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Status</label>
                <select name="status" class="w-full p-2 border rounded focus:outline-blue-500">
                    <option value="submitted" {{ $post->status == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ $post->status == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
                @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{ route('posts.index') }}" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Cancel</a>
                <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600">Update Post</button>
            </div>
        </form>
    </div>
@endsection

