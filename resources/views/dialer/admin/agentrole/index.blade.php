@extends('layouts.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

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


    {{-- <div class="content-wrapper"> --}}

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
            <h1>Agent Roles</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">

                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                   @php $nick_name  =service_name($organization_servicesID); @endphp
                    <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_servicesID]) }}">{{$nick_name}} </a></li>
                    <li class="breadcrumb-item"><a href="#">Admin</a></li>
                    <li class="breadcrumb-item"><a href="#">Agent Role</a></li>

              <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active"><a href="#">Agent Roles</a></li> -->
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
          <h3 class="card-title">Agent Roles</h3>

          <div class="card-tools">
            <a href="javascript:;" class="btn btn-secondary btn-sm" onclick="showmodal();" >Add Agent Role</a>
          </div>
        </div>
        <div class="card-body" style="position: relative;">
          <table id="tbl" class="table table-striped table-hover vonexta-table">
            <thead>
              <tr>
                <th>Role</th>
                <th>Allowed Profiles</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>

            
              @foreach($userGroups['user_group'] as $p=> $userGroup)
              <tr>
                <td>{{$userGroups['user_group'][$p]}}</td>

                 @php $compaings = get_agent_role_compaign($organization_servicesID , $userGroups['user_group'][$p] ); @endphp
                 <td>
                    @if($compaings == "" || $compaings == null)
                        No campaign selected
                    @else
                        {{ $compaings }}
                    @endif
                  </td>

                <td>
                <div class="btn-group">
                    <a class="btn btn-secondary btn-sm" href="{{ route('services.agent-role-edit', ['service' => strtolower($service), 'organization_services_id' => $organization_servicesID ,'user_group'=> $userGroups['user_group'][$p] ] ) }}">Edit</a>
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

  <!-- modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Create agent Role</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <form method="POST" action="{{ route('services.add-agent-role', ['service' => strtolower('Dailer')]) }}" id="form_add_agent_role" class="form-horizontal">

          @csrf
          <input type="hidden" class="form-control" id="organization_service_id" name="organization_service_id" value="{{$organization_servicesID}}">

          <div class="modal-body">

            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-group">
                  <label for="name" class="form-label">Agent Role<span class="text-danger">*</span></label>
                  <input type="text" class="form-control " id="user_group" name="user_group"   placeholder="Role name">
                  <span class="text-danger" id="user_group_error"></span>
                </div>

              </div>
            
            </div>

            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-lg-6">
                <label for="Allowed Campaigns">Allowed Campaigns <span class="text-danger">*</span></label>
                <select class="form-control select3" multiple name="allowed_campaigns[]" id="allowed_campaigns">
                    @foreach($all_compaign['campaign_id'] as $p=>$compan)
                    <option value="{{$all_compaign['campaign_id'][$p]}}">{{$all_compaign['campaign_name'][$p]}}</option>
                    @endforeach
                </select>
              </div>

              <div class="col-sm-12 col-md-6 col-lg-6" style="margin-top: 2rem;">
                  <div class="form-check">
                      <input type="checkbox" class="form-check-input"   id="allowed_compaigns" name="all_allowed_compaigns" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                      <label class="form-check-label" for="compaigns"> <b>Allow all compaigns</b> </label>
                  </div>
              </div>
            </div>

            <div class="row mb-3">
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
            </div>

            <div class="row mb-3">
             

            </div>

             
            
            <hr>
      

            <div class="row mb-3">
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
            </div>

            <div class="row mb-3">
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
            </div>

            <div class="row mb-3">
              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle5" name="allow_schedule_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle7"><b> Scheduled Callbacks</b></label>
                </div>
              </div>

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle6" name="allow_personal_callbacks" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle5"><b>Allow Personal Scheduled Callbacks</b></label>
                </div>
              </div>


            </div>

            <div class="row mb-3">

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle7" name="allow_alter_contact" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle7"><b> Edit Contact Info</b></label>
                </div>
              </div>

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle8" name="allow_alter_phone_number" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle5"><b>Edit Contact Phone Number</b></label>
                </div>
              </div>

            </div>

            <div class="row mb-3">

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

            </div>

            <div class="row mb-3">

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle11" name="agent_call_log_view" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle7"><b> Show Call Log</b></label>
                </div>
              </div>

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle12" name="agent_xfer_consultative" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle5"><b>Allow Internal Transfers</b></label>
                </div>
              </div>

            </div>

            <div class="row mb-3">

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle13" name="agent_xfer_blind_transfer" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle7"><b> Allow Blind Transfers</b></label>
                </div>
              </div>

              <div class="col-sm-12 col-md-6 col-lg-6">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="toggle14" name="agent_xfer_dial_with_customer" data-bootstrap-switch data-off-color="danger" data-on-color="success">
                  <label class="form-check-label" for="toggle5"><b>Allow Conference Call</b></label>
                </div>
              </div>

            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="button"  onclick="add_agent_role()" class="btn btn-primary">Add Role</button>
          </div>
        </form>
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
  <script src="{{ asset('views/agentrole/index.js') }}"></script>
  <script src="{{ asset('views/datatable/datatable.js') }}"></script>

  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


<script>
   $(function() {
    $('.select3').select2({
        placeholder: "Select the option",
        width: "100%"
    });
});

</script>


  @endpush