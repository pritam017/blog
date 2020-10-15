@extends('layouts.backend.app')@section('title', 'category')@push('css')
<!-- JQuery DataTable Css -->

<link href="{{ asset('assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}"
rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" rel="stylesheet"/>
 @endpush
@section('content')
<!-- Exportable Table -->

<div class = "row clearfix">
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="card">
        <div class="header">
            <h2>
                CATEGORY TABLE
            </h2>
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
                            <th>Name</th>
                            <th>Post Count</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Name</th>
                            <th>Post Count</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach($categories as $key => $category)
                        <tr>
                            <td>{{ $category->name }}
                            </td>
                            <td>{{ $category->posts->count() }}</td>
                            <td><img class="user_avatar" src="{{ Storage::disk('public')->url('category/'. $category->image) }}" width="70" height="80"></td>
                            <td>
                                <a href="{{ route('admin.category.edit', $category->id) }}" class="btn btn-info btn-sm">
                                    <i class="material-icons">edit</i>
                                </a>
                                <button class="btn btn-danger btn-sm" onclick="deleteData({{ $category->id }})" name="delete" type="button">
                                    <i class="material-icons">delete</i>
                                </button>
                                <form action="{{ route('admin.category.destroy',$category->id)  }}" id="delete-data-{{ $category->id }}" method="POST">
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
<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <div class="card">
        <div class="header">
            <h2>
                ADD Category
            </h2>
        </div>
        <div class="body">
            <form action="{{ route('admin.category.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="email_address">Category Name</label>
                <div class="form-group">
                    <div class="form-line">
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter Your category Name">
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
                        </div>
                    <input type="submit" class="btn btn-primary" value="Add New category"></form>
                </div>
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
