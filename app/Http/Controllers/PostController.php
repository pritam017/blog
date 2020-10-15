<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::latest()->approved()->published()->paginate(6);
        return view('posts', compact('posts'));
    }
    public function details($slug){
        $post = Post::where('slug', $slug)->approved()->published()->first();
        $randomPost = Post::approved()->published()->take(3)->inRandomOrder()->get();
        $blogKey = 'blog_'.$post->id;
        if(!Session::has($blogKey)) {
            $post->increment('view_count');
            Session::put($blogKey,1);
        }
        return view('post', compact('post','randomPost'));
    }
    public function postByCategory($slug){
 $category = Category::where('slug',$slug)->first();
 $posts = $category->posts()->approved()->published()->get();
 return view('category-post', compact('category','posts'));
    }
    public function postByTag($slug){
        $tag = Tag::where('slug',$slug)->first();
        $posts = $tag->posts()->approved()->published()->get();
        return view('tag-post', compact('tag','posts'));
           }

}
