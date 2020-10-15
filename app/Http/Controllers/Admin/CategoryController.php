<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Image;
use File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
            'name' => 'required|unique:categories',
            'image' => 'required',
        ]);
        $category = new Category();
        $image = $request->file('image');
        if(isset($image)){

            $img =  time(). '.' .$image->getClientOriginalExtension();
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }
            $categories = Image::make($image)->resize(1600,479)->save('png','jpg','jpeg');
            Storage::disk('public')->put('category/'.$img,$categories);


            if(!Storage::disk('public')->exists('category/slider')) {
                Storage::disk( 'public')->makeDirectory('category/slider');
            }
            $slider = Image::make($image)->resize(500,333)->save('png','jpg','jpeg');
            Storage::disk('public')->put('category/slider/'.$img,$slider);
            $category->image = $img;
            $slider->image = $img;
        }else{
            $img = 'default.png';
        }

        $category->slug = Str::slug($request->name);
        $category->name = $request->name;


        $category->save();
        Toastr::success('Category Added Successfully');
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::find($id);
        return view('admin.category.edit', compact('category'));
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
            'name' => 'required',

        ]);
        $category =  Category::find($id);
        $image = $request->file('image');
        if(isset($image)){

            $img =  time(). '.' .$image->getClientOriginalExtension();
            if(!Storage::disk('public')->exists('category')){
                Storage::disk('public')->makeDirectory('category');
            }
            if(!Storage::disk('public')->exists('category')) {
                Storage::disk( 'public')->makeDirectory('category');
            }
            $categories = Image::make($image)->resize(1600,479)->save('png','jpg','jpeg');
            Storage::disk('public')->put('category/'.$img,$categories);


            if(!Storage::disk('public')->exists('category/slider')) {
                Storage::disk( 'public')->makeDirectory('category/slider');
            }
            if(!Storage::disk('public')->exists('category/slider')) {
                Storage::disk( 'public')->makeDirectory('category/slider');
            }
            $slider = Image::make($image)->resize(500,333)->save('png','jpg','jpeg');
            Storage::disk('public')->put('category/slider/'.$img,$slider);
            $category->image = $img;
            $slider->image = $img;
        }else{
            $img = $category->image;
        }

        $category->slug = Str::slug($request->name);
        $category->name = $request->name;


        $category->save();
        Toastr::success('Category Updated Successfully');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category =  Category::find($id);
        if(Storage::disk('public')->exists('category/'. $category->image)){
            Storage::disk('public')->delete('category/'. $category->image);
        }
        if(Storage::disk('public')->exists('category/slider/'. $category->image)){
            Storage::disk('public')->delete('category/slider/'. $category->image);
        }
        $category->delete();
        Toastr::success(' Delete Successfully');
        return redirect()->back();
    }
}
