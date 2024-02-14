@extends('layouts.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">


<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">


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
        @endif

        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">

                        @php
                        $email_address = get_email($dailer_agent_user['user']);
                        @endphp


                        <h1>Edit {{ $email_address}} - {{$dailer_agent_user['user']}} </h1>
                    </div>


                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">

                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                            @php $nick_name  =service_name($organization_services_id); @endphp
                            <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_services_id]) }}">{{$nick_name}} </a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('services.agents', ['service' => strtolower($service), 'organization_services_id' => $organization_services_id]) }}">Agents</a></li>
                            <li class="breadcrumb-item active">Edit Agents</li>
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
                            <a class="nav-link @if( !session()->has('tab') && !session('tab')) active @endif " id="Organization-home-tab" data-toggle="pill" href="#Organization-home" role="tab" aria-controls="Organization-home" aria-selected="true">Details</a>
                        </li>

                        <!-- <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="options-tab" data-toggle="pill" href="#options" role="tab" aria-controls="campaigns-disposition" aria-selected="false">Options</a>
                        </li> -->

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="Organization-user-tab" data-toggle="pill" href="#Organization-user" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Skills</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link @if(session()->has('tab') && session('tab') == 'call-Logs')  active @endif " id="call-logs-tab" data-toggle="pill" href="#call-log" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Call Logs</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="activity-tab" data-toggle="pill" href="#activity" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Activity</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="campaigns-tabs Content">

                        <div class="tab-pane fade show @if( !session()->has('tab') && !session('tab')) show active @endif   " id="Organization-home" role="tabpanel" aria-labelledby="Organization-home-tab">
                            <form method="POST" action="{{ route('services.update-agent.details', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
                                <input type="hidden" class="form-control" id="User" name="User" value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}">
                                <input type="hidden" class="form-control" id="organization_services_id" name="organization_services_id" value="{{$organization_services_id}}">


                           

                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Edit Agent</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Name</label>
                                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ isset($dailer_agent_user['full_name']) ? $dailer_agent_user['full_name'] : '' }}" placeholder="Agent name">
                                                </div>
                                                <span>
                                                    @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </span>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="phone" class="form-label">Email</label>

                                                    <input type="email" class="form-control" id="email" name="email" readonly value="{{$email_address}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="address" class="form-label">Extension</label>
                                                    <input type="text" class="form-control" id="extension" name="extension" disabled value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}" placeholder="User">
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="Voice Mail" class="form-label">Voicemail</label>

                                                    @php
                                                    $responds = get_voicemail($organization_services_id);

                                                    $integerPart = filter_var($dailer_agent_user['phone_login'], FILTER_SANITIZE_NUMBER_INT);

                                                    @endphp

                                                    <select name="voice_mail" @if (!$responds) disabled @endif class="form-control select2" id="voice_mail">
                                                        <option value="" @if(!isset($dailer_agent_user['voicemail_id'])) selected @endif>No Voicemail</option>
                                                        <option value="{{$integerPart}}" @if(isset($dailer_agent_user['voicemail_id']) && $dailer_agent_user['voicemail_id'] == $integerPart) selected @endif>Agent's Voicemail ({{$integerPart}})</option>
                                                        
                                                        @foreach ($responds['voicemail_id'] as $i=>$respond)
                                                            <option value="{{ $responds['voicemail_id'][$i] }}" @if(isset($dailer_agent_user['voicemail_id']) && $dailer_agent_user['voicemail_id'] == $responds['voicemail_id'][$i]) selected @endif>
                                                                {{ $responds['voicemail_id'][$i] }} - {{ $responds['fullname'][$i] }}
                                                            </option>
                                                        @endforeach
                                                    </select>

                                                    {{-- <input type="text" class="form-control" id="Voice_Mail" name="Voice_Mail" value="{{ isset($dailer_agent_user['voicemail_id']) ? $dailer_agent_user['voicemail_id'] : '' }}" placeholder="Voice Mail.."> --}}
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="Group" class="form-label">Role</label>
                                                    {{-- <input type="text" class="form-control" id="group" name="group" value="{{ isset($dailer_agent_user['user_group']) ? $dailer_agent_user['user_group'] : '' }}" placeholder="Group .."> --}}

                                                    @php
                                                    $responses = get_group_user($organization_services_id);
                                                     
                                                    @endphp
                                                    <select name="role" class="form-control select2 " id="role">
                                                        @foreach ($responses as $response)
                                                        <option value="{{$response}}" {{ isset($dailer_agent_user['user_group']) && $dailer_agent_user['user_group'] == $response ? 'selected' : '' }}>{{ $response }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">Agent Line</label>
                                                    <input type="text" class="form-control" id="Sms_number" name="Sms_number" value="{{ isset($dailer_agent_user['mobile_number']) ? $dailer_agent_user['mobile_number'] : '' }}" placeholder="Mobile number"><br>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="Status" class="form-label">Status</label>
                                                    <select name="status" class="form-control data select5" id="status">
                                                        <option value="Y" {{ (isset($dailer_agent_user['active']) && $dailer_agent_user['active'] == 'Y') ? 'selected' : '' }}>Active</option>
                                                        <option value="N" {{ (isset($dailer_agent_user['active']) && $dailer_agent_user['active'] == 'N') ? 'selected' : '' }}>Not Active</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6" id="body_attribute">
                                            <div class="customfield-wrap" id="row_1">
                                                <div class="form-group fromgroup">
                                                    <label for="active" class="form-label">Custom Attribute 1</label>
                                                    <input type="text" class="form-control custom-attribute" name="custom_attribute[]" value="" placeholder="Custom attribute 1">
                                                </div>
                                                <div class="additionalfield-wrap">
                                                    <button type="button" onclick="add_row();" class="btn btn-success btn-sm mt-2"> + </button>
                                                    <button type="button" onclick="remove_row();" class="btn btn-danger btn-sm mt-2"> - </button>
                                                </div>
                                            </div>
                                            </div>
                                        </div>
                                        

                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                <a class="btn btn-default btn-md btn-block" href="#">Cancel</a>
                                            </div>
                                            <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                <button class="btn btn-success btn-md btn-block" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-footer-->
                                </div>
                                <!-- /.card -->
                            </form>
                        </div>

                        <div class="tab-pane fade" id="options" role="tabpanel" aria-labelledby="options-tab">
                            <form method="POST" action="{{ route('services.update-agent.options', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
                                <input type="hidden" class="form-control" id="User" name="User" value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}" placeholder="User">
                                @csrf
                                <div class="card">
                                    <div class="card-header">
                                        {{-- <h3 class="card-title">Edit Agent</h3> --}}
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="name" class="form-label">SMS Number</label>
                                                    <input type="text" class="form-control" id="Sms_number" name="Sms_number" value="{{ isset($dailer_agent_user['mobile_number']) ? $dailer_agent_user['mobile_number'] : '' }}" placeholder="Mobile number"><br>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_ingroups']) && $dailer_agent_user['agent_choose_ingroups']=='1' ) ? 'checked' : '' }} id="toggle1" name="Inbound_Upon_Login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle1"> <b>Select Inbound Upon Login</b> </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_blended']) && $dailer_agent_user['agent_choose_blended']=='1' ) ? 'checked' : '' }} id="toggle2" name="Auto_Outbound_Upon_Login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                <label class="form-check-label" for="toggle2"> <b>Select Auto-Outbound Upon Login</b> </label>
                                            </div>
                                        </div>



                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['closer_default_blended']) && $dailer_agent_user['closer_default_blended']=='1' ) ? 'checked' : '' }} id="toggle3" name="Allow_Outbound" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle3"><b>Allow Outbound</b></label>
                                                </div>
                                            </div>
                                            <!-- add here -->
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentcall_manual']) && $dailer_agent_user['agentcall_manual']=='1' ) ? 'checked' : '' }} id="toggle6" name="Allow_Manual_Calls" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                <label class="form-check-label" for="toggle6"><b>Allow Manual Calls</b></label>
                                            </div>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['scheduled_callbacks']) && $dailer_agent_user['scheduled_callbacks']=='1' ) ? 'checked' : '' }} id="toggle4" name="scheduled_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle4"><b>Allow Schedule Callbacks</b></label>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_call_log_view_override']) && $dailer_agent_user['agent_call_log_view_override']=='Y' ) ? 'checked' : '' }} id="toggle6" name="Call_Log_View" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                <label class="form-check-label" for="toggle7"><b> Allow Call Log View</b></label>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentonly_callbacks']) && $dailer_agent_user['agentonly_callbacks']=='1' ) ? 'checked' : '' }} id="toggle5" name="Personal_Callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle5"><b>Allow Personal Callbacks</b></label>
                                                </div>
                                               
                                            </div>
                                        </div>



                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                <a class="btn btn-default btn-md btn-block" href="#">Cancel</a>
                                            </div>
                                            <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                <button class="btn btn-success btn-md btn-block" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-footer-->
                                </div>
                                <!-- /.card -->
                            </form>
                        </div>

                        <div class="tab-pane fade" id="Organization-user" role="tabpanel" aria-labelledby="Organization-user-tab">

                            {{-- Inbound --}}
                            <div class="card inbounds mt-3">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Inbound</b></h3>

                                </div>
                                <div class="card-body">

                                    <table id="Enbound" class="table table-striped table-hover vonexta-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Level</th>
                                                <th>Invited </th>
                                                <th>Calls Today </th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @if(isset($call_log_inbounds))
                                            @foreach($call_log_inbounds as $row=>$call_log_inbound)

                                            <tr>
                                                <td id='inbound_id_{{$row}}'>{{$call_log_inbound['group_id']}} <span class="adminlet3" onclick='open_inbound_model({{$row}});'><i class="fas fa-list"></i> </span> </td>
                                                <td>
                                                    <select id="group_grade_{{$row}}"  onchange='update_skill_inbound("{{$row}}");' style="border: none;">
                                                        <?php for ($i = 1; $i <= 9; $i++) { ?>
                                                            <option {{ $call_log_inbound['group_grade'] == $i  ? 'selected' : '' }} value="{{$i}}"> {{$i}} </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="checkbox"  {{$call_log_inbound['selected']=='1'  ? 'checked' : '' }} id='invited_{{$row}}'  class="form-check-input inbound-checkbox" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                </td>
                                                <td>{{$call_log_inbound['calls_today']}}</td>
                                            </tr>
                                            @endforeach

                                            @endif

                                        </tbody>

                                    </table>
                                </div>

                            </div>

                            
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 mt-2">
                                        <label> Inbound Calls Limit: </label>
                                        <input id="inbound_calls_limit"    type="text" oninput="update_inblound_call_limit(this.id);" name="inbound_calls_limit"  value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}" >
                                    </div>
                                </div>
                            </div>
                           

                            <!-- outbound -->
                            <div class="card compaigns">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Outbound</b></h3>
                                </div>
                                <div class="card-body">

                                    <table id="Campaigns" class="table table-striped table-hover vonexta-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Level</th>
                                                <th>Calls Today</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(isset($call_log_outbounds))
                                                @foreach($call_log_outbounds as $row2=>$call_log_outbound)
                                                <tr>
                                                    <td id='campaign_id_{{$row2}}'>{{$call_log_outbound['campaign_id']}} <span class="adminlet3" onclick='open_outbound_model({{$row2}});'><i class="fas fa-list"></i> </span> </td>


                                                    <td>
                                                        <select id="campaign_grade_{{$row2}}" onchange='update_skill_outbound("{{$row2}}");' style="border: none;">
                                                            <?php for ($i = 1; $i <= 9; $i++) { ?>
                                                                <option {{ $call_log_outbound['campaign_grade'] == $i  ? 'selected' : '' }} value="{{$i}}"> {{$i}} </option>
                                                            <?php } ?>
                                                        </select>

                                                    </td>

                                                    <td>{{$call_log_outbound['calls_today']}}</td>
                                                </tr>
                                                @endforeach

                                            @endif


                                        </tbody>

                                    </table>
                                </div>

                            </div>


                        </div>

                        <div class="tab-pane fade  @if(session()->has('tab') && session('tab') == 'call-Logs') show active @endif " id="call-log" role="tabpanel" aria-labelledby="call-logs-tab">

                            <div class="row justify-content-end">
                                <div class="col-md-6 mb-3">
                                    <div class="tagcalender-wrapper d-flex justify-content-end">
                                        <div class="tagswrap">
                                            <select class="form-control select2" id="table_log" multiple style="width: 100%" onchange="show_call_log_tb();">
                                                <option selected value="Inbound">Inbound</option>
                                                <option selected value="Outbound">Outbound</option>
                                                <option value="Manual">Manual Calls</option>
                                                <option value="Transfer">Transfers</option>
                                            </select>
                                        </div>
                                        <div class="calenderwrap">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        <i class="far fa-calendar-alt"></i>
                                                    </span>
                                                </div>
                                                <input type="text" class="form-control float-right" id="reservation1">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="call-log-tb"></div>

                        </div>

                        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">


                            <div class="row d-flex justify-content-end mb-3">

                                <div class="col-md-4 mt-3">
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <i class="far fa-calendar-alt"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control float-right" id="reservation2">
                                    </div>
                                </div>
                            </div>

                            <div class="card compaigns">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Agent Activity</b></h3>

                                </div>

                                <div class="card-body">

                                    <table class="table table-striped table-hover vonexta-table" id='active-log'>
                                        <thead>
                                            <tr>
                                                <th>Time</th>
                                                <th>Activity</th>
                                                <th>Session Length</th>
                                                <th>Campaign</th>
                                            </tr>
                                        </thead>
                                        <tbody id="activity-tbody">
                                            <!-- <tr>
                                                <td>0000</td>
                                                <td>0000</td>
                                                <td>0000</td>
                                                <td>0000</td>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
    </div>
    <!-- /.card-body -->
</div>
</section>
<!-- /.content -->


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


<!-- Select2 -->
<script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

<!-- Bootstrap Switch -->
<script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
<!-- jquery-validation -->

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
<script src="{{asset('plugins/daterangepicker/daterangepicker.js')}}"></script>

<!-- Ion Slider -->
<script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!-- Bootstrap slider -->
<script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js') }}"></script>


<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('views/services/dialler/users/edit.js') }}"></script>




<script>
$(function () {
    $('.select2').select2();
});

$(function(){
    $('.select5').select2({
        minimumResultsForSearch: Infinity
    });
});
  </script>



<script>
    $(function () {
      // Initialize the ionRangeSlider
      $('#inbound_calls_limit').ionRangeSlider({
        min     : 1,
        max     : 1000,
        // from    : 0,
        type    : 'single',
        step    : 1,
        prettify: false,
        hasGrid : true
      });


         $('#inbound_calls_limit').data("ionRangeSlider").update({
        from: $('#inbound_calls_limit').val()
      });

    });
 




</script>




@endpush
<script>
    function update_input_value(id) {
        var range = $("#" + id).val();
        var input_filed = id.replace("-range", "");
        $("#" + input_filed).val(range);
    }

</script>
    