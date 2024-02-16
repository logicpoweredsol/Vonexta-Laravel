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
                <div class="card-body">
                    <!--<div class="row mb-3">
                        <div class="col-sm-12 col-md-6 col-lg-6" style=" margin-top: 2rem; ">

                         <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="allowed_compaigns" name="all_allowed_compaigns" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="compaigns"> <b>Allow all compaigns</b> </label>
                            </div>


                           
                        </div>
                    </div> -->

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label for="Allowed Campaigns">Allowed Profiles :</label>
                            <select class="form-control select3" id="all_comp_select" multiple name="allowed_profiles[]" {{ ($agent_edit_data['allowed_campaigns']=='-ALL-CAMPAIGNS-') ? 'disabled' : ''}} required>
                                <!-- <option value="" selected disabled>Allowed Campaigns</option> -->
                                @foreach($all_compaign['campaign_id'] as $p => $compan)
                                @php
                                $allowed_campaigns = isset($agent_edit_data['allowed_campaigns']) ? explode("-", $agent_edit_data['allowed_campaigns']) : [];

                                $isSelected = in_array($all_compaign['campaign_id'][$p], $allowed_campaigns) ? 'selected' : '';
                                @endphp
                                <option {{ $isSelected }} value="{{ $all_compaign['campaign_id'][$p] }}">
                                    {{ $all_compaign['campaign_name'][$p] }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check mt-4">
                                <input type="checkbox"  class="form-check-input" {{(isset($agent_edit_data['allowed_campaigns']) && $agent_edit_data['allowed_campaigns']=='-ALL-CAMPAIGNS-' ) ? 'checked' : '' }} id="allowe_compaigns" name="all_allowed_profiles" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="Profiles"> <b>Allow All Profiles</b> </label>
                            </div>

                    </div>

                    <div class="row mb-3">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                            <label for="allowed-Transfer-Caller">Allowed Transfer Caller IDs:</label>

                            @php
                                $transfer_caller_ids = isset($agent_edit_data['transfer_caller_id']) ? explode("-", $agent_edit_data['transfer_caller_id']) : [];
                            @endphp

                            <select class="form-control select3" multiple name="transfer_caller_id[]" id="allowed-Transfer-Caller" required>
                                <option {{ in_array('System', $transfer_caller_ids) ? 'selected' : '' }} value="System">System</option>
                                <option {{ in_array('Agent', $transfer_caller_ids) ? 'selected' : '' }} value="Agent">Agent</option>
                                <option {{ in_array('Customer', $transfer_caller_ids) ? 'selected' : '' }} value="Customer">Customer</option>
                            </select>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <label for="allowed-Transfer Caller">Allowed Manual Dial Caller IDs:</label>
                            @php
                            $manual_dial_caller_id = isset($agent_edit_data['manual_dial_caller_id']) ? explode("-", $agent_edit_data['manual_dial_caller_id']) : [];
                            @endphp

                            <select class="form-control select3 " multiple name="manual_dial_caller_id[]" id="" required>
                                <!-- <option value="" selected disabled> Allowed Manual Dial Caller IDs</option> -->
                                <option {{ in_array('System', $manual_dial_caller_id) ? 'selected' : '' }} value="System">System</option>
                                <option {{ in_array('Agent', $manual_dial_caller_id) ? 'selected' : '' }} value="Agent">Agent</option>
                            </select>
                        </div>
                    </div>


                    <hr>



                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['select_inbound_upon_login']) && $agent_edit_data['select_inbound_upon_login']=='Y' ) ? 'checked' : '' }} id="toggle1" name="select_inbound_upon_login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle1"> <b>Select Inbound Queues Upon Login</b> </label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['select_auto_outbound_upon_login']) && $agent_edit_data['select_auto_outbound_upon_login']=='Y' ) ? 'checked' : '' }} id="toggle2" name="select_auto_outbound_upon_login" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle2"> <b>Select Auto-Outbound Upon Login</b> </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_auto_outbound']) && $agent_edit_data['allow_auto_outbound']=='Y' ) ? 'checked' : '' }} id="toggle3" name="allow_auto_outbound" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle3"><b>Allow Auto-Outbound</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_manual_calls']) && $agent_edit_data['allow_manual_calls']=='Y' ) ? 'checked' : '' }} id="toggle6" name="allow_manual_calls" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle6"><b>Allow Manual Calls</b></label>

                            </div>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_schedule_callbacks']) && $agent_edit_data['allow_schedule_callbacks']=='Y' ) ? 'checked' : '' }} id="toggle5" name="allow_schedule_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle7"><b> Scheduled Callbacks</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">

                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_personal_callbacks']) && $agent_edit_data['allow_personal_callbacks']=='Y' ) ? 'checked' : '' }} id="toggle10" name="allow_personal_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for="toggle5"><b>Allow Personal Scheduled Callbacks</b></label>
                            </div>
                        </div>


                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_alter_contact']) && $agent_edit_data['allow_alter_contact']=='Y' ) ? 'checked' : '' }} id="toggle7" name="allow_edit_contact_info" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Edit Contact Info</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_alter_phone_number']) && $agent_edit_data['allow_alter_phone_number']=='Y' ) ? 'checked' : '' }} id="toggle8" name="allow_edit_contact_phone_number" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b>Edit Contact Phone Number</b></label>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['display_dialable_contacts']) && $agent_edit_data['display_dialable_contacts']=='Y' ) ? 'checked' : '' }} id="toggle9" name="display_dialable_contacts" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Dialable Contacts Count</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">

                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_waiting_calls_view']) && $agent_edit_data['allow_waiting_calls_view']=='Y' ) ? 'checked' : '' }} id="toggle10" name="allow_waiting_calls_view" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b>Show Waiting Inbound Calls</b></label>

                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['agent_call_log_view']) && $agent_edit_data['agent_call_log_view']=='Y' ) ? 'checked' : '' }} id="toggle11" name="show_call_log" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Show Call Log</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['agent_xfer_consultative']) && $agent_edit_data['agent_xfer_consultative']=='Y' ) ? 'checked' : '' }} id="toggle12" name="allow_transfer_to_agent" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b>Allow Transfer To Agent</b></label>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['agent_xfer_blind_transfer']) && $agent_edit_data['agent_xfer_blind_transfer']=='Y' ) ? 'checked' : '' }} id="toggle13" name="agent_xfer_blind_transfer" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Allow Blind Transfer</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['agent_xfer_dial_with_customer']) && $agent_edit_data['agent_xfer_dial_with_customer']=='Y' ) ? 'checked' : '' }} id="toggle14" name="allow_conference_call" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b>Allow Conference Call</b></label>
                            </div>
                        </div>

                    </div>

                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_direct_extension_inbounds']) && $agent_edit_data['allow_direct_extension_inbounds']=='Y' ) ? 'checked' : '' }} id="toggle13" name="allow_direct_extension_inbounds" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Allow Receive Direct Calls</b></label>
                            </div>
                        </div>

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_transfers_to_number']) && $agent_edit_data['allow_transfers_to_number']=='Y' ) ? 'checked' : '' }} id="toggle14" name="allow_transfers_to_number" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b>Allow Transfer To Phone Number</b></label>
                            </div>
                        </div>

                    </div>


                    <div class="row mb-3">

                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" {{(isset($agent_edit_data['allow_transfers_to_queue']) && $agent_edit_data['allow_transfers_to_queue']=='Y' ) ? 'checked' : '' }} id="toggle13" name="allow_transfers_to_queue" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                <label class="form-check-label" for=""><b> Allow Transfer To Queue</b></label>
                            </div>
                        </div>

                    </div>




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
                <!-- /.card -->
        </form>
    </section>
    <!-- /.content -->
</div>




<!-- /.content-wrapper -->
@endSection


@push('scripts')

<!-- <script>
    $(function() {
        $('.select3').select2({
            placeholder: "Select the option"
        });
    });
</script> -->


@endpush