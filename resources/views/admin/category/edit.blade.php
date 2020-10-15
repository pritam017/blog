@extends('layouts.backend.app')
@section('title', 'Tag')
@stack('css')
@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>
                UPDATE CATEGORY
            </h2>
        </div>
        <div class="body">
            <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <label for="email_address">Category Name</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="text"
                           name="name"
                            class="form-control"
                            placeholder="Enter Your Tag Name" value="{{ $category->name }}">
                    </div>
                </div>
                <label for="email_address">Category Image</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="file"
                            name="image"
                           >
                        </div>
                        <img class="user_avatar" src="{{ Storage::disk('public')->url('category/'. $category->image) }}" width="70" height="80">
                    </div>
               <input type="submit" class="btn btn-primary" value="Update Category">
            </form>
        </div>
    </div>
</div>
@endsection
@stack('js')
