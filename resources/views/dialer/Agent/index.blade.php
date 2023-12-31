@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">  
    
    
    <!-- Ion Slider -->
<link rel="stylesheet" href="{{ asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
<!-- bootstrap slider -->
<link rel="stylesheet" href="{{ asset('plugins/bootstrap-slider/css/bootstrap-slider.min.css') }}">



@endpush
@section('content')

<!-- Content Wrapper. Contains page content -->
@if (auth()->user()->hasRole('user'))
<div class="content-wrapper" style="margin-left: 0rem">
@else
<div class="content-wrapper">
@endif


{{-- <div class="content-wrapper"> --}}

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

        @elseif(session()->has('bluck_success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    failed("{{ session('bluck_success') }}");
                });
            </script> 
     @endif

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



            <a href="javascript:void(0);" onclick='bulk_button_action("emergency",{{$organization_servicesID}});' class="btn btn-sm btn-warning check_btn d-none" >
                <i class="fas fa-sign-out-alt"></i> Emergency Logout
              </a>

              <a href="javascript:void(0);"  onclick='bulk_button_action("disable" ,{{$organization_servicesID}});' class="btn btn-sm btn-secondary check_btn d-none" >
                <i class="fas fa-ban"></i> Disable
              </a>

              <a href="javascript:void(0);"  onclick='bulk_button_action("delete" ,{{$organization_servicesID}});' class="btn btn-sm btn-danger check_btn d-none" >
                <i class="fas fa-trash"></i> Delete
              </a>

            <a href="javascript:void(0);" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#add-agent-model" > <i class="fas fa-plus"></i> Add Agent</a>
            

            {{-- <a href="javascript:void(0);" class="btn btn-sm btn-primary mr-2" id="btnAddUser">
              <i class="fas fa-plus"></i> Add Agent
            </a> --}}

            

            <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="Modal2">
                <i class="fas fa-plus"></i> Add Bulk Users
              </a>


            

          </div>
        </div>
        <div class="card-body">
            @php
                $agent_user_detail = [];
            @endphp
            <table id="agent_list" class="table table-striped table-hover vonexta-table">
                <thead>
                    <tr>
                        <th></th>
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
                    @endphp

                    @if ($detail['result'] == 'success' )
                    @php
                        $detail = $detail['data'];
                        array_push($agent_user_detail, $detail);
                    @endphp

                    <tr>
                        <td> <input type="checkbox" style="width: 20px; height: 20px; margin-top: 7px;" value="{{$detail['user']}}" id="{{$detail['user']}}" class="form-control" onclick="bulk_button(this.id);" ></td>
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
                                  
                                  @php
                                     $extension =  $detail['user'];
                                  @endphp
                                  <a class="dropdown-item" href="javascript:;" onclick="EmergencyLogout('{{$service}}' , {{$service_User->services_id}} , {{$extension}}   )">Emergency Logout</a>
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




  {{-- Add Agent User --}}

  <div class="modal fade" id="add-agent-model">
    <div class=" modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Add New Agent</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12">
                  <div class="card card-default">
                    <div class="card-body p-0">
                      <div class="bs-stepper">
                        <div class="bs-stepper-header" role="tablist">
                          <!-- your steps here -->
                          <div class="step" data-target="#Users-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="Users-part" id="Users-part-trigger">
                              <span class="bs-stepper-circle">1</span>
                              <span class="bs-stepper-label">Users</span>
                            </button>
                          </div>
                          <div class="line"></div>
                          <div class="step" data-target="#Options-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="Options-part" id="Options-part-trigger">
                              <span class="bs-stepper-circle">2</span>
                              <span class="bs-stepper-label">information</span>
                            </button>
                          </div>

                          <div class="line"></div>
                          <div class="step" data-target="#skills-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="skills-part" id="skills-part-trigger">
                              <span class="bs-stepper-circle">3</span>
                              <span class="bs-stepper-label">skills</span>
                            </button>
                          </div>

                          {{-- <div class="line"></div>
                          <div class="step" data-target="#Success-part">
                            <button type="button" class="step-trigger" role="tab" aria-controls="Success-part" id="Success-part-trigger">
                              <span class="bs-stepper-circle">4</span>
                              <span class="bs-stepper-label">Success</span>
                            </button>
                          </div> --}}


                        </div>


                        <div class="bs-stepper-content" id="add_agent_form">
                          <!-- your steps content here -->
                          <form action="{{ route('services.save-agent', ['service' => strtolower('Dailer')]) }}" method="post">
                            <input type="hidden" name="organization_servicesID" value="{{$organization_servicesID}}">
                            <input type="hidden" name="orgID" value="{{$organization->id}}">
                            @csrf
                            <div id="Users-part" class="content" role="tabpanel" aria-labelledby="Users-part-trigger">
                                
                                <div class="form-group row">
                                    <label for="user_group" class="col-sm-2 col-form-label">Users  <span class="text-danger">*</span> </label>
                                    <div class="col-md-7">
                                        <select class="form-control" name="organization_user" id="organization_user">
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
                                    <label for="user_group" class="col-sm-2 col-form-label">Extention <span class="text-danger">*</span> </label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" onkeyup="allow_only_number(this.id);"  onchange="check_extension(this.id,{{$organization_servicesID}} ,'add-agnet');" required name="user" id="user">
                                        <span style = "color:red" id="extension-error"></span>
                                        <span style = "color:green" id="extension-success"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="user_group" class="col-sm-2 col-form-label">Group <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        @php
                                        $responses = get_group_user($organization_servicesID);
                                        // dd($responses);
                                        @endphp
                                        <select name="user_group" class="form-control select2" id="user_group">
                                            <option value="" selected disabled>Select Group</option>
                                            @foreach ($responses as $response)
                                            <option value="{{$response}}" {{ isset($dailer_agent_user['user_group']) && $dailer_agent_user['user_group'] == $response ? 'selected' : '' }}>{{ $response }}</option>
                                            @endforeach
                                            
                                        </select>

                                        {{-- <input type="text" class="form-control" required name="user_group" id="user_group"> --}}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="full_name" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" required name="full_name" id="full_name">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="active" class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                                    <div class="col-sm-10">
                                        <select class="form-control" required name="active" id="active">
                                            <option value="1">Active</option>
                                            <option value="0">No Active</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="active" class="col-sm-2 col-form-label">Copy settings from other user: ?</label>
                                    <div class="col-sm-10 col-md-6">

                                        <select class="form-control" name="other_user" id="other_user">
                                            <option value="" selected disabled>Default No </option>
                                            @foreach ($agent_user_detail as $i=>$agent_us)
                                            <option value="{{$agent_us['user']}}"> {{$agent_us['user']}} | {{$agent_us['full_name']}}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-md-2 ">
                                        <button type="button"  class="btn btn-primary"  onclick="validateAndNext() ,copy_agent_detail({{$organization_servicesID}})">Next</button>
                                    </div>
                                    
                                </div>
                            </div>
                            
                            <div id="Options-part" class="content" role="tabpanel" aria-labelledby="Options-part-trigger">
                                <div class="row">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">SMS number</label>
                                            <input type="text" class="form-control" id="Sms_number" name="Sms_number"  placeholder="Mobile number"><br>
                                        </div>
                                    </div>
                                
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"  id="agent_choose_ingroups" name="agent_choose_ingroups" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                            <label class="form-check-label" for="agent_choose_ingroups"> <b>Select Inbound Upon Login</b> </label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input type="checkbox" class="form-check-input"  id="agent_choose_blended" name="agent_choose_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                        <label class="form-check-label" for="agent_choose_blended"> <b>Select Auto-Outbound Upon Login</b> </label>
                                    </div>
                                </div>
                                
                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"  id="closer_default_blended" name="closer_default_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                            <label class="form-check-label" for="closer_default_blended"><b>Allow Outbound</b></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input type="checkbox" class="form-check-input"  id="scheduled_callbacks" name="scheduled_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                        <label class="form-check-label" for="scheduled_callbacks"><b>Allow Schedule Callbacks</b></label>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input"  id="agentonly_callbacks" name="agentonly_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                        <label class="form-check-label" for="agentonly_callbacks"><b>Allow Personal Callbacks</b></label>
                                        </div>
                                    </div>

                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <input type="checkbox" class="form-check-input"  id="agentcall_manual" name="agentcall_manual" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                        <label class="form-check-label" for="agentcall_manual"><b>Allow Manual Calls</b></label>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" id="agent_call_log_view_override" name="agent_call_log_view_override" data-bootstrap-switch data-off-color="danger" data-on-color="success" >
                                            <label class="form-check-label" for="agent_call_log_view_override"><b>Allow Call Log View</b></label>
                                        </div>
                                    </div>
                                </div>


                                <div class="row mb-3">
                                    <div class="col-md-4 mt-2">
                                        <label> Inbound Calls Limit: </label>
                                        <input id="max_inbound_calls" type="text" name="max_inbound_calls" value="">
                                    </div>
                                  </div>

                                {{-- //limit --}}
                                {{-- <div class="row mb-3">          
                                    <div class="col-md-4 mt-2">
                                        <label>Inbound Calls Limit: </label>
                                        <input type="range" id="fixtures-events-range" name="max_inbound_calls" id="max_inbound_calls" min="1" max="1000" value="" oninput="update_input_value(this.id)">
                                    </div>
                                    <div class="col-md-2">
                                        <div class="slidercontainer"> 
                                            <input class="form-control" type="number" readonly id="fixtures-events">
                                        </div>
                                    </div>
                                </div> --}}


                                <div class="row justify-content-between">
                                    <div class="col-md-4">
                                        <button type="button" class="btn btn-primary"  onclick="stepper.previous()">Previous</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button"  class="btn btn-primary"  onclick="stepper.next()">Next</button>
                                    </div>
                                </div>
                              
                               
                            </div>

                            <div id="skills-part" class="content" role="tabpanel" aria-labelledby="skills-part-trigger">
                                
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
                                                <a href="#" class="btn btn-sm btn-primary" class="btnEdit" data-id=""><i class="fas fa-pen"></i><a>
                                                <a href="#" class="btn btn-sm btn-danger" class="btnDelete" data-id=""><i class="fas fa-trash"></i><a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>




                                <div class="row justify-content-between">
                                    <div class="col-md-4">
                                        <button type="button"  class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                    </div>
                                    <div class="col-md-4">
                                        <button type="submit" class="btn btn-primary" onclick="add_agent()" >Save</button>
                                    </div>
                                </div>
                               
                                

                               
                            </div>

                          </form>
                          
                        </div>
                      </div>
                    </div>
                  
                  </div>
                  <!-- /.card -->
                </div>
              </div>

          {{-- <p>One fine body&hellip;</p> --}}
        </div>
      </div>
    </div>
  </div>


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


                    <form action="{{ route('services.bulk-save-agent', ['service' => strtolower('Dailer')]) }}" method="post" id="bulk_agent_save_form">
                        @csrf
                    <input type="hidden" name="organization_servicesID" id="organization_servicesID" value="{{$organization_servicesID}}">
                    <input type="hidden" name="orgID" value="{{$organization->id}}">

                    <div class="modal-body">



                        <div class="col-md-6 mb-3">
                            <label for=""> Copy settings from other user:  <span class="text-danger">*</span></label>
                            <select class="form-control" name="other_user" id="other_user_bulk">
                                <option value="" selected disabled>Default No </option>
                                @foreach ($agent_user_detail as $i=>$agent_us)
                                <option value="{{$agent_us['user']}}"> {{$agent_us['user']}} | {{$agent_us['full_name']}}</option>
                                @endforeach
                            </select>

                        </div>
                        
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>User<span class="text-danger">*</span></th>
                                    <th>Extension <span class="text-danger">*</span> </th>
                                    <th>Full Name <span class="text-danger">*</span></th>
                                    {{-- <th>Password</th> --}}
                                    <th>Status <span class="text-danger">*</span> </th>
                                    <th>Action <span class="text-danger">*</span> </th>
                                </tr>
                            </thead>
                            <tbody class="add_row">
                                <tr  id="1">
                                    <td>
                                        <select class="form-control select2" name="email[]" id="email_1" style="width: 100%;">
                                            <option value="" selected disabled >Select Organization User</option>
                                            @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach 
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control" onkeyup="allow_only_number(this.id);"  onchange="check_extension(this.id,{{$organization_servicesID}} ,'add-bulk-agnet');"  name="extension[]" id="extension_1">

                                        <span style = "color:red" id="extension-error-1"></span>
                                        <span style = "color:green" id="extension-success-1"></span>

                                    </td>

                                    <td><input type="text" class="form-control" name="full_name[]" id="full_name_1"></td>
                                    {{-- <td><input type="password" class="form-control" name="password[]" id="password_1"></td> --}}
                                    <td>
                                        <select class="form-control" required name="active[]" id="active_1">
                                            <option value="1">Active</option>
                                            <option value="0">Not Active</option>
                                        </select>

                                        {{-- <input type="text" class="form-control" name="status[]" id="status_1"> --}}
                                    </td>
                                    <td><button type="button" onclick="add_row();" class="btn btn-success btn-sm">+</button></td>
                                </tr>
                            </tbody>
                        </table>

                      
                        
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="button" onclick="bulk_agent_add();" class="btn btn-primary">Add</button>
                    </div>

                    </form>


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

 <!-- Bootstrap Switch -->
 <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

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

        <!-- Ion Slider -->
    <script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <!-- Bootstrap slider -->
    <script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js') }}"></script>




    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('views/services/dialler/users/index.js') }}"></script>


    <script>
        $(function () {
          // Initialize the ionRangeSlider
          $('#max_inbound_calls').ionRangeSlider({
            min     : 1,
            max     : 1000,
            // from    : 0,
            type    : 'single',
            step    : 1,
            postfix : 'Calls Limit',
            prettify: false,
            hasGrid : true
          });
    
        });
    
    </script>

@endpush
