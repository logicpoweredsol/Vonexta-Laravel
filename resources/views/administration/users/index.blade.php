@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
    @if (session()->has('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                success("{{ session('success') }}");
            });
        </script>
    @elseif (session()->has('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
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
                            <li class="breadcrumb-item"><a href="#">System Settings</a></li>
                            <li class="breadcrumb-item active">Users</li>
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
                        <a href="javascript:;" class="btn btn-sm btn-primary" onclick="add_user_modal_in_administration();">
                            <i class="fas fa-plus"></i> Add User
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (null !== session('msg'))
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('msg') }}
                                </div>
                                <div>
                                </div>
                    @endif
                    @if (null !== session('error'))
                        <div class="row">
                            <div class="col-sm-12 col-md-12 col-lg-12">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert"
                                        aria-hidden="true">&times;</button>
                                    {{ session('error') }}
                                </div>
                                <div>
                                </div>
                    @endif
                    <table id="usersDT" class="table table-striped table-hover vonexta-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>

                                    <td>
                                        {{ $user->name }}
                                    </td>
                                    <td>
                                        {{ $user->email }}
                                    </td>
                                    <td>

                                        @foreach ($user->getRoleNames() as $role)
                                            {{ ucfirst($role) }}
                                        @endforeach


                                    </td>

                                    <td>

                                        @if ($user->active == 1)
                                            <span class="text-success"> <strong>Active</strong> </span>
                                        @else
                                            <span class="text-danger"> <strong>Not Active</strong></span>
                                        @endif


                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-default">Actions</button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon"
                                                data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu">
                                                @if (Auth::user()->can('edit users'))
                                                    <a class="dropdown-item" onclick="edit_user_modal_in_administration();" href="javascript:;">Edit</a>

                                                        {{-- {{ route('administration.users.view', $user->id) }} --}}
                                                @endif

                                                @if (Auth::user()->can('delete users'))
                                                    <a class="dropdown-item btnDelete" href="javascript:;"
                                                        data-id="{{ $user->id }}">Disable</a>
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





        {{-- Modal for adding user on administration --}}
        <div class="modal fade" id="add-administration-user">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- form start -->
                        <form id="systemUsersForm" action="{{ route('administration.users.store') }}" method="post"
                            class="form-horizontal">
                            @csrf

                            <div>
                                @if (null !== session('msg'))
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="alert alert-success alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">&times;</button>
                                                {{ session('msg') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if (null !== session('error'))
                                    <div class="row">
                                        <div class="col-sm-12 col-md-12 col-lg-12">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert"
                                                    aria-hidden="true">&times;</button>
                                                {{ session('error') }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Name of the user" @error('name') aria-invalid="true" @enderror
                                            required>
                                        <span class="error">
                                            @error('name')
                                                <label id="name-error" class="error invalid-feedback" for="name"
                                                    style="display: inline-block;">{{ $message }}</label>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="Email" @error('email') aria-invalid="true" @enderror required>
                                        <span class="error">
                                            @error('email')
                                                <label id="email-error" class="error invalid-feedback" for="email"
                                                    style="display: inline-block;">{{ $message }}</label>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="password" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="password" name="password"
                                            value="{{ old('password') }}" required placeholder="Password">
                                        <span class="error"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="role" class="col-sm-2 col-form-label">Role</label>
                                    <div class="col-sm-10">
                                        <select class="form-control select3" id="role" name="role"
                                            value="{{ old('role') }}">
                                            <option value="admin">Admin</option>
                                            <option value="user">User</option>
                                        </select>
                                        <span class="error"></span>
                                    </div>
                                </div>


                                <div class="form-group row" id='div_services'>
                                    <label for="Services_row" class="col-sm-2 col-form-label">Services</label>
                                    <div class="col-sm-10">
                                        <div class="row" id="Services_row"
                                            @error('Services') aria-invalid="true"
                                      @enderror>
                                            {{-- @foreach ($user_have_service as $Services) --}}

                                            <div class="col-sm-6 mb-3">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id=""
                                                        name="Services[]" data-bootstrap-switch data-off-color="danger"
                                                        data-on-color="success" checked value="">
                                                    <label class="form-check-label" for=""></label>
                                                </div>
                                            </div>
                                            {{-- @endforeach --}}
                                        </div>
                                        <span class="error">
                                            @error('Services')
                                                <label id="Services-error" class="error invalid-feedback" for="Services_row"
                                                    style="display: inline-block;">{{ $message }}</label>
                                            @enderror
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer justify-content-between">
                                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                  <button type="submit" class="btn btn-primary">Save</button>
                              </div>
                            </div>  
                        </form>

                    </div>

                </div>
            </div>
        </div>




        {{-- Modal for edit user in administration --}}
        <div class="modal fade" id="edit-administration-user">
          <div class="modal-dialog modal-lg">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title">Edit User</h4>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                      <!-- form start -->
                      <form id="systemUsersForm" action="{{ route("administration.users.edit", $user->id) }}" method="post" class="form-horizontal">
                        <input type="hidden" name="edit-user" id="edit-user" value="{{$user->id}}">
                          @csrf

                          <div>
                              @if (null !== session('msg'))
                                  <div class="row">
                                      <div class="col-sm-12 col-md-12 col-lg-12">
                                          <div class="alert alert-success alert-dismissible">
                                              <button type="button" class="close" data-dismiss="alert"
                                                  aria-hidden="true">&times;</button>
                                              {{ session('msg') }}
                                          </div>
                                      </div>
                                  </div>
                              @endif
                              @if (null !== session('error'))
                                  <div class="row">
                                      <div class="col-sm-12 col-md-12 col-lg-12">
                                          <div class="alert alert-danger alert-dismissible">
                                              <button type="button" class="close" data-dismiss="alert"
                                                  aria-hidden="true">&times;</button>
                                              {{ session('error') }}
                                          </div>
                                      </div>
                                  </div>
                              @endif
                              <div class="form-group row">
                                  <label for="name" class="col-sm-2 col-form-label">Name</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="" placeholder="Name of the user" @error('name') aria-invalid="true" @enderror
                                          required>
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
                                      <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="" placeholder="Email" @error('email') aria-invalid="true" @enderror required>
                                      <span class="error">
                                          @error('email')
                                              <label id="email-error" class="error invalid-feedback" for="email"
                                                  style="display: inline-block;">{{ $message }}</label>
                                          @enderror
                                      </span>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="password" class="col-sm-2 col-form-label">Password</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control" id="password" name="password" value="" required placeholder="Password">
                                      <span class="error"></span>
                                  </div>
                              </div>
                              <div class="form-group row">
                                  <label for="role" class="col-sm-2 col-form-label">Role</label>
                                  <div class="col-sm-10">
                                      <select class="form-control select3" id="role" name="role" value="">
                                          <option value="admin">Admin</option>
                                          <option value="user">User</option>
                                      </select>
                                      <span class="error"></span>
                                  </div>
                              </div>


                              <div class="form-group row" id='div_services'>
                                  <label for="Services_row" class="col-sm-2 col-form-label">Services</label>
                                  <div class="col-sm-10">
                                      <div class="row" id="Services_row"
                                          @error('Services') aria-invalid="true"
                                    @enderror>
                                          {{-- @foreach ($user_have_service as $Services) --}}

                                          <div class="col-sm-6 mb-3">
                                              <div class="form-check">
                                                  <input type="checkbox" class="form-check-input" id="" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="">
                                                  <label class="form-check-label" for=""></label>
                                              </div>
                                          </div>
                                          {{-- @endforeach --}}
                                      </div>
                                      <span class="error">
                                          @error('Services')
                                              <label id="Services-error" class="error invalid-feedback" for="Services_row"
                                                  style="display: inline-block;">{{ $message }}</label>
                                          @enderror
                                      </span>
                                  </div>
                              </div>
                              <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                          </div>  
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
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
    </script>
    <script src="{{ asset('views/administration/users/index.js') }}"></script>
    <script src="{{ asset('views/administration/users/common.js') }}"></script>
@endpush
