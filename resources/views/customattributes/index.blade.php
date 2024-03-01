@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Nothing for now -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">

        @if(session()->has('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    success("{{ session('success') }}");
                });
            </script>
        @elseif (session()->has('failed'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    failed("{{ session('failed') }}");
                });
            </script>
    @endif



    <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
{{--                        <h1>Edit Organization {{$organization->name}} </h1>--}}
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('organizations') }}">Organizations</a></li>
                            <li class="breadcrumb-item active">Edit Organization</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">

            <div class="card">
                <div class="card-body">

                    <ul class="nav nav-tabs vonexta-nav" id="campaigns-tabs" role="tablist">
                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'customattribuites-agents')  active @endif  @if( !session()->has('tab') && !session('tab')) active @endif   " id="customattribuites-agents-tab" data-toggle="pill" href="#customattribuites-agents" role="tab" aria-controls="customattribuites-agents" aria-selected="true">Agents</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'Customattributes-contacts')  active @endif " id="Customattributes-contacts-tab" data-toggle="pill" href="#Customattributes-contacts" role="tab" aria-controls="Customattributes-contacts" aria-selected="false">Contacts</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'Customattributes-outboundprofiles')  active @endif " id="Customattributes-outboundprofiles-tab" data-toggle="pill" href="#Customattributes-outboundprofiles" role="tab" aria-controls="Customattributes-outboundprofiles" aria-selected="false">Outbound Profiles</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'Customattributes-inboundquesues')  active @endif " id="Customattributes-inboundquesues-tab" data-toggle="pill" href="#Customattributes-inboundquesues"  role="tab" aria-controls="Customattributes-inboundquesues" aria-selected="false">Inbound Queues</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'Customattributes-tnfs')  active @endif " id="Customattributes-tnfs-tab" data-toggle="pill" href="#Customattributes-tnfs" onclick="change_tab('org-user');" role="tab" aria-controls="Customattributes-tnfs" aria-selected="false">TFNs</a>
                        </li>

                    </ul>

                    <div class="tab-content" id="campaigns-tabs Content">


                        <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'customattribuites-agents') show active @endif   @if( !session()->has('tab') && !session('tab')) show active @endif  "  id="customattribuites-agents" role="tabpanel" aria-labelledby="customattribuites-agents-tab">
                            <section class="content">

                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Outbound Profile Attributes</h3>

                                        <div class="card-tools">
                                            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                              <i class="fas fa-minus"></i>
                                            </button> -->

