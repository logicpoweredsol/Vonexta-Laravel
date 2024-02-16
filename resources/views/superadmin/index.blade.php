@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush
@section('content')


@if(session()->has('success'))
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            success("{{ session('success') }}");
        });
    </script>
    @elseif (session()->has('error'))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        failed("{{ session('error') }}");
    });
</script>
@endif


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>SuperAdmins</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">SuperAdmins</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">SuperAdmins</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            {{--  --}}

            <!-- {{ route('accounts.new') }} -->
            
            <a href="javascript:;" class="btn btn-md btn-primary" onclick="add_superAdmin();"><i class="fas fa-plus"></i> Add SuperAdmin</a>
          </div>
        </div>
        <div class="card-body">
            <table id="organizationsDT" class="table table-striped table-hover vonexta-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                  @foreach($superadminUsers as $adminUsers)


                  
                  <tr>
         
                      <td>
                          {{ $adminUsers->name }}
                      </td>
                      <td>
                          {{ $adminUsers->email }}
                      </td>
                      <td>
                        {{ $adminUsers->phone}}
                      </td>
              
                      <td>
                          @if($adminUsers->active==1)
                              {{-- <span class="badge badge-success"> <strong>Active</strong> </span> --}}
                              <span class="text-success"> <strong>Active</strong> </span>
                          @else
                              <span class="text-danger"> <strong>Not Active</strong></span>
                              {{-- <span class="badge badge-danger"> <strong>Not Active</strong></span> --}}
                          @endif
                      </td>
                      <td>
                         
                              {{-- <a href="{{ route('accounts.edit', $adminUsers->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i><a>
                         
                         
                              <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="{{ $adminUsers->id }}"><i class="fas fa-trash"></i><a> --}}
                         
                          <div class="btn-group">
                            <button type="button" class="btn btn-default">Actions</button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon"
                                data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu">
                              @if(Auth::user()->can('edit users'))
                                <a class="dropdown-item" href="{{ route('accounts.edit', $adminUsers->id) }}">Edit</a>
                              @endif
                              @if(Auth::user()->can('delete users'))
                                <a class="dropdown-item" href="javascript:;" data-id="{{ $adminUsers->id }}">Delete</a>
                              @endif
                            </div>
                        </div>
                      </td>
                  </tr>
              @endforeach

                    
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer">

        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section>
    <!-- /.content -->


    {{-- modal for adding Super Admin --}}
  <div class="modal fade" id="add-superAdmin">
      <div class="modal-dialog modal-lg">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title">Add SuperAdmin</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>

              
              <div class="modal-body">
                  <!-- form start -->
                  <form id="SuperAdmin" action="{{route('accounts.store')}}" method="post" class="form-horizontal">
                    @csrf
                    <!-- Default box -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Create Account</h3>
                            <div class="card-tools">
                                <a href="{{  route('accounts') }}" class="btn btn-primary">
                                Accounts List 
                                </a>
                            </div>
                
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="name" class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name')  }}" placeholder="Name of the user" @error('name') aria-invalid="true" @enderror>
                                    <span class="error">
                                    @error('name')
                                        <label id="name-error" class="error invalid-feedback" for="name" style="display: inline-block;">{{ $message }}</label>
                                    @enderror
                                </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email')  }}" placeholder="Email" @error('email') aria-invalid="true" @enderror>
                                    <span class="error">
                                        @error('email')
                                            <label id="email-error" class="error invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                    <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone" required>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password')  }}" placeholder="Password"  @error('password') aria-invalid="true" @enderror>
                                    <span class="error">
                                        @error('password')
                                        <label id="password-error" class="error invalid-feedback" for="password" style="display: inline-block;">{{ $message }}</label>
                                    @enderror
                                    </span>
                                </div>
                            </div>
                          
                            
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right">Save</button>
                            <a href="{{  route('accounts') }}"  class="btn btn-default">Cancel</a>
                        </div>
                        <!-- /.card-footer-->
                    </div>
                    <!-- /.card -->
                </form>

              </div>

          </div>
      </div>
  </div>


  

  </div>
  <!-- /.content-wrapper -->
@endSection

@push('scripts')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('views/superadmin/super-admin.js') }}"></script>

@endpush
