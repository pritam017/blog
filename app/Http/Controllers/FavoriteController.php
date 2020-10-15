<?php

namespace App\Http\Controllers;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function favorite($post){

        $user = Auth::user();
        $isFavorite = $user->favorite_posts()->where('post_id', $post)->count();
if($isFavorite == 0){
    $user->favorite_posts()->attach($post);
    Toastr::success('This Post Added Your Favorite List', 'Success');
    return redirect()->back();
}else{
    $user->favorite_posts()->detach($post);
    Toastr::success('This Post Remove From Your Favorite List', 'Success');
    return redirect()->back();
}
    }
}
