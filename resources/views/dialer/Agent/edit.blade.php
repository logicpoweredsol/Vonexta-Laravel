
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
        @elseif (session()->has('failed'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                failed("{{ session('failed') }}");
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
                            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower($service), 'organization_services_id' => $organization_services_id]) }}">Agents</a></li>
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
                            <a class="nav-link active" id="Organization-home-tab" data-toggle="pill" href="#Organization-home" role="tab" aria-controls="Organization-home" aria-selected="true">Details</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="options-tab" data-toggle="pill" href="#options" role="tab" aria-controls="campaigns-disposition" aria-selected="false">Options</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="Organization-user-tab" data-toggle="pill" href="#Organization-user" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Skills</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="call-logs-tab" data-toggle="pill" href="#call-log" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Call Logs</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="campaigns-tabs Content">

                        <div class="tab-pane fade show active " id="Organization-home" role="tabpanel" aria-labelledby="Organization-home-tab">
                            <form method="POST" action="{{ route('services.update-agent.details', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
                                <input type="hidden" class="form-control" id="User" name="User" value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}" placeholder="User">

                               
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
                                                    @php
                                                        $email_address = get_email($dailer_agent_user['user']);
                                                    @endphp
                                                    <input type="email" class="form-control" id="email" name="email" readonly  value="{{$email_address}}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="address" class="form-label">Extension</label>
                                                    <input type="text" class="form-control" id="User" name="User" disabled value="{{ isset($dailer_agent_user['user']) ? $dailer_agent_user['user'] : '' }}" placeholder="User">
                                                </div>
                                    
                                            </div>
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="Voice Mail" class="form-label">Voice Mail</label>
                                                    <input type="text" class="form-control" id="Voice_Mail" name="Voice_Mail" value="{{ isset($dailer_agent_user['voicemail_id']) ? $dailer_agent_user['voicemail_id'] : '' }}" placeholder="Voice Mail..">
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="Group" class="form-label">Group</label>
                                                    {{-- <input type="text" class="form-control" id="group" name="group" value="{{ isset($dailer_agent_user['user_group']) ? $dailer_agent_user['user_group'] : '' }}" placeholder="Group .."> --}}
                                                    
                                                    @php
                                                        $responses = get_group_user($organization_services_id);
                                                        // dd($responses);
                                                    @endphp
                                                    <select name="group" class="form-control " id="group">
                                                        @foreach ($responses as $response)
                                                        <option value="{{$response}}" {{ isset($dailer_agent_user['user_group']) && $dailer_agent_user['user_group'] == $response ? 'selected' : '' }}>{{ $response }}</option>
                                                        @endforeach
                                                        
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="active" class="form-label ">Active</label>
                                                    <select name="active" class="form-control data" id="active" active="">
                                                        <option value="Y" {{ (isset($dailer_agent_user['active']) && $dailer_agent_user['active'] == 'Y') ? 'selected' : '' }}>Yes</option>
                                                        <option value="N" {{ (isset($dailer_agent_user['active']) && $dailer_agent_user['active'] == 'N') ? 'selected' : '' }}>No</option>
                                                    </select>
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
                                                    <label for="name" class="form-label">SMS number</label>
                                                    <input type="text" class="form-control" id="Sms_number" name="Sms_number" value="{{ isset($dailer_agent_user['mobile_number']) ? $dailer_agent_user['mobile_number'] : '' }}" placeholder="Mobile number"><br>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_ingroups']) && $dailer_agent_user['agent_choose_ingroups']=='1' ) ? 'checked' : '' }} id="toggle1" name="agent_choose_ingroups" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle1"> <b>Select Inbound Upon Login</b> </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_choose_blended']) && $dailer_agent_user['agent_choose_blended']=='1' ) ? 'checked' : '' }} id="toggle2" name="agent_choose_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                <label class="form-check-label" for="toggle2"> <b>Select Auto-Outbound Upon Login</b> </label>
                                            </div>
                                        </div>



                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['closer_default_blended']) && $dailer_agent_user['closer_default_blended']=='1' ) ? 'checked' : '' }} id="toggle3" name="closer_default_blended" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                    <label class="form-check-label" for="toggle3"><b>Allow Outbound</b></label>
                                                </div>
                                            </div>
                                            <!-- add here -->
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentcall_manual']) && $dailer_agent_user['agentcall_manual']=='1' ) ? 'checked' : '' }} id="toggle6" name="agentcall_manual" data-bootstrap-switch data-off-color="danger" data-on-color="success">
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
                                                <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agent_call_log_view_override']) && $dailer_agent_user['agent_call_log_view_override']=='Y' ) ? 'checked' : '' }} id="toggle6" name="agent_call_log_view_override" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                                <label class="form-check-label" for="toggle7"><b> Allow Call Log View</b></label>
                                            </div>

                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" {{(isset($dailer_agent_user['agentonly_callbacks']) && $dailer_agent_user['agentonly_callbacks']=='1' ) ? 'checked' : '' }} id="toggle5" name="agentonly_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
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

                                        {{-- <div class="row mb-3">
                                            <div class="col-md-4 mt-2">
                                                <label> Inbound Calls Limit: </label>
                                                <input type="range" id="fixtures-events-range" name="max_inbound_calls" id="max_inbound_calls" min="1" max="1000" value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}" oninput="update_input_value(this.id)">
                                            </div>
                                            <div class="col-md-2">
                                                <div class="slidercontainer">
                                                    <input class="form-control" type="number" readonly id="fixtures-events" value="{{ isset($dailer_agent_user['max_inbound_calls']) ? $dailer_agent_user['max_inbound_calls'] : '' }}" >
                                                </div>         
                                            </div>
                                        </div> --}}


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
                            <div class="d-flex justify-content-end">
                                
                                {{-- <div class="form-group">
                                    <select class="select2" multiple="multiple" data-placeholder=""  id="skills_log"  onchange="Change_tab(this.id);">
                                        <option selected value="compaigns">compaigns</option>
                                        <option value="inbounds">Inbound</option>
                                    </select>
                                </div> --}}

                                {{-- <div class="col-md-3 mb-3">

            
                                    
                                    <select class="form-control" id="skills_log" style="width:200px"  onchange="Change_tab(this.id);">
                                        <option selected value="compaigns">compaigns</option>
                                        <option value="inbounds">Inbound</option>
                                    </select> 
                                    
                                </div> --}}
                            </div>
                            {{-- Campaigns --}}
                            <div class="card compaigns">
                                <div class="card-header">
                                    <h3 class="card-title"><b>Campaigns</b></h3>
                                </div>
                                <div class="card-body">

                                    <table id="Campaigns" class="table table-striped table-hover vonexta-table">
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
                                </div>

                            </div>

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
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>0000</td>
                                                <td>0000</td>
                                                <td>
                                                    <a href="" class="btn btn-sm btn-primary" class="btnEdit" data-id=""><i class="fas fa-pen"></i><a>
                                                            <a href="#" class="btn btn-sm btn-danger" class="btnDelete" data-id=""><i class="fas fa-trash"></i><a>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>

                            </div>
                        </div>

                        <div class="tab-pane fade" id="call-log" role="tabpanel" aria-labelledby="call-logs-tab">

                            <div class="d-flex justify-content-end">
                                <div class="col-md-4 mb-3">
                                    <select class="form-control select2" id="table_log" multiple  style="width: 100%" onchange="show_call_log_tb();">
                                        <option value="Inbound">Inbound</option>
                                        <option value="Outbound">Outbound</option>
                                        <option value="Manual">manual calls</option>
                                        <option value="Transfer">Transfers</option>
                                    </select>
                                </div>
                            </div>

                            <div class="call-log-tb"></div>


                            


                        </div>
                    </div>
                </div>
            <!-- /.card-body -->
            </div>
        </section>
    <!-- /.content -->
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

<!-- Ion Slider -->
<script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<!-- Bootstrap slider -->
<script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js') }}"></script>


<!-- SweetAlert2 -->
<script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
{{-- <script>
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    console.log(123)
    $(document).ready(function() {

    });
    $("#toggle4").change(function() {
         $('#toggle4').val();
         alert('changed')
    });

    $('#toggle4').on('ifChanged', function(event){
        alert('changed')
    // if(this.checked) // if changed state is "CHECKED"
    // {
    //     var correspondence_address = $('#correspondence_address').val();
    //     $('#permanent_address').val(correspondence_address);
    // }
    // else
    // {
    //   $('#permanent_address').val('');
    // }
});
</script> --}}

<script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
<script src="{{ asset('views/services/dialler/users/edit.js') }}"></script>



<script>
   $(function () {
    $('.select2').select2();
   });
  </script>



<script>
    $(function () {
      // Initialize the ionRangeSlider
      $('#max_inbound_calls').ionRangeSlider({
        min     : 1,
        max     : 1000,
        // from    : 0,
        type    : 'single',
        step    : 1,
        prettify: false,
        hasGrid : true
      });


         $('#max_inbound_calls').data("ionRangeSlider").update({
        from: $('#max_inbound_calls').val()
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
    