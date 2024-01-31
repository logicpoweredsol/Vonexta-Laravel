@extends('layouts.app')

@section('content')

{{-- <style>
  .card {
    box-shadow: 15px 0px 35px rgba(0, 0, 0, 0.2); /* Adjusted values for a more visible shadow */
}
</style> --}}

<!-- Content Wrapper. Contains page content -->

@if (auth()->user()->hasRole('user'))
<div class="content-wrapper" style="margin-left: 0rem">
    @else
    <div class="content-wrapper">
        @endif


        
        <form method="POST" action="{{ route('services.update-agent.options', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
            <input type="hidden" class="form-control" id="User" name="User" value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}" placeholder="User">
            @csrf
            <div class="card">
                <div class="card-header">
                    {{-- <h3 class="card-title">Edit Agent</h3> --}}
                </div>
                <div class="card-body">

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

                    {{-- //limit --}}

                    <div class="row mb-3">
                        <div class="col-md-4 mt-2">
                            <label> Inbound Calls Limit: </label>
                            <input id="max_inbound_calls" type="text" name="max_inbound_calls" value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}">
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




        @endSection