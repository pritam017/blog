@extends('layouts.backend.app')
@section('title', 'Post')
@push('css')
<style>
    .inner{
        padding-left: 13px !important;
    }
    .bs-searchbox{
       float: left;
    }
</style>
<!-- Bootstrap Select Css -->
<link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

@endpush
@section('content')
<form action="{{ route('admin.post.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
       <div class="card">
        <div class="header">
            <h2>
                ADD NEW POST
            </h2>
        </div>
        <div class="body">

                <label for="title">Post Title</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="text"
                           name="title"
                            class="form-control"
                            placeholder="Enter Your Post Title">
                    </div>
                </div>
                <label for="image">Post Image</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="file"
                            name="image"
                           >
                        </div>
                    </div>
                    <div class="form-group">
                       <label for="">Publish</label>
                       <select class="" name="status">
                           <option value="">Select Status</option>
                            <option value="0">Not Publish</option>
                            <option value="1">Publish</option>
                       </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
            <div class="card">
                <div class="header">
                    <h2>
                        CATEGORIES & TAGS
                    </h2>
                </div>
                    <div class="body">
                        <div class="form-group from-float">
                            <div class="form-line {{ $errors->has('categories')? 'focused-error' : '' }}">
                                <label for="category">Categories</label>
                                <select
                                    name="categories[]"
                                    id="category"
                                    class="form-control show-tick"
                                    data-live-search="true"
                                    multiple="multiple">
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                       </div>
                    </div>
                    <div class="body">
                        <div class="form-group from-float">
                            <div class="form-line {{ $errors->has('categories')? 'focused-error' : '' }}">
                                <label for="tag">Tags</label>
                                <select
                                    name="tags[]"
                                    id="tag"
                                    class="form-control show-tick"
                                    data-live-search="true"
                                    multiple="multiple">
                                    <option value="">Select Tag</option>
                                    @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                       </div>
                       <div class="btn-group">
                        <input type="submit" class="btn btn-primary btn-sm" value="Submit">
                    </div>
                </div>
            </div>
        </div>
        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="header">
                        <h2>
                            BODY
                        </h2>
                    </div>
                    <div class="body">
                        <textarea id="tinymce" name="text">

                          </textarea>
                    </div>
                </div>
            </div>
        </div>


    </form>

@endsection
@push('js')

<!-- Select Plugin Js -->
<script src="{{ asset('assets/backend/plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
<!-- TinyMCE -->
<script src="{{ asset('assets/backend/plugins/tinymce/tinymce.js') }}"></script>
<script>
    $(function(){
        //TinyMCE
        tinymce.init({
          selector: "textarea#tinymce",
          theme: "modern",
          height: 300,
          plugins: [
              'advlist autolink lists link image charmap print preview hr anchor pagebreak',
              'searchreplace wordcount visualblocks visualchars code fullscreen',
              'insertdatetime media nonbreaking save table contextmenu directionality',
              'emoticons template paste textcolor colorpicker textpattern imagetools'
          ],
          toolbar1: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
          toolbar2: 'print preview media | forecolor backcolor emoticons',
          image_advtab: true
      });
      tinymce.suffix = ".min";
      tinyMCE.baseURL = '{{ asset('assets/backend/plugins/tinymce') }}';
  });
</script>
@endpush
