@extends('layouts.app')
@push('css')
    <!-- Nothing for now -->
@endpush
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('accounts') }}">Accounts</a></li>
              <li class="breadcrumb-item active">create Account</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <form id="" action="{{route('accounts.store')}}" method="post" class="form-horizontal">
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

        
       
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endSection

@push('scripts')
    <script src="{{ asset('views/superadmin/super-admin.js') }}"></script>
@endpush
