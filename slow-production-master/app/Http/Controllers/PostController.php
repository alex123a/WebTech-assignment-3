<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostLike;
use App\Models\User;
use Illuminate\Http\Request;

class PostController extends Controller {

    public function store(Request $request) {
        $validated = $request->validate([
            'content' => ['required', 'min:10']
        ]);

        auth()->user()->posts()->create([
            'content' => $validated['content']
        ]);

        return redirect()->back()->with('success', 'Your post was created');
    }

    public function like(Post $post) {
        if (auth()->check()) {
            $postLike = new PostLike();
            $postLike->user_id = auth()->id();
            $postLike->post_id = $post->id;
            // if (PostLike::where('user_id', auth()->id())->where('post_id', $post->id)->get() == null) {
            $postLike->save();
            // }
            return redirect()->route("home");
        }
    }

    public function dislike(Post $post) {
        $postLike = PostLike::where('user_id', auth()->id())->where('post_id', $post->id)->first();
        $postLike->delete();
        return redirect()->route("home");
    }

    public function delete(Post $post) {
        if(auth()->check() && (auth()->user()->id == $post["user_id"] || auth()->user()->role == "moderator" )) {
            $post->delete();
            session()->flash("success", "The post was deleted");
            return redirect()->route("home");
        } else {
            abort(403);
        }
    }

}
