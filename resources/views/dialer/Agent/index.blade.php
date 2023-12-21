@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Agents</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Agents</li>
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
          <h3 class="card-title">All Agents</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="btnAddUser">
              <i class="fas fa-plus"></i> Add Agent
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
                        <th>User</th>
                        <th>Extention</th>
                        <th>Full Name</th>
                        <th>User Group</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>


                    @foreach ($userAgent as $i=>$service_User)
                    @php
                      $detail = get_agent_detail($service_User->services_id , $service_User->api_user );

                    //   dd($detail['data']);
                    @endphp
                   
                    <tr>
                        <td>
                            {{$service_User->user_detail->email}}
                        </td>
                        <td>
                            {{$detail['data']['user']}}
                        </td>
                        <td>
                            {{$detail['data']['full_name']}}
                        </td>
                        <td>
                            {{$detail['data']['user_group']}}
                        </td>
                        <td>
                            @if ($detail['data']['active'] == 'Y')
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
                                  <a class="dropdown-item" href="{{ route('services.agents.edit', ['service' => strtolower($service), 'serviceID' => $serviceID,'AgentID' => $detail['data']['user']  ] ) }}">Modify</a>
                                  <a class="dropdown-item" href="#">Logs</a>
                                  <a class="dropdown-item" href="#">Emergency Logout</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Delete</a>
                                </div>
                              </div>
                        </td>
                    </tr>
                    @endforeach
                    
                    
                    {{-- @foreach ($service_Users['user_id'] as $i=>$service_User)
                   
                    <tr>
                        <td>
                            {{$users[0]['email']}}
                        </td>
                        <td>
                           {{$service_Users['user'][$i]}}
                        </td>
                        <td>
                            {{$service_Users['full_name'][$i]}}
                        </td>
                        <td>
                            {{$service_Users['user_group'][$i]}}
                        </td>
                        <td>
                            @if ($service_Users['active'][$i] == 'Y')
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
                                  <a class="dropdown-item" href="{{ route('services.agents.edit', ['service' => strtolower($service), 'serviceID' => $serviceID,'AgentID' => $service_Users['user'][$i]  ] ) }}">Modify</a>
                                  <a class="dropdown-item" href="#">Logs</a>
                                  <a class="dropdown-item" href="#">Emergency Logout</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Delete</a>
                                </div>
                              </div>
                        </td>
                    </tr>
                    @endforeach --}}
                   
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
    <!-- Modals... -->
    <div class="modal fade" id="modalNewUser">
        <div class="bs-stepper" id="newUserWizard">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add New User</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="bs-stepper-header" role="tablist">
                            <!-- your steps here -->
                            <div class="step" data-target="#gettingstarted">
                                <button type="button" class="step-trigger" role="tab" aria-controls="gettingstarted" id="gettingstarted-trigger">
                                    <span class="bs-stepper-circle">1</span>
                                    <span class="bs-stepper-label">Getting Started</span>
                                </button>
                            </div>
                            <div class="line"></div>
                            <div class="step" data-target="#accountdetails">
                                <button type="button" class="step-trigger" role="tab" aria-controls="accountdetails" id="accountdetails-trigger">
                                    <span class="bs-stepper-circle">2</span>
                                    <span class="bs-stepper-label">Account Details</span>
                                </button>
                            </div>
                        </div>
                        <div class="bs-stepper-content">
                            <!-- your steps content here -->
                            <div id="gettingstarted" class="content" role="tabpanel" aria-labelledby="gettingstarted-trigger">
                                <div class="form-group row">
                                    <label for="totalUsers" class="col-sm-2 col-form-label">Total Users</label>
                                    <div class="col-sm-10">
                                        <input type="text" readonly class="form-control" name="totalUsers" id="totalUsers">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="noOfSeats" class="col-sm-2 col-form-label">Number of Seat(s):</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="noOfSeats" id="noOfSeats">
                                            @for($i=1;$i<=99;$i++)
                                                <option value="{{ $i }}">{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div id="accountdetails" class="content" role="tabpanel" aria-labelledby="accountdetails-trigger">
                                <div class="form-group row">
                                    <label for="user" class="col-sm-2 col-form-label">User ID</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="user" id="user">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" name="email" id="email">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_group" class="col-sm-2 col-form-label">User Group</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="user_group" id="user_group">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-2 col-form-label">Full Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="full_name" id="full_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="pass" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="pass" id="pass">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="confirm_pass" class="col-sm-2 col-form-label">Confirm Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" name="confirm_pass" id="confirm_pass">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="active" class="col-sm-2 col-form-label">Active</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="active" id="active">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="server_ip" class="col-sm-2 col-form-label">Server IP</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="server_ip" id="server_ip">
                                            <option value="127.0.0.1">127.0.0.1 - Vonexta meetme server</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="vonexta-modal-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-4 col-lg-4" style="text-align:left;">
                                <button class="btn btn-md btn-block btn-secondary" id="btnPreviousStep" style="display:none;">Previous</button>
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4"></div>
                            <div class="col-sm-12 col-md-4 col-lg-4" style="text-align:right;">
                                <button class="btn btn-md btn-block btn-primary" id="btnNextStep">Next</button>
                                <button class="btn btn-md btn-block btn-success" type="submit" id="btnSubmit" style="display:none;">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <!-- /.Modals -->
@endSection

@push('scripts')
    <script>
        //Any constants to be used by this service/module...
        const service = "{{ $service }}";
       
    </script>
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
    <!-- BS-Stepper -->
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('views/services/dialler/users/index.js') }}"></script>
@endpush
