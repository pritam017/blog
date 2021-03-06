<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use App\Notifications\PostNotification;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Image;
use File;
use Illuminate\Support\Facades\Notification;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Auth::user()->posts()->latest()->get();
        return view('author.post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $tags = Tag::all();
       return view('author.post.create', compact('categories','tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|unique:posts',
            'image' => 'required',
            'text' => 'required',
            'status' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);
        $post = new Post();
        $image = $request->file('image');
        if(isset($image)){

            $img =  time(). '.' .$image->getClientOriginalExtension();
            if(!Storage::disk('public')->exists('post')){
                Storage::disk('public')->makeDirectory('post');
            }
            $categories = Image::make($image)->resize(1600,1066)->save($img, 90);
            Storage::disk('public')->put('post/'.$img,$categories);
            $post->image = $img;
        }else{
            $img = 'default.png';
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug  = Str::slug($request->title);
        $post->text = $request->text;
        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }
        $post->is_approved = false;
        $post->save();
        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);

        $user = User::where('role_id', 1)->get();
        Notification::send($user, new PostNotification($post));

        Toastr::success('Post Sucessfully Added');
        return redirect()->route('author.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You Are Not Authorise To Access','Error');
            return redirect()->back();
        }
        return view('author.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You Are Not Authorise To Access','Error');
            return redirect()->back();
        }
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You Are Not Authorise To Access','Error');
            return redirect()->back();
        }
        $request->validate([
            'title' => 'required',
            'text' => 'required',
            'status' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);

        $image = $request->file('image');
        if(isset($image)){

            $img =  time(). '.' .$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('post')){
                Storage::disk('public')->makeDirectory('post');
            }
            if(Storage::disk('public')->exists('post/'. $post->image)){
                Storage::disk('public')->delete('post/'. $post->image);
            }
            $categories = Image::make($image)->resize(1600,1066)->save($img, 90);
            Storage::disk('public')->put('post/'.$img,$categories);
            $post->image = $img;
        }else{
            $img = $post->image;
        }

        $post->user_id = Auth::id();
        $post->title = $request->title;
        $post->slug  = Str::slug($request->title);
        $post->text = $request->text;
        if(isset($request->status)){
            $post->status = true;
        }else{
            $post->status = false;
        }
        $post->is_approved = false;
        $post->save();
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Sucessfully Update');
        return redirect()->route('author.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if($post->user_id != Auth::id()){
            Toastr::error('You Are Not Authorise To Access','Error');
            return redirect()->back();
        }
        if(Storage::disk('public')->exists('post/'. $post->image)){
            Storage::disk('public')->delete('post/'. $post->image);
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Post Sucessfully Deleted');
        return redirect()->back();
    }

}

