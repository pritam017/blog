@extends('layouts.backend.app')
@section('title', 'Show')
@push('css')

<!-- Bootstrap Select Css -->
<link href="{{ asset('assets/backend/plugins/bootstrap-select/css/bootstrap-select.css') }}" rel="stylesheet" />

@endpush
@section('content')
@if ($post->is_approved == false)
    <button class="btn btn-success pull-right" onclick="approveData({{ $post->id }})" name="approve" type="button">
        <i class="material-icons">done</i>
        Approve
    </button>
    <form action="{{ route('admin.approve',$post->id)  }}" id="approve-data" method="POST">
        @csrf
        @method('PUT')
</form>
    <br> <br>
    @else
    <button class="btn btn-info disabled pull-right">
        <i class="material-icons">done</i>
        <span>Approved</span>
    </button> <br><br>
@endif
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <a href="{{ route('admin.post.index') }}" class="btn btn-danger float-left">Back</a> <br><br>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">

    function approveData(id){
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
              confirmButton: 'btn btn-success',
              cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
          })

          swalWithBootstrapButtons.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'success',
            showCancelButton: true,
            confirmButtonText: 'Yes, Approve it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
                jQuery("#approve-data").submit();
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is not approved :)',
                'error'
              )
            }
          })
    }
</script>
@endpush
