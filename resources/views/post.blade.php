@extends('layouts.frontend.app')
@section('title')
{{ $post->title }}
@endsection
@push('css')
<link href="{{ asset('assets/frontend/css/single-post/styles.css') }}" rel="stylesheet">

<link href="{{ asset('assets/frontend/css/single-post/responsive.css') }}" rel="stylesheet">
<style>
    .header-bg{

background-image: url({{ Storage::disk('public')->url('post/'. $post->image) }});
background-size: cover;
height: 80vh;
width: 100%;
    }
    .favorite-post{
        color: blue;
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/fontawesome.min.css" crossorigin="anonymous">
@endpush
@section('content')
<div class="header-bg">
</div><!-- slider -->
<section class="post-area section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-12 no-right-padding">
                <div class="main-post">
                    <div class="blog-post-inner">
                        <div class="post-info">
                            <div class="left-area">
                                <a class="avatar" href="#"><img src="{{ Storage::disk('public')->url('profile/'. $post->user->image) }}"  alt="User" /></a>
                            </div>
                            <div class="middle-area">
                                <a class="name" href="#"><b>{{ $post->user->name }}</b></a>
                                <h6 class="date">on {{ $post->created_at->diffForHumans() }}</h6>
                            </div>
                        </div><!-- post-info -->
                        <h3 class="title"><a href="#"><b>{{ $post->title }}</b></a></h3>
                        <p class="para">
                            {!! html_entity_decode($post->text) !!}
                        </p>
                        <ul class="tags">
                            @foreach ($post->tags as $tag)
                            <li><a href="{{ route('postByTag',$tag->slug) }}">{{ $tag->name }}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- blog-post-inner -->
                    <div class="post-icons-area">
                        <ul class="post-icons">
                            <li>  @guest
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
                                @endguest</li>

                    <li><a href="#"><i class="ion-eye"></i>{{ $post->view_count  }}</a></li>
                        </ul>
                        <ul class="icons">
                            <li>SHARE : </li>
                            <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                            <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                            <li><a href="#"><i class="ion-social-pinterest"></i></a></li>
                        </ul>
                    </div>
                </div><!-- main-post -->
            </div><!-- col-lg-8 col-md-12 -->
            <div class="col-lg-4 col-md-12 no-left-padding">
                <div class="single-post info-area">
                    <div class="sidebar-area about-area">
                        <h4 class="title"><b>ABOUT AUTHOR</b></h4>
                        <p>{{ $post->user->about }}</p>
                    </div>
                    <div class="tag-area">
                        <h4 class="title"><b>CATEGORIES</b></h4>
                        <ul>
                            @foreach ($post->categories as $cat)
                            <li><a href="{{ route('postByCategory', $cat->slug) }}">{{ $cat->name }}</a></li>
                            @endforeach
                        </ul>
                    </div><!-- subscribe-area -->
                </div><!-- info-area -->
            </div><!-- col-lg-4 col-md-12 -->
        </div><!-- row -->
    </div><!-- container -->
</section><!-- post-area -->
<section class="recomended-area section">
    <div class="container">
        <div class="row">
@foreach ($randomPost as $post)
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

        </div><!-- row -->

    </div><!-- container -->
</section>
<div class="container">
    <div id="disqus_thread"></div>
</div>

@endsection
@push('js')
<script>

    /**
    *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
    *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/

    var disqus_config = function () {
    this.page.url = PAGE_URL;  // Replace PAGE_URL with your page's canonical URL variable
    this.page.identifier = PAGE_IDENTIFIER; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
    };

    (function() { // DON'T EDIT BELOW THIS LINE
    var d = document, s = d.createElement('script');
    s.src = 'https://sarker.disqus.com/embed.js';
    s.setAttribute('data-timestamp', +new Date());
    (d.head || d.body).appendChild(s);
    })();
    </script>
    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
@endpush

