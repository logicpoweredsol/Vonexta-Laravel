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
            <h1>Accounts</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Accounts</li>
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
          <h3 class="card-title">Register Account</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            <a href="{{ route('accounts.new') }}" class="btn btn-md btn-primary">
              <i class="fas fa-plus"></i> Create Account
            </a>
          </div>
        </div>
        <div class="card-body">
            <table id="organizationsDT" class="table table-striped table-hover vonexta-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
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
                          @if($adminUsers->active==1)
                              {{-- <span class="badge badge-success"> <strong>Active</strong> </span> --}}
                              <span class="text-success"> <strong>Active</strong> </span>
                          @else
                              <span class="text-danger"> <strong>Not Active</strong></span>
                              {{-- <span class="badge badge-danger"> <strong>Not Active</strong></span> --}}
                          @endif
                      </td>
                      <td>
                          @if(Auth::user()->can('edit users'))
                              <a href="{{ route('accounts.edit', $adminUsers->id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i><a>
                          @endif
                          @if(Auth::user()->can('delete users'))
                              <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="{{ $adminUsers->id }}"><i class="fas fa-trash"></i><a>
                          @endif
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
