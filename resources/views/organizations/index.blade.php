@extends('layouts.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
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
                        <li class="breadcrumb-item active">Organizations</li>
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
                <h3 class="card-title">Registered Organizations</h3>

                <div class="card-tools">
                    <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            {{-- {{ route('organizations.new') }} --}}
                    <a href="javascript:;" class="btn btn-md btn-primary" onclick="show_add_organsization()">
                        <i class="fas fa-plus"></i> Add Organization
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
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('error'); }}
                                </div>
                                <div>
                                </div>
                                @endif
                                <table id="organizationsDT" class="table table-striped table-hover vonexta-table">
                                    <thead>
                                        <tr>
                                            {{-- <th>#</th> --}}
                                            <th>Organization Name</th>
                                            <!-- <th>Email</th> -->
                                            <th>Phone</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($organizations as $organization)
                                        <tr>
                                            {{-- <td>
                                                {{ $loop->index + 1 }}
                                            </td> --}}
                                            <td>
                                                {{ $organization->name }}
                                            </td>
                                            <!-- <td>
                                {{ $organization->email }}
                            </td> -->
                                            <td>
                                                {{ $organization->phone }}
                                            </td>

                                            <td>
                                                @if($organization->active==1)
                                                {{-- <span class="badge badge-success"> <strong>Active</strong> </span>
                                                --}}
                                                <span class="text-success"> <strong>Active</strong> </span>
                                                @else
                                                <span class="text-danger"> <strong>Not Active</strong></span>
                                                {{-- <span class="badge badge-danger"> <strong>Not
                                                        Active</strong></span> --}}
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" class="btn btn-default">Actions</button>
                                                    <button type="button"
                                                        class="btn btn-default dropdown-toggle dropdown-icon"
                                                        data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                    </button>
                                                    <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item"
                                                            href="{{ route('organizations.view', $organization->id) }}">Edit</a>
                                                        <a class="dropdown-item btnDelete"
                                                            data-id="{{ $organization->id }} href="
                                                            javascript:;">Delete</a>

                                                    </div>
                                                </div>
                                                <!-- <a href="" class="btn btn-sm btn-primary" class="btnEdit" data-id="{{ $organization->id }}"><i class="fas fa-pen"></i><a>
                                 <a href="#" class="btn btn-sm btn-danger" class="btnDelete" data-id="{{ $organization->id }}"><i class="fas fa-trash"></i><a> -->
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
</div>
<div class="modal fade" id="add-organization">
    <div class="bs-stepper" id="newUserWizard">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Organization:</h4>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" action="{{ route('organizations.save') }}" class="form-horizontal">
                    @csrf
                    <div class="card">
                        
                        <div class="card-body">
                            @if(null!==session('msg'))
                            <div class="row">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">&times;</button>
                                        {{ session('msg'); }}
                                    </div>
                                    <div>
                                    </div>
                                    @endif
                                    @if(null!==session('error'))
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">&times;</button>
                                                {{ session('error'); }}
                                            </div>
                                            <div>
                                            </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text"
                                                            class="form-control @error('name') is-invalid @enderror"
                                                            id="name" name="name" value="{{ old('name') }}"
                                                            placeholder="Legal company name">
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
                                                        <input type="text"
                                                            class="form-control @error('phone') is-invalid @enderror"
                                                            id="phone" name="phone" value="{{ old('phone') }}"
                                                            placeholder="Company contact">
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
                                                        <input type="text"
                                                            class="form-control @error('address') is-invalid @enderror"
                                                            id="address" name="address" value="{{ old('address') }}"
                                                            placeholder="Company address">
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
                                                        <input type="text" class="form-control" id="address2"
                                                            name="address2" value="{{ old('address2') }}"
                                                            placeholder="Company address 2">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="city" class="form-label">City</label>
                                                        <input type="text"
                                                            class="form-control @error('city') is-invalid @enderror"
                                                            id="city" name="city" value="{{ old('city') }}"
                                                            placeholder="City">
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
                                                        <input type="text"
                                                            class="form-control @error('state') is-invalid @enderror"
                                                            id="state" name="state" value="{{ old('state') }}"
                                                            placeholder="State">
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
                                                        <input type="text"
                                                            class="form-control @error('zip') is-invalid @enderror"
                                                            id="zip" name="zip" value="{{ old('zip') }}"
                                                            placeholder="Zip/Postal Code">
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
                                                            <option value="" @if(old('active')=='' ) {{ "selected" }}
                                                                @endif>Select Option</option>
                                                            <option value="1" @if(old('active')=='1' ) {{ "selected" }}
                                                                @endif>Yes</option>
                                                            <option value="0" @if(old('active')=='0' ) {{ "selected" }}
                                                                @endif>No</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                    <a class="btn btn-default btn-md btn-block"
                                                        href="{{  route('organizations') }}">Cancel</a>
                                                </div>
                                                <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                                <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                    <button class="btn btn-success btn-md btn-block"
                                                        type="submit">Save</button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-footer-->
                                    </div>
                                    <!-- /.card -->
                </form>

               


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
<script src="{{ asset('views/organizations/index.js') }}"></script>
@endpush