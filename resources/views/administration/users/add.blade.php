@extends('layouts.app')
@push('css')

@endpush
@section('content')

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
                            <li class="breadcrumb-item"><a href="#">System Settings</a></li>
                            <li class="breadcrumb-item active">Users</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- form start -->
            <form id="systemUsersForm" action="{{ route("administration.users.store") }}" method="post" class="form-horizontal">
                @csrf
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Add User</h3>
                    </div>
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
                                    @foreach($user_have_service as $Services)
                             
                                        <div class="col-sm-6 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->user_have_service->service_nick_name) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="{{ $Services->user_have_service->id }}">
                                                <label class="form-check-label" for="{{ str_replace(" ", "_", $Services->user_have_service->service_nick_name) }}"> {{ ucwords($Services->user_have_service->service_nick_name)}}</label>
                                            </div>
                                        </div>
                                    @endforeach
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
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                    <a href="{{ route("administration.users")  }}" class="btn btn-default">Cancel</a>
                </div>
                <!-- /.card-footer-->
            `   </div>
                <!-- /.card -->
            </form>
        </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endSection

@push('scripts')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    <script src="{{ asset('views/administration/users/add.js') }}"></script>
    <script src="{{ asset('views/administration/users/common.js') }}"></script>
@endpush
