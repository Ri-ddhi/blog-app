<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user')
            ->where('status', 'submitted')
            ->latest('id')
            ->paginate(8);
        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        // 'create' in PostPolicy checks if user is logged in (any auth user can create)
        Gate::authorize('create', Post::class);
        $categories = Category::all();
        return view('posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request)
    {
        Gate::authorize('create', Post::class);
        $validated = $request->validated();

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Post::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        $post = Post::create([
            'title'   => $validated['title'],
            'slug'    => $slug,           // ✅ use the generated $slug, not validated['slug']
            'body'    => $validated['body'],
            'status'  => $validated['status'],
            'user_id' => auth()->id(),
        ]);

        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('posts.index')->with('success', 'Post created!');
    }

    public function show(Post $post)
    {
        // 'view' in PostPolicy allows anyone (even guests) to see a post
        Gate::authorize('view', $post);
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        // 'update' in PostPolicy checks: is this user the post owner?
        Gate::authorize('update', $post);
        $categories = Category::all();
        return view('posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        // ✅ was wrongly using 'create' before — now correctly uses 'update'
        Gate::authorize('update', $post);
        $validated = $request->validated();

        $post->update([
            'title'  => $validated['title'],
            'slug'   => $validated['slug'],
            'body'   => $validated['body'],
            'status' => $validated['status'],
        ]);

        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('posts.index')->with('success', 'Post updated!');
    }

    public function destroy(Post $post)
    {
        // 'delete' in PostPolicy: owner OR admin (admin handled by before())
        Gate::authorize('delete', $post);
        $post->categories()->detach(); // remove pivot rows first
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted!');
    }
}
