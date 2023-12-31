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
            <a href="javascript:void(0);" class="btn btn-sm btn-primary mr-2" id="btnAddUser">
              <i class="fas fa-plus"></i> Add Agent
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="Modal2">
                <i class="fas fa-plus"></i> Add Bulk Users
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
                    {{-- @php
                        $services_id = $userAgent[0]->services_id;
                        $agent_user = [];
                    @endphp --}}
                    @foreach ($userAgent as $i=>$service_User)
                    @php
                        $detail = get_agent_detail($service_User->services_id , $service_User->api_user );
                    @endphp

                    @if ($detail['result'] == 'success' )
                    @php
                        $detail = $detail['data'];
                        $agent_user[$detail['user']] = $detail['full_name'];
                    @endphp

                    <tr>
                        <td>
                            {{$service_User->user_detail->email}}
                        </td>
                        <td>
                            {{$detail['user']}}
                        </td>
                        <td>
                            {{$detail['full_name']}}
                        </td>
                        <td>
                            {{$detail['user_group']}}
                        </td>
                        <td>
                            @if ($detail['active'] == 'Y')
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
                                  <a class="dropdown-item" href="{{ route('services.agents.edit', ['service' => strtolower($service), 'organization_services_id' => $service_User->services_id ,'AgentID' => $detail['user']  ] ) }}">Modify</a>
                                  <a class="dropdown-item" href="#">Logs</a>
                                  <a class="dropdown-item" href="#">Emergency Logout</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="#">Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    

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
    <!-- Modals... -->
    <div class="modal fade" id="modalNewUser">
        <form id="" action="{{route('add-agents')}}" method="post" class="form-horizontal" >
            @csrf
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
                            <input type="hidden" value="{{$organization_servicesID}}" name="organization_servicesID">
                            <div class="bs-stepper-header" role="tablist">
                                <!-- your steps here -->
                                <div class="step" data-target="#gettingstarted">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="gettingstarted" id="gettingstarted-trigger">
                                        <span class="bs-stepper-circle">1</span>
                                        <span class="bs-stepper-label">Users</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#accountdetails">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="accountdetails" id="accountdetails-trigger">
                                        <span class="bs-stepper-circle">2</span>
                                        <span class="bs-stepper-label">Options</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#skills">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="skills" id="skills-trigger">
                                        <span class="bs-stepper-circle">3</span>
                                        <span class="bs-stepper-label">Skills</span>
                                    </button>
                                </div>
                                <div class="line"></div>
                                <div class="step" data-target="#Success">
                                    <button type="button" class="step-trigger" role="tab" aria-controls="Success" id="Success-trigger">
                                        <span class="bs-stepper-circle">4</span>
                                        <span class="bs-stepper-label">Success</span>
                                    </button>
                                </div>
                            </div>

                            <div class="bs-stepper-content">
                                <!-- your steps content here -->
                                <div id="gettingstarted" class="content" role="tabpanel" aria-labelledby="gettingstarted-trigger">
                                    <div class="form-group row">
                                        <label for="user_group" class="col-sm-2 col-form-label">Total Users</label>
                                        <div class="col-md-7">
                                            <select class="form-control" name="" id="">
                                                <option value="" selected disabled >Select Organization User</option>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <button class="btn btn-primary btn-sm ">+</button>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="user_group" class="col-sm-2 col-form-label">Extention</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" onchange="check_extension(this.id,{{$organization_servicesID}});" required name="user" id="user">
                                            <span style = "color:red" id="extension-error"></span>
                                            <span style = "color:green" id="extension-success"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="user_group" class="col-sm-2 col-form-label">Group</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" required name="user_group" id="user_group">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="full_name" class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" required name="full_name" id="full_name">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="active" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" required name="active" id="active">
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="active" class="col-sm-2 col-form-label">Copy settings from other user: ?</label>
                                        <div class="col-sm-10 col-md-6">
                                            <select class="form-control" name="active" id="active">
                                                <option value="" selected disabled>Default No </option>
                                                @foreach ($agent_user as $i=>$agent_us)
                                                <option value="{{$agent_user[$i]}}">{{$agent_us}}</option>
                                                @endforeach
                                            
                                            
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div id="accountdetails" class="content" role="tabpanel" aria-labelledby="accountdetails-trigger">
                                    <div class="row">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">SMS number</label>
                                                        <input type="text" class="form-control" id="Sms_number" name="Sms_number" value="{{ isset($dailer_agent_user['mobile_number']) ? $dailer_agent_user['mobile_number'] : '' }}" placeholder="Mobile number"><br>
                                                    </div>
                                                </div>
                                            
                                            </div>
        
                                            <div class="row mb-3">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_ingroups']) && $dailer_agent_user['agent_choose_ingroups']=='1' ) ? 'checked' : '' }} id="toggle1" name="agent_choose_ingroups" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                        <label class="form-check-label" for="toggle1"> <b>Select Inbound Upon Login</b> </label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_blended']) && $dailer_agent_user['agent_choose_blended']=='1' ) ? 'checked' : '' }} id="toggle2" name="agent_choose_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                    <label class="form-check-label" for="toggle2"> <b>Select Auto-Outbound Upon Login</b> </label>
                                                </div>
                                            </div>
        
        
        
                                            <div class="row mb-3">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['closer_default_blended']) && $dailer_agent_user['closer_default_blended']=='1' ) ? 'checked' : '' }} id="toggle3" name="closer_default_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                        <label class="form-check-label" for="toggle3"><b>Allow Outbound</b></label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['scheduled_callbacks']) && $dailer_agent_user['scheduled_callbacks']=='1' ) ? 'checked' : '' }} id="toggle4" name="scheduled_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                    <label class="form-check-label" for="toggle4"><b>Allow Schedule Callbacks</b></label>
                                                </div>
                                            </div>
        
                                            <div class="row mb-3">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentonly_callbacks']) && $dailer_agent_user['agentonly_callbacks']=='1' ) ? 'checked' : '' }} id="toggle5" name="agentonly_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                    <label class="form-check-label" for="toggle5"><b>Allow Personal Callbacks</b></label>
                                                    </div>
                                                </div>
        
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentcall_manual']) && $dailer_agent_user['agentcall_manual']=='1' ) ? 'checked' : '' }} id="toggle6" name="agentcall_manual" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                    <label class="form-check-label" for="toggle6"><b>Allow Manual Calls</b></label>
                                                </div>
                                            </div>
        
                                            <div class="row mb-3">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_call_log_view_override']) && $dailer_agent_user['agent_call_log_view_override']=='1' ) ? 'checked' : '' }} id="toggle6" name="agent_call_log_view_override" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                                        <label class="form-check-label" for="toggle7"><b>Allow Call Log View</b></label>
                                                    </div>
                                                </div>
                                            </div>
        
                                            {{-- //limit --}}
                                            <div class="row mb-3">          
                                                <div class="col-md-4 mt-2">
                                                    <label>Inbound Calls Limit: </label>
                                                    <input type="range" id="fixtures-events-range" name="max_inbound_calls" id="max_inbound_calls" min="1" max="1000" value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}" oninput="update_input_value(this.id)">
                                                </div>
                                                <div class="col-md-2">
                                                    <div class="slidercontainer"> 
                                                        <input class="form-control" type="number" readonly id="fixtures-events">
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                    </div>
                                </div>
                                <div id="skills" class="content" role="tabpanel" aria-labelledby="skills-trigger">
                                    <div class="card">
                                        <div class="card-header">  
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-default">Compaigns</button>
                                                <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                                </button>
                                                <div class="dropdown-menu" role="menu">
                                                <a class="dropdown-item" href="#">allowed campaigns</a>
                                                <a class="dropdown-item" href="#">inbound groups</a>
                                                </div>
                                            </div>
                                            <div class="card-body">

                                                <table id="compaignns" class="table table-striped table-hover vonexta-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Level</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>0000</td>
                                                            <td>0000</td>
                                                            <td>
                                                                <a href="#" class="btn btn-sm btn-primary" class="btnEdit" data-id=""><i
                                                                        class="fas fa-pen"></i><a>
                                                                        <a href="#" class="btn btn-sm btn-danger" class="btnDelete"
                                                                            data-id=""><i class="fas fa-trash"></i><a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                
                                                </table>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div id="Success" class="content" role="tabpanel" aria-labelledby="Success-trigger">
                                    <div class="">
                                        <h3 style="color: red">Success message to be written here..</h3>
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
                                    <button class="btn btn-md btn-block btn-primary" type="button" id="btnNextStep">Next</button>
                                    {{-- <button class="btn btn-md btn-block btn-primary" id="btnNextStep">Next</button> --}}
                                    <button class="btn btn-md btn-block btn-success" type="submit" id="btnSubmit" style="display:none;">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
        </form>
    </div>
    <!-- /.Modals -->


    {{-- modal2 --}}
    <div class="modal fade" id="NewModal">
        <div class="bs-stepper" id="newUserWizard">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Add bulk users:</h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="col-md-6 mb-3">
                            <label for=""> Copy settings from other user: ?</label>
                            <select class="form-control" name="" id="">
                                <option value="">user1</option>
                               
                            </select>
                        </div>
                        
                        <table id="bulk-table" class="table table-striped table-hover vonexta-table">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Extension</th>
                                    <th>Full Name</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody class="add_row">
                                <tr  id="1">
                                    <td><input type="email" class="form-control" name="email[]" id="email"></td>
                                    <td><input type="text" class="form-control" name="extension[]" id="extension"></td>
                                    <td><input type="text" class="form-control" name="full_name[]" id="full_name"></td>
                                    <td><input type="password" class="form-control" name="password[]" id="password"></td>
                                    <td><input type="text" class="form-control" name="status[]" id="status"></td>
                                    <td><button onclick="add_row();" class="btn btn-success btn-sm">+</button></td>
                                </tr>
                            </tbody>
                        </table>

                      
                        
                    </div>
                </div>
            </div> 
        </div>
    </div>
  
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

    <script>

        function update_input_value(id){
        var range = $("#"+id).val();
        var input_filed = id.replace("-range", "");
        $("#"+input_filed).val(range);
}
    </script>
@endpush