{{--                                            <a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_custom_attributes_modal">--}}
{{--                                                <i class="fas fa-plus"></i> Add Attribute--}}
{{--                                            </a>--}}
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
                                                                        <th>API Name</th>
                                                                        <th>Display Name</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                    <tbody>
                                                                    {{--                                                                        @foreach($users as $user)--}}
                                                                    <tr>

                                                                        <td>
                                                                            test name
                                                                        </td>
                                                                        <td>
                                                                            test display name
                                                                        </td>

                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-default">Actions</button>
                                                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                                </button>
                                                                                <div class="dropdown-menu" role="menu">
                                                                                    {{--                                                                                            @if(Auth::user()->can('edit attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item" href="{{ route('administration.users.view', $user->id) }}">Edit</a>--}}
                                                                                    <a class="dropdown-item" href="#!">Edit</a>
                                                                                    {{--                                                                                            @endif--}}

                                                                                    {{--                                                                                            @if(Auth::user()->can('delete attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item btnDelete" href="javascript:;" data-id="{{ $user->id }}">Delete</a>--}}
                                                                                    <a class="dropdown-item btnDelete" href="#!" data-id="">Delete</a>
                                                                                    {{--                                                                                            @endif--}}
                                                                                </div>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                    {{--                                                                        @endforeach--}}

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
                        </div>

                        <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Customattributes-contacts') show active @endif " id="Customattributes-contacts" role="tabpanel" aria-labelledby="Customattributes-contacts-tab">
                           Contact Tab
                        </div>

                        <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Customattributes-outboundprofiles') show active @endif  " id="Customattributes-outboundprofiles" role="tabpanel" aria-labelledby="Customattributes-outboundprofiles-tab">

                            <!-- Main content -->
                            <section class="content">

                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Outbound Profile Attributes</h3>

                                        <div class="card-tools">
                                            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                              <i class="fas fa-minus"></i>
                                            </button> -->

                                        <!-- {{ route('administration.users.new') }} -->
                                            <a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_custom_attributes_modal">
                                                <i class="fas fa-plus"></i> Add Attribute
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
                                                                        <th>API Name</th>
                                                                        <th>Display Name</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                        <tbody>
{{--                                                                        @foreach($users as $user)--}}
                                                                            <tr>

                                                                                <td>
                                                                                    test name
                                                                                </td>
                                                                                <td>
                                                                                    test display name
                                                                                </td>

                                                                                <td>
                                                                                    <div class="btn-group">
                                                                                        <button type="button" class="btn btn-default">Actions</button>
                                                                                        <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                                            <span class="sr-only">Toggle Dropdown</span>
                                                                                        </button>
                                                                                        <div class="dropdown-menu" role="menu">
{{--                                                                                            @if(Auth::user()->can('edit attribute'))--}}
{{--                                                                                                <a class="dropdown-item" href="{{ route('administration.users.view', $user->id) }}">Edit</a>--}}
                                                                                                <a class="dropdown-item" href="#!">Edit</a>
{{--                                                                                            @endif--}}

{{--                                                                                            @if(Auth::user()->can('delete attribute'))--}}
{{--                                                                                                <a class="dropdown-item btnDelete" href="javascript:;" data-id="{{ $user->id }}">Delete</a>--}}
                                                                                                <a class="dropdown-item btnDelete" href="#!" data-id="">Delete</a>
{{--                                                                                            @endif--}}
                                                                                        </div>
                                                                                    </div>

                                                                                </td>
                                                                            </tr>
{{--                                                                        @endforeach--}}

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

                            <div class="modal fade" id="add_custom_attributes_modal">
                                <div class="bs-stepper" id="newUserWizard">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">Add Attribute:</h4>

                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>


                                            <form id="addAttributesForm" action="" method="post" class="form-horizontal">
                                                {{-- {{ route("customattributes.store") }} --}}
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
                                                        <label for="name" class="col-sm-2 col-form-label">Api name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control @error('api_name') is-invalid @enderror" id="api_name" name="api_name" value="{{ old('api_name')  }}" placeholder="Api Name" @error('api_name') aria-invalid="true" @enderror>
                                                            <span class="error">
                                @error('api_name')
                                    <label id="api_name-error" class="error invalid-feedback" for="api_name" style="display: inline-block;">{{ $message }}</label>
                                @enderror
                            </span>
                                                        </div>
                                                    </div>

                                                    <div class="form-group row">
                                                        <label for="password" class="col-sm-2 col-form-label">Display Name</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" id="display_name" name="Display Name" value="{{ old('display_name')  }}" placeholder="Display Name">
                                                            <span class="error"></span>
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
                                                    <a href="#!" class="btn btn-default">Cancel</a>
                                                </div>
                                                <!-- /.card -->
                                            </form>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Customattributes-inboundquesues') show active @endif  " id="Customattributes-inboundquesues" role="tabpanel" aria-labelledby="Customattributes-outboundprofiles-tab">
                            <section class="content">

                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Outbound Profile Attributes</h3>

                                        <div class="card-tools">
                                            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                              <i class="fas fa-minus"></i>
                                            </button> -->

                                        <!-- {{ route('administration.users.new') }} -->
{{--                                            <a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_custom_attributes_modal">--}}
{{--                                                <i class="fas fa-plus"></i> Add Attribute--}}
{{--                                            </a>--}}
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
                                                                        <th>API Name</th>
                                                                        <th>Display Name</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                    <tbody>
                                                                    {{--                                                                        @foreach($users as $user)--}}
                                                                    <tr>

                                                                        <td>
                                                                            test name
                                                                        </td>
                                                                        <td>
                                                                            test display name
                                                                        </td>

                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-default">Actions</button>
                                                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                                </button>
                                                                                <div class="dropdown-menu" role="menu">
                                                                                    {{--                                                                                            @if(Auth::user()->can('edit attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item" href="{{ route('administration.users.view', $user->id) }}">Edit</a>--}}
                                                                                    <a class="dropdown-item" href="#!">Edit</a>
                                                                                    {{--                                                                                            @endif--}}

                                                                                    {{--                                                                                            @if(Auth::user()->can('delete attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item btnDelete" href="javascript:;" data-id="{{ $user->id }}">Delete</a>--}}
                                                                                    <a class="dropdown-item btnDelete" href="#!" data-id="">Delete</a>
                                                                                    {{--                                                                                            @endif--}}
                                                                                </div>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                    {{--                                                                        @endforeach--}}

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
                        </div>

                        <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Customattributes-tnfs') show active @endif  " id="Customattributes-tnfs" role="tabpanel" aria-labelledby="Customattributes-tnfs-tab">
                            <section class="content">

                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Outbound Profile Attributes</h3>

                                        <div class="card-tools">
                                            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                              <i class="fas fa-minus"></i>
                                            </button> -->

{{--                                            <a href="javascript:;" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add_custom_attributes_modal">--}}
{{--                                                <i class="fas fa-plus"></i> Add Attribute--}}
{{--                                            </a>--}}
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
                                                                        <th>API Name</th>
                                                                        <th>Display Name</th>
                                                                        <th>Actions</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    </tbody>
                                                                    <tbody>
                                                                    {{--                                                                        @foreach($users as $user)--}}
                                                                    <tr>

                                                                        <td>
                                                                            test name
                                                                        </td>
                                                                        <td>
                                                                            test display name
                                                                        </td>

                                                                        <td>
                                                                            <div class="btn-group">
                                                                                <button type="button" class="btn btn-default">Actions</button>
                                                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                                                    <span class="sr-only">Toggle Dropdown</span>
                                                                                </button>
                                                                                <div class="dropdown-menu" role="menu">
                                                                                    {{--                                                                                            @if(Auth::user()->can('edit attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item" href="{{ route('administration.users.view', $user->id) }}">Edit</a>--}}
                                                                                    <a class="dropdown-item" href="#!">Edit</a>
                                                                                    {{--                                                                                            @endif--}}

                                                                                    {{--                                                                                            @if(Auth::user()->can('delete attribute'))--}}
                                                                                    {{--                                                                                                <a class="dropdown-item btnDelete" href="javascript:;" data-id="{{ $user->id }}">Delete</a>--}}
                                                                                    <a class="dropdown-item btnDelete" href="#!" data-id="">Delete</a>
                                                                                    {{--                                                                                            @endif--}}
                                                                                </div>
                                                                            </div>

                                                                        </td>
                                                                    </tr>
                                                                    {{--                                                                        @endforeach--}}

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
                        </div>


                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">

                </div>
                <!-- /.card-footer-->
            </div>

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Modal to get connection parameters from the super admin to connect to the service... -->
    <div class="modal fade" id="modalConnectionParameters" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <input type="hidden" id="add_serive_name" value="">
            <input type="hidden" id="add_service_type" value="">
            {{-- <input type="hidden" id="add_org_id" value=""> --}}
            <input type="hidden" id="add_serive_Nickname" value="">

            <form id="formConnectionParameters" class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add service connection</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="connectionParams"></div>
                    <!-- <div class="modal-body" id="connectionParams">
                        <input type="hidden" name="id" id="os_id" value="">
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnAddConnectionParameters">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal to get connection parameters from the super admin to connect to the service... -->
    <!-- <div class="modal fade" id="modalEditOgService" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitleEditOgService" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleEditOgService">Edit service connection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="ogService_id" >
                    <input type="hidden" id="service_type" >

                    <div class="form-group row">
                        <label for="edit_service_name" class="col-sm-12 col-md-4 col-lg-4 col-form-label">Service slug</label>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <input type="text" class="form-control" id="edit_serive_name" required placeholder="Service slug">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="edit_service_nick" class="col-sm-12 col-md-4 col-lg-4 col-form-label">Nick name</label>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <input type="text" class="form-control" id="edit_serive_Nickname" required  placeholder="nick name">
                        </div>
                    </div>



                    <form id="formEditOgService" class="form-horizontal">
                        <div  id="editOgServiceBody" >

                        </div>
                        </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnUpdateOgService">Save changes</button>
                </div>
            </div>
        {{-- </form> --}}
            </div>
        </div> -->
@endSection




