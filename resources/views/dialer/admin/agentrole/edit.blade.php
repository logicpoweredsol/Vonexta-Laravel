@extends('layouts.app')
@section('content')


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>{{$agent_edit_data['user_group']}}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                     @php $nick_name  =service_name($organization_service_id); @endphp
                    <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_service_id]) }}">{{$nick_name}} </a></li>
                    <li class="breadcrumb-item"><a href="javascript:;">Admin</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('services.agent-role', ['service' => strtolower($service), 'organization_services_id' => $organization_service_id]) }}">Agent Role</a></li>
                    <li class="breadcrumb-item active">Edit roles</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
   

    <!-- Main content -->
    <section class="content">
        <!-- form start -->
        <form method="POST" action="{{ route('services.agent-role-update', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
            <input type="hidden" class="form-control" id="user_group" name="user_group" value="{{ isset($agent_edit_data['user_group']) ? $agent_edit_data['user_group'] : '' }}">
            <input type="hidden" class="form-control" id="organization_service_id" name="organization_service_id" value="{{$organization_service_id}}">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Edit Agent Role</h3>
                </div>
                <div class="modal-body">

<div class="row mb-3">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-group">
      <label for="name" class="form-label">Agent Role<span class="text-danger">*</span></label>
      <input type="text" class="form-control " id="role" name="role" value="{{$agent_edit_data['group_name']}}" placeholder="Role name">
      <span class="text-danger" id="role_error"></span>
    </div>

  </div>

</div>

<div class="card card-primary card-outline">
  <a class="d-block w-100" data-toggle="collapse" href="#collapseOne">
    <div class="card-header">
      <h4 class="card-title w-100">
        Login
      </h4>
    </div>
  </a>
  <div class="form-check p-3">
    <div id="collapseOne" class="collapse show" data-parent="#collapseOne">
      <div class="row">
        <div class="col-sm-6">
          <div class="radiobtn-wrapper mb-2">
            <input type="checkbox" class="form-check-input" id="allowe_profiles" name="all_allowed_profiles" data-bootstrap-switch data-off-color="danger" data-on-color="success">
            <label class="form-check-label" for="profiles"> <b>Allow all profiles</b> </label>
          </div>
          <select class="form-control select3" multiple name="allowed_profiles[]" id="allowedd_profiles">
            
            <option value=""></option>
            
          </select>
        </div>
        <div class="col-sm-6">
          <b class="d-block mb-2">login permissions</b>
          <select class="form-control select3" multiple name="" id="">
            <option value="">Select inbound Queues</option>
            <option value="">Select Auto-outbound</option>
            <option value="">Allow Auto-outbound</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>



<!-- <div class="row mb-3">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <label for="Allowed profiles">Allowed profiles <span class="text-danger">*</span></label>
    
    
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6" style="margin-top: 2rem;">
      <div class="form-check">
          <input type="checkbox" class="form-check-input"   id="allowe_compaigns" name="all_allowed_compaigns" data-bootstrap-switch data-off-color="danger" data-on-color="success">
          <label class="form-check-label" for="profiles"> <b>Allow all profiles</b> </label>
      </div>
  </div>
</div> -->

<div class="card card-primary card-outline">
  <a class="d-block w-100" data-toggle="collapse" href="#collapsetwo">
    <div class="card-header">
      <h4 class="card-title w-100">
        Transfers
      </h4>
    </div>
  </a>
  <div class="form-check p-3">
    <div id="collapsetwo" class="collapse show" data-parent="#collapsetwo">
      <div class="row mb-4">
        <div class="col-sm-6">
          <b class="d-block mb-3">transfer permissions:</b>
          <select class="form-control select3" multiple name="" id="">
            <option value="">Allow Transfer To Queue</option>
            <!-- <div class="form-check">
                      <input type="checkbox" class="form-check-input" id="toggle13" name="allow_transfers_to_queue" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                      <label class="form-check-label" for="toggle7"><b> Allow Transfer To Queue</b></label>
                    </div> -->
            <option value="">Allow Transfer To Agent</option>
            <option value="">Allow external transfers</option>
            <option value=""> Allow Blind transfers</option>
            <option value="">Allow 3-way calls</option>
            <option value="">Allow Receive Direct Calls</option>
          </select>
        </div>
        <div class="col-sm-6">
          <label class="d-block mb-3" for="allowed-Transfer Caller">Allowed Transfer Caller IDs <span class="text-danger">*</span></label>
          <select class="form-control select3" multiple name="" id="">
            <option value="System"> System </option>
            <option value="Agent"> Agent </option>
            <option value="Customer"> Customer </option>
          </select>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6">
          <div class="radiobtn-wrapper mb-2">
            <input type="checkbox" class="form-check-input" id="toggle5" name="allow_schedule_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
            <label class="form-check-label" for="toggle7"><b> Allow All Agents</b></label>
          </div>
          <select class="form-control select3" multiple name="" id="">
            <option value="">Online Agents in Outbound Profile</option>
            <option value="">Admin</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="card card-primary card-outline">
  <a class="d-block w-100" data-toggle="collapse" href="#collapsethree">
    <div class="card-header">
      <h4 class="card-title w-100">
        Interface
      </h4>
    </div>
  </a>
  <div class="form-check p-3">
    <div id="collapsethree" class="collapse show" data-parent="#collapsethree">
      <div class="row">
        <div class="col-sm-6">
          <b class="d-block mb-3">Interface permissions:</b>
          <select class="form-control select3" multiple name="" id="">
            <option value="">Scheduled Callbacks</option>
            <option value="">Personal callbacks</option>
            <option value="">Allow Manual Calls</option>
            <option value="">Edit contact-info</option>
            <option value="">Allow 3-way calls</option>
            <option value="">Edit contact-phone-number</option>
            <option value="">Show call log</option>
            <option value="">show disable contacts count</option>
            <option value="">Show waiting inbound calls</option>
          </select>
        </div>
        <div class="col-sm-6">
          <b class="d-block mb-3">Allowed Manual Dial caller IDs:</b>
          <select class="form-control select3" multiple name="" id="">
            <option value="System">System</option>
            <option value="Agent">Agent</option>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>




