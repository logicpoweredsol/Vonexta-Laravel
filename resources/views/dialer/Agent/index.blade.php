@extends('layouts.app')
@push('css')

<style>
    .bootstrap-switch{
        height: 24px !important;
    }
</style>
@endpush
@section('content')

<!-- Content Wrapper. Contains page content -->
@if (auth()->user()->hasRole('user'))
<div class="content-wrapper" style="margin-left: 0rem">
    @else
    <div class="content-wrapper">
        @endif

        @if(session()->has('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                success("{{ session('success') }}");
            });
        </script>
        @elseif (session()->has('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                error("{{ session('error') }}");
            });
        </script>

        @elseif(session()->has('add_more_agent'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                add_more_agent("{{ session('add_more_agent') }}");
            });
        </script>

        @elseif(session()->has('add-user-by-agent'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                add_user_by_agent("{{ session('add-user-by-agent') }}");
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
                            @php $nick_name  =service_name($organization_servicesID); @endphp
                            <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_servicesID]) }}">{{$nick_name}} </a></li>
                            <li class="breadcrumb-item active"><a href="javascript:;">Agents</a></li>
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



                        <a href="javascript:void(0);" onclick='bulk_button_action("emergency",{{$organization_servicesID}});' class="btn btn-sm btn-warning check_btn d-none">
                            <i class="fas fa-sign-out-alt"></i> Emergency Logout
                        </a>

                        <a href="javascript:void(0);" onclick='bulk_button_action("disable" ,{{$organization_servicesID}});' class="btn btn-sm btn-secondary check_btn d-none">
                            <i class="fas fa-ban"></i> Disable
                        </a>

                        <a href="javascript:void(0);" onclick='bulk_button_action("delete" ,{{$organization_servicesID}});' class="btn btn-sm btn-danger check_btn d-none">
                            <i class="fas fa-trash"></i> Delete
                        </a>

                        <a href="javascript:void(0);" type="button" class="btn btn-sm btn-primary" onclick="add_agent_model();"> <i class="fas fa-plus"></i> Add Agent</a>

                        <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="Modal2">
                            <i class="fas fa-plus"></i> Add Bulk Agents
                        </a>




                    </div>
                </div>
            <div class="card-body" style="position: relative;">
                    <div class="acbz" style="position: absolute;width: 250px;right: 275px;top: 20px ;z-index:9;">

                        <div class="btn-group" style="margin-left:50px;">
                            <button type="button" class="btn btn-default">Status - <span id="cur_status">All</span> </button>
                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <div class="dropdown-menu" role="menu" >
                                <a class="dropdown-item" href="javascript:;" onclick="search_filter('all')">All</a>
                                <a class="dropdown-item" href="javascript:" onclick="search_filter('Active')" >Active</a>
                                <a class="dropdown-item" href="javascript:" onclick="search_filter('Not Active')" >Not Active</a>
                            </div>
                        </div>
                    </div>
                    
                    <table id="tbl" class="table table-striped table-hover vonexta-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>User</th>
                                <th>Extension</th>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>



                            @foreach ($userAgents as $index => $userAgent)
                            <tr>
                                <td> <input type="checkbox" style="width: 20px; height: 20px; margin-top: 7px;" value="{{$userAgent->user}}" id="{{$userAgent->user}}" class="form-control" onclick="bulk_button(this.id);"></td>

                                <td>{{ $userAgent->email }}</td>



                                <td>{{ $userAgent->user }}</td>


                                <td>{{ $userAgent->full_name }}</td>

                                <td>{{ $userAgent->User_Group }}</td>






                                <td>
                                    @if ($userAgent->active == 'Y')
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
                                            <a class="dropdown-item" href="{{ route('services.agents.edit', ['service' => strtolower($service), 'organization_services_id' => $organization_servicesID ,'AgentID' => $userAgent->user  ] ) }}">Edit</a>
                                            <a class="dropdown-item" href="{{ route('services.agents.log', ['service' => strtolower($service), 'organization_services_id' => $organization_servicesID ,'AgentID' => $userAgent->user  ] ) }}">Logs</a>
                                            <a class="dropdown-item" href="javascript:;" onclick="update_skill_modal('{{$service}}' , {{$organization_servicesID}} ,{{$userAgent->user}});">Update Skills</a>


                                            <a class="dropdown-item" href="javascript:;" onclick="EmergencyLogout('{{$service}}' , {{$organization_servicesID}} ,{{$userAgent->user}}   )">Emergency Logout</a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#">Delete</a>
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
                                                    <span class="bs-stepper-label">Details</span>
                                                </button>
                                            </div>

                                            <div class="line"></div>
                                            <div class="step" data-target="#skills-part">
                                                <button type="button" class="step-trigger" role="tab" aria-controls="skills-part" id="skills-part-trigger" style="margin-right: 30px;">
                                                    <span class="bs-stepper-circle">2</span>
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
                                                <input type="hidden" id="organization_ser_id" name="organization_servicesID" value="{{$organization_servicesID}}">
                                                <input type="hidden" name="orgID" value="{{$organization->id}}">
                                                    @csrf
                                                <div id="Users-part" class="content" role="tabpanel" aria-labelledby="Users-part-trigger">

                                                    <div class="form-group row">
                                                        <label for="user_group" class="col-sm-2 col-form-label">User<span class="text-danger">*</span> </label>
                                                        <div class="col-md-7">
                                                            <select class="form-control select3" name="organization_user" id="organization_user">
                                                                <option value="" selected disabled>Select User</option>

                                                               
                                                                @foreach ($users as $user)
                                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-primary btn-sm" onclick="show_adduser_modal();">+</button>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="user_group" class="col-sm-2 col-form-label">Extension <span class="text-danger">*</span> </label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" onkeyup="allow_only_number(this.id);" onchange="check_extension(this.id,{{$organization_servicesID}} ,'add-agnet');" required value="{{$last_extension}}" name="user" id="user">
                                                            <span style="color:red" id="extension-error"></span>
                                                            <span style="color:green" id="extension-success"></span> 
                                                            <br>
                                                            <span style="color:red" id="error_extension-error"></span>
                                                            <span style="color:green" id="success_extension-success"></span>
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="name" class="col-sm-2 col-form-label">SMS Number</label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" name="Sms_number" id="Sms_number">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="user_group" class="col-sm-2 col-form-label">Role<span class="text-danger">*</span></label>
                                                        <div class="col-sm-10">
                                                            @php
                                                            $responses = get_group_user($organization_servicesID);
                                                          
                                                            @endphp
                                                            <select name="agent_role" class="form-control select3" id="agent_role">
                                                                <option value="" selected disabled>Select Role</option>
                                                                @foreach ($responses as $response)
                                                                    <option value="{{$response}}" {{ isset($dailer_agent_user['user_group']) && $dailer_agent_user['user_group'] == $response ? 'selected' : '' }}>
                                                                        {{ $response }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="full_name" class="col-sm-2 col-form-label">Name <span class="text-danger">*</span></label>
                                                        <div class="col-sm-10">
                                                            <input type="text" class="form-control" required name="agent_name" id="agent_name">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="active" class="col-sm-2 col-form-label">Status <span class="text-danger">*</span></label>
                                                        <div class="col-sm-10">
                                                            <select class="form-control select4" required name="status" id="status">
                                                                <option value="1">Active</option>
                                                                <option value="0">No Active</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="active" class="col-sm-2 col-form-label">Get Skills From Agent:</label>
                                                        <div class="col-sm-10 col-md-6">

                                                            <?php
                                                            // Assuming $userAgents is an array of user agents
                                                            usort($userAgents, function ($a, $b) {
                                                                // Sorting based on the 'user' property in ascending order
                                                                return $a->user <=> $b->user;
                                                            });
                                                            ?>

                                                            <select class="form-control select3" name="other_user" id="other_user">
                                                                <option value="" selected>Disabled</option>
                                                                <?php foreach ($userAgents as $i => $userAgent) : ?>
                                                                    <option value="<?= $userAgent->user ?>"> <?= $userAgent->user ?> | <?= $userAgent->full_name ?></option>
                                                                <?php endforeach; ?>
                                                            </select>

                                                        </div>
                                                    </div>

                                                    <div class="row justify-content-end">
                                                    <div class="col-md-2">
                                                        <button type="button" id="extension_val_idd" class="btn btn-primary" style="margin-left: 100px;" onclick="validateAndNext(); copy_agent_skill();">Next</button>
                                                    </div>


                                                    </div>
                                                </div>



                                                <div id="skills-part" class="content" role="tabpanel" aria-labelledby="skills-part-trigger">
                                                    <div class="card-header">
                                                        <h3 class="card-title"><b>Inbound:</b></h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <table id="compaignnse" class="table table-striped table-hover vonexta-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Level</th>
                                                                    <th>Invited </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="append-html-agent-inblound">

                                                            @if($skills['Inbound']['result'] == 'success')
                                                                @foreach($skills['Inbound']['group_id'] as $row=>$call_log_inbound)
                                                                <tr>
                                                                <td>

                                                                <!-- name='group_id[]' -->
                                                                        <input type='text' id="model_group_id_{{$skills['Inbound']['group_id'][$row]}}" readonly value='{{$skills['Inbound']['group_id'][$row]}}' name='inbound_id[]' style='border:none;background: transparent;' />
                                                                        <span class="adminlet3" onclick="open_inbound_model('{{$skills['Inbound']['group_id'][$row]}}');"><i class="fas fa-list"></i></span>

                                                                </td>

                                                                    <td>
                                                                        <select class="form-control select4" style="border: none;"  id="model_level_{{$skills['Inbound']['group_id'][$row]}}"  name='level[]'>
                                                                            
                                                                            <?php for ($i = 1; $i <= 9; $i++) { ?>
                                                                                <option  value="{{$i}}"> {{$i}} </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" id="model_invited_{{$skills['Inbound']['group_id'][$row]}}"  name='row_{{$skills['Inbound']['group_id'][$row]}}'  class="form-check-input inbound-checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                                    </td>
                                                                
                                                                </tr>
                                                                @endforeach

                                                            @endif



                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row mb-3">
                                                            <div class="col-md-4 mt-2">
                                                                <label> Inbound Calls Limit: </label>
                                                                <input id="inbound_calls_limit" type="text" default="1000" name="inbound_calls_limit" value="">
                                                            </div>
                                                        </div>

                                                    </div>


                                                    <div class="card-header">
                                                        <h3 class="card-title"><b>Outbound: </b></h3>
                                                    </div>
                                                    <div class="card-body">
                                                        <table id="compaignns" class="table table-striped table-hover vonexta-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Name</th>
                                                                    <th>Level</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="append-html-agent-compaign">

                                                            @if($skills['Campaigns']['result'] == 'success')
                                                                @foreach($skills['Campaigns']['campaign_id'] as $row=>$call_log_Campaigns)
                                                                <tr>
                                                                    <td ><input id="model_profile_id_{{$skills['Campaigns']['campaign_id'][$row]}}"  type='text' readonly value='{{$skills['Campaigns']['campaign_id'][$row]}}' name='campaign_id[]' style='border:none;background: transparent;' />
                                                                    <span class="adminlet3" onclick="open_outbound_model('{{$skills['Campaigns']['campaign_id'][$row]}}');"><i class="fas fa-list"></i></span> </td>
                                                                    <td>
                                                                        <select class="form-control select4"  style="border: none;" id="model_prof_level_{{$skills['Campaigns']['campaign_id'][$row]}}" name='profile_id_level[]'>
                                                                            
                                                                            <?php for ($i = 1; $i <= 9; $i++) { ?>
                                                                                <option  value="{{$i}}"> {{$i}} </option>
                                                                            <?php } ?>
                                                                        </select>
                                                                    </td>
                                                                   
                                                                
                                                                </tr>
                                                                @endforeach

                                                            @endif

                                                            </tbody>
                                                        </table>
                                                    </div>




                                                    <div class="row justify-content-between">
                                                        <div class="col-md-4">
                                                            <button type="button" class="btn btn-primary" onclick="stepper.previous()">Previous</button>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <button type="submit" class="btn btn-primary" style="margin-left: 259px;" onclick="add_agent()">Save</button>
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
                </div>
            </div>
        </div>
    </div>


    <!-- bulk user modal -->
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
                                <label for=""> Get Skills From Agent: <span class="text-danger">*</span></label>
                                <select class="form-control select3" name="other_user" id="other_user_bulk">
                                    <option value="" selected>Disabled</option>
                                    @foreach ($userAgents as $i=>$userAgent)
                                    <option value="{{$userAgent->user}}"> {{$userAgent->user}} | {{$userAgent->full_name}}</option>
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
                                    <tr id="1">
                                        <td>
                                            <select class="form-control select3" name="email[]" id="email_1" style="width: 100%;">
                                                <option value="" selected disabled>Select User</option>
                                                @foreach ($users as $user)
                                                <option value="{{$user->id}}">{{$user->name}}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="text" class="form-control" onkeyup="allow_only_number(this.id);" onchange="check_extension(this.id,{{$organization_servicesID}} ,'add-bulk-agnet');" name="extension[]" id="extension_1">

                                            <span style="color:red" id="extension-error-1"></span>
                                            <span style="color:green" id="extension-success-1"></span>

                                        </td>

                                        <td><input type="text" class="form-control" name="full_name[]" id="full_name_1"></td>
                                        {{-- <td><input type="password" class="form-control" name="password[]" id="password_1"></td> --}}
                                        <td>

                                            {{-- active_1 --}}
                                            <select class="form-control select3" required name="status[]" id="status_1"> 
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




    <!-- addUserModal -->
    <div class="modal fade" id="add_organization_user">
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
                    <form id="systemUsersForm" action="{{ route("administration.users.store_user_by_agent_side") }}" method="post" class="form-horizontal">
                        @csrf

                        <div>
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
                                <label for="email" class="col-sm-2 col-form-label">Phone</label>
                                <div class="col-sm-10">
                                <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" placeholder="Phone" @error('Phone') aria-invalid="true" @enderror>
                                    <span class="error">
                                        @error('Phone')
                                        <label id="Phone-error" class="error invalid-feedback" for="Phone" style="display: inline-block;">{{ $message }}</label>
                                        @enderror
                                    </span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="password" class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="password" name="password" value="{{ old('password')  }}" placeholder="Password">
                                    <span class="error"></span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="role" class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-control select3" id="role" name="role" value="{{ old('role')  }}" onchange='toggal_service();'>
                                        <option value="admin">Admin</option>
                                        <option value="user">User</option>
                                    </select>
                                    <span class="error"></span>
                                </div>
                            </div>


                            <div class="form-group row" id='div_services'>
                                <label for="Services_row" class="col-sm-2 col-form-label">Services</label>
                                <div class="col-sm-10">
                                    <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>
                                        @foreach($user_have_service as $Services)

                                        <div class="col-sm-6 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->user_have_service->service_nick_name) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="{{ $Services->user_have_service->id }}">
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

<!-- /.card -->
</form>

</div>
<div class="modal-footer justify-content-between">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    <button type="button" onclick="save_systemUsersForm();" class="btn btn-primary">Save changes</button>
</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>




<!-- UpdateSkillModal -->
<div class="modal fade" id="update_skill_modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title">Update Skills for <span id="modell_user"></span>  </h4>

                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <input type="hidden"  id="model_organization_services_id" readonly>
            <input type="hidden"id="model_User" readonly>
                {{-- Inbound --}}
                <div class="card inbounds mt-3">
                    <div class="card-header">
                        <h3 class="card-title"><b>Inbound</b></h3>

                    </div>
                    <div class="card-body">

                        <table id="Enbound-1" class="table table-striped table-hover vonexta-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Invited </th>
                                    <th>Calls Today </th>
                                </tr>
                            </thead>
                            <tbody id="Enbound_body">



                            </tbody>

                        </table>
                    </div>

                </div>


                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 mt-2">
                            <label> Inbound Calls Limit: </label>
                            <input id="max_inbound_calls1" type="text" oninput="update_inblound_call_limit(this.id);" name="max_inbound_calls1" value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}">
                        </div>
                    </div>
                </div>


                <!-- outbound -->
                <div class="card compaigns">
                    <div class="card-header">
                        <h3 class="card-title"><b>Outbound</b></h3>
                    </div>
                    <div class="card-body">

                        <table id="Campaigns-1" class="table table-striped table-hover vonexta-table">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Level</th>
                                    <th>Calls Today</th>
                                </tr>
                            </thead>
                            <tbody id="Campaigns-body">
     

                            </tbody>

                        </table>
                    </div>

                </div>


            </div>
          
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>



<div class="modal fade" id="detail_modal">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id='skill_name'></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <table class='table table-striped table-hover vonexta-table'>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Extension</th>
                        <th>Invited</th>
                        <th>Level</th>
                    </tr>
                </thead>
                <tbody id='detail_modal_body'>
                    <tr>
                    
                    </tr>
                </tbody>
            </table>
            
        </div>
        <!-- <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div> -->
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- Modal for outbound Skill -->
<div class="modal fade" id="Outbound-Skill">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title" id='skill_name'></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <table class='table table-striped table-hover vonexta-table'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Extension</th>
                            <th>Level</th>
                        </tr>
                    </thead>
                    <tbody id='Outbound-Skill-body'>
                        <tr>
                        
                        </tr>
                    </tbody>
                </table>
              
            </div>
            <!-- <div class="modal-footer justify-content-between">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary">Save changes</button>
            </div> -->
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
</div>

</div>


@endSection

@push('scripts')




<script>
    //Any constants to be used by this service/module...
    const service = "{{ $service }}";
</script>





@endpush