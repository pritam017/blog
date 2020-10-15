@extends('layouts.frontend.app')
@section('title', 'Category Post')
@push('css')
<link href="{{ asset('assets/frontend/css/category/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/category/responsive.css') }}" rel="stylesheet">
<style>

    .favorite-post{
        color: blue;
    }
</style>
@endpush
@section('content')

<div class="slider display-table center-text">
    <h1 class="title display-table-cell"><b>{{ $tag->name }}</b></h1>
</div><!-- slider -->
<section class="blog-area section">
    <div class="container">

        <div class="row">
@if($posts->count() > 0)
@foreach ($posts as $post)
<div class="col-lg-4 col-md-6">
    <div class="card h-100">
        <div class="single-post post-style-1">

            <div class="blog-image"><img class="user_avatar" src="{{ Storage::disk('public')->url('post/'. $post->image) }}"></div>

            <a class="avatar" href="{{ Storage::disk('public')->url('profile/'. $post->user->image) }}">
                <img src="{{ Storage::disk('public')->url('profile/'. $post->user->image) }}" width="48" height="48" alt="User" />
            </a>

            <div class="blog-info">

                <h4 class="title"><a href="{{ route('post.details', $post->slug) }}"><b>{{ $post->title }}</b></a></h4>

                <ul class="post-footer">

                    <li>
                        @guest
                    <a href="javscript:void(0);" onclick="toastr.info('You Need To Login First','Info',{
                        closeButton: true,
                        progressBar: true,
                    })"><i class="ion-heart"></i>{{ $post->favorite_to_users->count() }}</a>
                        @else
                        <a href="javascript:void(0);"
                        onclick="document.getElementById('favorite-form-{{ $post->id }}').submit()"
                        class="{{ !Auth::user()->favorite_posts->where('pivot.post_id', $post->id)->count() == 0 ? 'favorite-post' : '' }}"
                        >
                        <i class="ion-heart"></i>{{ $post->favorite_to_users->count() }}</a>
                        <form id="favorite-form-{{ $post->id }}" action="{{ route('favorite.post', $post->id) }}" style="display:  none;" method="POST">
                            @csrf
                        </form>
                    @endguest
                </li>

                    <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count  }}</a></li>
                </ul>

            </div><!-- blog-info -->
        </div><!-- single-post -->
    </div><!-- card -->
</div><!-- col-lg-4 col-md-6 -->
@endforeach
@else

<h4>No Post Found In This Category</h4>
@endif

        </div><!-- row -->



    </div><!-- container -->
</section><!-- section -->
@endsection
@push('js')

@endpush
