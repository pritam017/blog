<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
   public function index()
  {
 $authors = User::authors()
 ->withCount('posts')

 ->withCount('favorite_posts')
 ->get();

 return view('admin.authors', compact('authors'));
  }
  public function destroy($id){
      $author = User::find($id);
      $author->delete();
      Toastr::success('Author Delete Successfully');
      return redirect()->back();
  }
}
