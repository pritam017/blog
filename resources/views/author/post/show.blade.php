@extends('layouts.backend.app')
@section('title', 'Post')
@push('css')

<!-- Bootstrap Select Css -->
<link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

@endpush
@section('content')
@if ($post->is_approved == true)
<button class="btn btn-success disabled pull-right">
    <i class="material-icons">done</i>
    <span>Approved</span>
</button> <br> <br>
    @else
    <a href="" class="btn btn-danger disabled pull-right">
        <i class="material-icons">pending</i>
        <span>Pending</span>
    </a> <br><br>
@endif
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <a href="{{ route('author.post.index') }}" class="btn btn-danger float-left">Back</a> <br><br>
       <div class="card">
        <div class="header">
            <h2>
               {{ $post->title }}
               <small>Posted By <b><a href=""></a></b> on {{ $post->created_at->toFormattedDateString() }}</small>
            </h2>
        </div>
        <div class="body">
<p>{!!  $post->text  !!}</p>
        </div>
       </div>
    </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="card">
                <div class="header bg-blue">
                    <h2>
                        Categories
                    </h2>
                </div>
                <div class="body">
                    @foreach ($post->categories as $cat)
                        <span class="label bg-cyan">{{ $cat->name }}</span>
                    @endforeach
                </div>
            </div>
            <div class="card">
                <div class="header bg-brown">
                    <h2>
                        Tags
                    </h2>
                </div>
                <div class="body">
                    @foreach ($post->tags as $tag)
                    <span class="label bg-purple">{{ $tag->name }}</span>
                @endforeach
                </div>
            </div>
            <div class="card">
                <div class="header">
                    <h2>
                        Featured Image
                    </h2>
                </div>
                <div class="body">
                    <img  src="{{ Storage::disk('public')->url('post/'. $post->image) }}" width="250" height="120">
                </div>
            </div>
        </div>




@endsection
@push('js')

<!-- Select Plugin Js -->
<script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>

@endpush
