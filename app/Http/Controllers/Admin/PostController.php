<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Subscriber;
use App\Models\Tag;
use App\Notifications\AuthorPostApproval;
use App\Notifications\SubscriberNotify;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Image;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $posts = Post::latest()->get();
       return view('admin.post.index', compact('posts'));
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
       return view('admin.post.create', compact('categories','tags'));
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
            $categories = Image::make($image)->resize(1600,1066)->save('png','jpg','jpeg');
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
        $post->is_approved = true;
        $post->save();
        $post->categories()->attach($request->categories);
        $post->tags()->attach($request->tags);
        $subscribers = Subscriber::all();
     foreach($subscribers as $sub){
Notification::route('mail', $sub->email)->notify(new SubscriberNotify($post));
     }


        Toastr::success('Post Sucessfully Added');
        return redirect()->route('admin.post.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);
        return view('admin.post.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);
        $categories = Category::all();
        $tags = Tag::all();
        return view('admin.post.edit', compact('post','categories','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
            'status' => 'required',
            'categories' => 'required',
            'tags' => 'required',
        ]);
        $post =  Post::find($id);
        $image = $request->file('image');
        if(isset($image)){

            $img =  time(). '.' .$image->getClientOriginalExtension();

            if(!Storage::disk('public')->exists('post')){
                Storage::disk('public')->makeDirectory('post');
            }
            if(Storage::disk('public')->exists('post/'. $post->image)){
                Storage::disk('public')->delete('post/'. $post->image);
            }
            $categories = Image::make($image)->resize(1600,1066)->save('png','jpg','jpeg');
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
        $post->is_approved = true;
        $post->save();
        $post->categories()->sync($request->categories);
        $post->tags()->sync($request->tags);

        Toastr::success('Post Sucessfully Update');
        return redirect()->route('admin.post.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if(Storage::disk('public')->exists('post/'. $post->image)){
            Storage::disk('public')->delete('post/'. $post->image);
        }
        $post->categories()->detach();
        $post->tags()->detach();
        $post->delete();

        Toastr::success('Post Sucessfully Deleted');
        return redirect()->back();
    }
    public function pending(){
        $posts = Post::where('is_approved', false)->get();
        return view('admin.post.pending', compact('posts'));

    }
    public function approve($id){
        $post = Post::find($id);
        if($post->is_approved == false){
            $post->is_approved = true;
            $post->save();

            $post->user->notify( new AuthorPostApproval($post));

            $subscribers = Subscriber::all();
            foreach($subscribers as $sub){
       Notification::route('mail', $sub->email)->notify(new SubscriberNotify($post));
            }
            Toastr::success('Post Sucessfully Approved');
            return redirect()->route('admin.pending');
        }
    }
}
