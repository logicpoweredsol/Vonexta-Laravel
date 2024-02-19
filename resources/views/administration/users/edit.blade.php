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
                        <h1>Edit {{$user->name}}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="">Administration</a></li>
                            <li class="breadcrumb-item active"><a href="{{route('administration.users')}}">Users</li></a>
                            <li class="breadcrumb-item active">Edit User</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="card">
                <div class="card-body">
                    <!-- form start -->
                    <form id="systemUsersForm" action="{{ route("administration.users.edit", $user->id) }}" method="POST" class="form-horizontal">
                        @csrf
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit User</h3>
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

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name</label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?? $user->name }}" placeholder="Name of the user" @error('name') aria-invalid="true" @enderror>
                                        </div>
                                        <span class="error">
                                            @error('name')
                                                <label id="name-error" class="error invalid-feedback" for="name" style="display: inline-block;">{{ $message }}</label>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Email</label>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?? $user->email  }}" placeholder="Email" @error('email') aria-invalid="true" @enderror>
                                            <span class="error">
                                                @error('email')
                                                    <label id="email-error" class="error invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                                        <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" value="{{ old('Phone') ?? $user->phone  }}" placeholder="Phone" @error('Phone') aria-invalid="true" @enderror>
                                        <span class="error">
                                            @error('Phone')
                                            <label id="Phone-error" class="error invalid-feedback" for="Phone" style="display: inline-block;">{{ $message }}</label>
                                            @enderror
                                        </span>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="password" class="form-label">Password</label>
                                            <input type="text" class="form-control" id="password" name="password" value="{{ old('password')  }}" placeholder="Password" @error('password') aria-invalid="true" @enderror>
                                            <span class="error">
                                                @error('password')
                                                    <label id="password-error" class="error invalid-feedback" for="password" style="display: inline-block;">{{ $message }}</label>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="role" class="form-label">Role</label>
                                            <select class="form-control" id="role" name="role" onchange='toggal_service();'>
                                                @foreach($roles as $role)
                                                    <option value="{{ $role->name }}" @if($edit_userRoles[0]==$role->name) {{ "selected" }} @endif>{{ ucfirst($role->name) }}</option>
                                                @endforeach
                                            </select>
                                            <span class="error"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="active" class="form-label">Active</label>
                                            <select class="form-control" id="active" name="active">
                                                <option value="1" @if($user->active==1) {{ "selected" }} @endif>Yes</option>
                                                <option value="0" @if($user->active==0) {{ "selected" }} @endif>No</option>
                                            </select>
                                            <span class="error"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        @if($edit_userRoles[0] == 'admin')
                                            <div class="form-group">
                                                <label for="Services_row" class="form-label">Services</label>
                                                <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>
                                                        @foreach($user_have_service as $Services)
                                                        <div class="col-sm-4 mb-3">
                                                            <div class="form-check p-0">
                                                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->user_have_service->service_nick_name) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success"  @if (in_array($Services->user_have_service->id,$edit_user_service )) checked @endif value="{{$Services->user_have_service->id }}">
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
                                            @else
                                            <div class="form-group d-none" id='div_services' >
                                                <label for="Services_row" class="form-label">Services</label>
                                                <div class="col-sm-10">
                                                    <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>
                                                        @foreach($user_have_service as $Services)
                                                        <div class="col-sm-6 mb-3">
                                                            <div class="form-check">
                                                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->user_have_service->service_nick_name) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success"  @if (in_array($Services->user_have_service->id,$edit_user_service )) checked @endif value="{{$Services->user_have_service->id }}">
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
                                            @endif

                                            {{-- <div class="form-group">
                                                <label for="permissions_row" class="form-label">This user can</label>
                                                <div class="col-sm-10">
                                                    <div class="row" id="permissions_row" @error('permissions') aria-invalid="true" @enderror>

                                                        @foreach($permissions as $permission)
                                                            @if(in_array($permission->name, $userPermissions))
                                                                <div class="col-sm-6 mb-3">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $permission->name) }}" name="permissions[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="{{ $permission->name }}">
                                                                        <label class="form-check-label" for="{{ str_replace(" ", "_", $permission->name) }}">{{ ucwords($permission->name) }}</label>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="col-sm-6 mb-3">
                                                                    <div class="form-check">
                                                                        <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $permission->name) }}" name="permissions[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" value="{{ $permission->name }}">
                                                                        <label class="form-check-label" for="{{ str_replace(" ", "_", $permission->name) }}">{{ ucwords($permission->name) }}</label>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                        <span class="error">
                                                            @error('permissions')
                                                                <label id="permissions-error" class="error invalid-feedback" for="permissions_row" style="display: inline-block;">{{ $message }}</label>
                                                            @enderror
                                                        </span>
                                                    </div>
                                                    <span class="error"></span>
                                                </div>
                                            </div> --}}
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                        <a href="{{ route("administration.users")  }}" class="btn btn-default btn-md btn-block">Cancel</a>
                                    </div>
                                    <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                    <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                        <button type="submit" class="btn btn-success btn-md btn-block">Save</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!-- /.card -->
                    </form>
                </div>
            </div>
        </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endSection
