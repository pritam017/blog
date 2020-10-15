@extends('layouts.backend.app')@section('title', 'post')@push('css')
<!-- JQuery DataTable Css -->

<link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet"/>
 @endpush
@section('content')
<!-- Exportable Table -->
<div class = "row clearfix">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <a href="{{ route('author.post.create') }}" class="btn btn-primary pull-0">Add New</a> <br> <br>
    <div class="card">
        <div class="header">
            <h2>
                ALL POST ({{ $posts->count() }})
            </h2> <br>
            <ul class="header-dropdown m-r--5">
                <li class="dropdown">
                    <a
                        href="javascript:void(0);"
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        role="button"
                        aria-haspopup="true"
                        aria-expanded="false">
                        <i class="material-icons">more_vert</i>
                    </a>
                    <ul class="dropdown-menu pull-right">
                        <li>
                            <a href="javascript:void(0);">Action</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Another action</a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">Something else here</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="body">
            <div class="table-responsive">
                <table
                    class="table table-bordered table-striped table-hover dataTable js-exportable">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th><i class="material-icons">visibility</i></th>
                            <th>Is Approved</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>SL</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th><i class="material-icons">visibility</i></th>
                            <th>Is Approved</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($posts as $key => $post)
                        <tr>
                            <td>{{ $key+=1 }}</td>
                            <td>{{ Str::limit($post->title, '10') }}
                            </td>
                            <td>{{ $post->user->name }}
                            </td>
                            <td>{{ $post->view_count }}
                            </td>
                            <td> @if($post->is_approved == false)
                                <span class="badge badge-danger">pending</span>
                            @else
                                <span class="badge badge-info">Approved</span>
                            @endif</td>

                            <td>

                            <td>
                                @if($post->status == false)
                                    <span class="badge bg-danger">Unapproved</span>
                                @else
                                    <span class="badge bg-info">Approved</span>
                                @endif
                            </td>

                            <td><img class="user_avatar" src="{{ Storage::disk('public')->url('post/'. $post->image) }}" width="50" height="50"></td>
                            <td>{{ $post->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('author.post.show', $post->id) }}" class="btn btn-warning btn-sm">
                                    <i class="material-icons">visibility</i>
                                </a>
                                <a href="{{ route('author.post.edit', $post->id) }}" class="btn btn-info btn-sm">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteData({{ $post->id }})" name="delete" type="button">
                                    <i class="material-icons">delete</i>
                                </button>
                                <form action="{{ route('author.post.destroy',$post->id)  }}" id="delete-data-{{ $post->id }}" method="POST">
                                    @csrf
                                @method('DELETE')
                            </form>
                            </td>
                        </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- #END# Exportable Table -->
@endsection @push('js')
<!-- Jquery DataTable Plugin Js -->
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/jquery.dataTables.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
<script
    src="{{ asset('assets/backend/jquery-datatable/extensions/export/buttons.flash.min.js') }}plugins/"></script>

<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
<script
    src="{{ asset('assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
<script src="{{ asset('assets/backend/js/pages/tables/jquery-datatable.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">

    function deleteData(id){
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
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!',
            reverseButtons: true
          }).then((result) => {
            if (result.isConfirmed) {
                jQuery("#delete-data-"+id).submit();
            } else if (
              /* Read more about handling dismissals below */
              result.dismiss === Swal.DismissReason.cancel
            ) {
              swalWithBootstrapButtons.fire(
                'Cancelled',
                'Your imaginary file is safe :)',
                'error'
              )
            }
          })
    }
</script>
@endpush
