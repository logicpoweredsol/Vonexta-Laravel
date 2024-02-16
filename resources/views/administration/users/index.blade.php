@extends('layouts.app')

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
            <h1>Users</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="#">Administration</a></li>
              <li class="breadcrumb-item active"><a href="{{route('administration.users')}}">Users</li></a>
              
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
          <h3 class="card-title">All Users</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->

            <!-- {{ route('administration.users.new') }} -->
            <a href="javascript:;" class="btn btn-sm btn-primary" onclick="show_user_on_administrtion()">
              <i class="fas fa-plus"></i> Add User
            </a>
          </div>
        </div>
        <div class="card-body">
            @if(null!==session('msg'))
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('msg'); }}
                        </div>
                    <div>
                </div>
            @endif
            @if(null!==session('error'))
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error'); }}
                        </div>
                    <div>
                </div>
            @endif
            <table id="usersDT" class="table table-striped table-hover vonexta-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                      
                            <td>
                                {{ $user->name }}
                            </td>
                            <td>
                                {{ $user->email }}
                            </td>
                            <td>
                                {{ $user->phone }}
                            </td>
                            <td>

                            @foreach ($user->getRoleNames() as $role)
                              {{ucfirst($role)}}
                            @endforeach
                             
                             
                            </td>
                               
                            <td>

                                @if($user->active==1)
                                    <span class="text-success"> <strong>Active</strong> </span>
                                @else
                                    <span class="text-danger"> <strong>Not Active</strong></span>
                                @endif

                                
                            </td>
                            <td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default">Actions</button>
                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                  <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <div class="dropdown-menu" role="menu">
                                @if(Auth::user()->can('edit users'))
                                  <a class="dropdown-item" href="{{ route('administration.users.view', $user->id) }}">Edit</a>
                                @endif

                                @if(Auth::user()->can('delete users'))
                                  <a class="dropdown-item btnDelete" href="javascript:;" data-id="{{ $user->id }}">Disable</a>
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


    <!-- modal for adding user on administration -->
<div class="modal fade" id="administration_user_modal">
        <div class="bs-stepper" id="newUserWizard">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add User:</h4>

                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>


                    <form id="systemUsersForm" action="{{ route("administration.users.store") }}" method="post" class="form-horizontal">
                    @csrf
                <!-- Default box -->
                
                    
                    <div class="card-body">
                        @if(null!==session('msg'))
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        {{ session('msg') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(null!==session('error'))
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        {{ session('error') }}
                                    </div>
                                </div>
                            </div>
                        @endif
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
                                <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" value="{{ old('Phone')  }}" placeholder="Phone" @error('Phone') aria-invalid="true" @enderror>
                                    <span class="error">
                                    @error('Phone')
                                    <label id="Phone-error" class="error invalid-feedback" for="Phone" style="display: inline-block;">{{ $message }}</label>
                                    @enderror
                                    </span>
                            </div>

                           
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="password" name="password" value="{{ old('password')  }}" placeholder="Password">
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="role" class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role" value="{{ old('role')  }}"  onchange='toggal_service();'>
                                    <option value="admin">Admin</option>
                                    <option value="user">User</option>
                                </select>
                                <span class="error"></span>
                            </div>
                        </div>


                        <div class="form-group row" id='div_services'>
                            <label for="Services_row" class="col-sm-2 col-form-label">Services</label>
                            <div class="col-sm-10">
                                <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>

                             
                                        <div class="col-sm-6 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="">
                                                <label class="form-check-label" for=""></label>
                                            </div>
                                        </div>
                             
                                   
                                </div>
                                <span class="error">
                                    @error('Services')
                                        <label id="Services-error" class="error invalid-feedback" for="Services_row" style="display: inline-block;">{{ $message }}</label>
                                    @enderror
                                </span>
                            </div>
                        </div>

                        {{-- <div class="form-group row">
                            <label for="permissions_row" class="col-sm-2 col-form-label">This user can</label>
                            <div class="col-sm-10">
                                <div class="row" id="permissions_row" @error('permissions') aria-invalid="true" @enderror>
                                    @foreach($permissions as $permission)
                                        <div class="col-sm-6 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $permission->name) }}" name="permissions[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="{{ $permission->name }}">
                                                <label class="form-check-label" for="{{ str_replace(" ", "_", $permission->name) }}">{{ ucwords($permission->name) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <span class="error">
                                    @error('permissions')
                                        <label id="permissions-error" class="error invalid-feedback" for="permissions_row" style="display: inline-block;">{{ $message }}</label>
                                    @enderror
                                </span>
                            </div>
                        </div> --}}
                    </div>
                    <!-- /.card-body -->
                
                <!-- /.card-footer-->
            `  
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                    <a href="{{ route("administration.users")  }}" class="btn btn-default">Cancel</a>
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


