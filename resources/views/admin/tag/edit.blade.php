@extends('layouts.backend.app')
@section('title', 'Tag')
@stack('css')
@section('content')
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="card">
        <div class="header">
            <h2>
                UPDATE TAG
            </h2>
        </div>
        <div class="body">
            <form action="{{ route('admin.tag.update', $tags->id) }}" method="POST">
                @csrf
                @method('PUT')
                <label for="email_address">Tag Name</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="text"
                           name="name"
                            class="form-control"
                            placeholder="Enter Your Tag Name" value="{{ $tags->name }}">
                    </div>
                </div>
               <input type="submit" class="btn btn-primary" value="Update Tag">
            </form>
        </div>
    </div>
</div>
@endsection
@stack('js')
