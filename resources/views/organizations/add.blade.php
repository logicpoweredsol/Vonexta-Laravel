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
            <h1>Organizations</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('organizations') }}">Organizations</a></li>
              <li class="breadcrumb-item active">Add Organization</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <form method="POST" action="{{ route('organizations.save') }}" class="form-horizontal">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Add Organization</h3>
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
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Legal company name">
                            </div>
                            <span>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Company contact">
                            </div>
                            <span>
                                @error('phone')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="address" class="form-label">Address</label>
                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address') }}" placeholder="Company address">
                            </div>
                            <span>
                                @error('address')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="address2" class="form-label">Address 2</label>
                                <input type="text" class="form-control" id="address2" name="address2" value="{{ old('address2') }}" placeholder="Company address 2">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city') }}" placeholder="City">
                            </div>
                            <span>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state') }}" placeholder="State">
                            </div>
                            <span>
                                @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="zip" class="form-label">Zip/Postal Code</label>
                                <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" value="{{ old('zip') }}" placeholder="Zip/Postal Code">
                            </div>
                            <span>
                                @error('zip')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="active" class="form-label">Active</label>
                                <select name="active" class="form-control" id="active">
                                    <option value="" @if(old('active')=='') {{ "selected" }} @endif>Select Option</option>
                                    <option value="1" @if(old('active')=='1') {{ "selected" }} @endif>Yes</option>
                                    <option value="0" @if(old('active')=='0') {{ "selected" }} @endif>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <div class="row">
                        <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                            <a class="btn btn-default btn-md btn-block" href="{{  route('organizations') }}">Cancel</a>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-8"></div>
                        <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                            <button class="btn btn-success btn-md btn-block" type="submit" >Save</button>
                        </div>
                    </div>
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
    <script src="{{ asset('views/organizations/add.js') }}"></script>
@endpush