<!-- <div class="row mb-3">
  <div class="col-sm-12 col-md-6 col-lg-6">
      <label for="allowed-Transfer Caller">Allowed Transfer Caller IDs <span class="text-danger">*</span></label>
      <select class="form-control select3" multiple name="transfer_caller_id[]" id="transfer_caller_id">
        <option value="System"> System </option>
        <option value="Agent"> Agent </option>
        <option value="Customer"> Customer </option>
      </select>
    </div>
    <div class="col-sm-12 col-md-6 col-lg-6">
      <label for="allowed-Transfer Caller">Allowed Manual Dial Caller IDs <span class="text-danger">*</span></label>
      <select class="form-control select3" multiple name="manual_dial_caller_id[]" id="manual_dial_caller_id">
        <option value="System">System</option>
        <option value="Agent">Agent</option>
      </select>
  </div>
</div> -->

<!-- <div class="row mb-3">
 

</div>

  -->

<hr>


<!-- <div class="row mb-3">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle1" name="select_inbound_upon_login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle1"> <b>Select Inbound Upon Login</b> </label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle2" name="select_auto_outbound_upon_login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle2"> <b>Select Auto-Outbound Upon Login</b> </label>
    </div>
  </div>
</div> -->

<!-- <div class="row mb-3">
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle3" name="allow_auto_outbound" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle3"><b>Allow Outbound</b></label>
    </div>
  </div>
  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle4" name="allow_manual_calls" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle4"><b>Allow Manual Calls</b></label>
    </div>
  </div>
</div> -->

<div class="row mb-3">
  <!-- <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle5" name="allow_schedule_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Scheduled Callbacks</b></label>
    </div>
  </div> -->

  <!-- <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle6" name="allow_personal_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Allow Personal Scheduled Callbacks</b></label>
    </div>
  </div> -->


</div>

<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle7" name="allow_edit_contact_info" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Edit Contact Info</b></label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle8" name="allow_edit_contact_phone_number" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Edit Contact Phone Number</b></label>
    </div>
  </div>

</div> -->

<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
    <input type="checkbox" class="form-check-input" id="toggle9" name="display_dialable_contacts" data-bootstrap-switch data-off-color="danger" data-on-color="success">
    <label class="form-check-label" for="toggle7"><b> Dialable Contacts Count</b></label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle10" name="allow_waiting_calls_view" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Show Waiting Inbound Calls</b></label>
    </div>
  </div>

</div> -->

<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle11" name="show_call_log" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Show Call Log</b></label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle12" name="allow_transfer_to_agent" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Allow Transfer To Agent</b></label>
    </div>
  </div>

</div> -->

<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle13" name="agent_xfer_blind_transfer" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Allow Blind Transfer</b></label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle14" name="allow_conference_call" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Allow Conference Call</b></label>
    </div>
  </div>

</div> -->

<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle13" name="allow_direct_extension_inbounds" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Allow Receive Direct Calls</b></label>
    </div>
  </div>

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle14" name="allow_transfers_to_number" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle5"><b>Allow Transfer To Phone Number</b></label>
    </div>
  </div>

</div> -->



<!-- <div class="row mb-3">

  <div class="col-sm-12 col-md-6 col-lg-6">
    <div class="form-check">
      <input type="checkbox" class="form-check-input" id="toggle13" name="allow_transfers_to_queue" data-bootstrap-switch data-off-color="danger" data-on-color="success">
      <label class="form-check-label" for="toggle7"><b> Allow Transfer To Queue</b></label>
    </div>
  </div>

</div> -->
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                <a class="btn btn-default btn-md btn-block" href="{{ route('services.agent-role', ['service' => strtolower($service), 'organization_services_id' => $organization_service_id]) }}">Cancel</a>
                            </div>
                            <div class="col-sm-12 col-md-8 col-lg-8"></div>
                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                <button class="btn btn-success btn-md btn-block" type="submit">Save</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-footer-->
                </div>


</div>
                <!-- /.card -->
        </form>
    </section>
    <!-- /.content -->
</div>




<!-- /.content-wrapper -->
@endSection