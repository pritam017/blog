<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function GuzzleHttp\Promise\all;

class CommentController extends Controller
{
    public function store(Request $request, $post){
$request->validate([
'comment' => 'required',
]);

$comment = new Comment();
$comment->post_id = $post;
$comment->user_id =  Auth::id();
$comment->comment = $request->comment;
$comment->save();
Toastr::success('Comment Post Successfully');
return redirect()->back();
    }
}

