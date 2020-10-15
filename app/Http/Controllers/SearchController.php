<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request){
        $query = $request->input('query');
$posts = Post::where('title','LIKE', "%$query%")->approved()->published()->get();

return view('search', compact('query','posts'));
    }
}
