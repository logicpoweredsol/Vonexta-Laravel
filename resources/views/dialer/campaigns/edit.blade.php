@extends('layouts.app')

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

       
        <section class="content-header">
            <div class="container-fluid">
              <div class="row ">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                  <ol class="breadcrumb float-sm-right">
        
                  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                      {{-- @php $nick_name  =service_name($organization_servicesID); @endphp --}}
                      <li class="breadcrumb-item"><a href="">Dialer</a></li>

                  <li class="breadcrumb-item active"><a href="javascript:;">Outbound Profiles</a></li>
        
                    <!-- <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"></li> -->
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
                            <a class="nav-link @if( !session()->has('tab') && !session('tab')) active @endif " id="Organization-home-tab" data-toggle="pill" href="#Organization-home" role="tab" aria-controls="Organization-home" aria-selected="true">Basic</a>
                        </li>

                        <!-- <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="options-tab" data-toggle="pill" href="#options" role="tab" aria-controls="campaigns-disposition" aria-selected="false">Options</a>
                        </li> -->

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="Organization-user-tab" data-toggle="pill" href="#Organization-user" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Statistics</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link " id="call-logs-tab" data-toggle="pill" href="#call-log" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Contact List</a>
                        </li>

                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="activity-tab" data-toggle="pill" href="#activity" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Advanced</a>
                        </li>
                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="Dynamic-tab" data-toggle="pill" href="#caller" role="tab" aria-controls="dynamic-leadRecycling" aria-selected="false">Dynamic Caller ID</a>
                        </li>
                        <li class="nav-item vonext-campaign-item">
                            <a class="nav-link" id="attributes-tab" data-toggle="pill" href="#attributes" role="tab" aria-controls="dynamic-leadRecycling" aria-selected="false">Custom attributes</a>
                        </li>
                    </ul>

                    <div class="tab-content" id="campaigns-tabs Content">

                    
                        <div class="tab-pane fade show active " id="Organization-home" role="tabpanel" aria-labelledby="Organization-home-tab">
                            <form method="POST" action="{{ route('services.campaigns.update', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">

                                <input type="hidden" name="organization_service_id" value="{{$organization_service_id}}" readonly>
                                <input type="hidden" name="profile_id" value="{{$edit_compaigns['data']['campaign_id']}}" readonly>
                                    @csrf
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Edit outbound Profile</h3>
                                            
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="name" class="form-label">Name</label>
                                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="profile_name" name="profile_name" value="{{$edit_compaigns['data']['campaign_name']}}">
                                                    </div>
                                                    <span>
                                                        @error('name')
                                                        <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </span>    
                                                </div>
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="type" class="form-label">Type</label>
                                                        <select name="type" class="form-control" id="type" onchange="speed_calculate(this.id);">
                                                            <option value="select" selected disabled >Select Type</option>
                                                            <option {{$edit_compaigns['data']['dial_method'] == 'RATIO' ? 'selected' : ''}} value="RATIO">Predictive</option>
                                                            <option {{$edit_compaigns['data']['dial_method'] == 'ADAPT_TAPERED' ? 'selected' : ''}} value="ADAPT_TAPERED">Smart Predictive</option>
                                                        
                                                            <option @if($edit_compaigns['data']['dial_method'] == 'INBOUND_MAN' && $velocity == "0") selected @endif value="INBOUND_MAN">Agent Dial Next</option>
                                                            <option @if($edit_compaigns['data']['dial_method'] == 'INBOUND_MAN' && $velocity > "0") selected @endif value="INBOUND_MAN">Auto Dial Next</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="Sequence" class="form-label">Sequence</label>
                                                        <select name="sequence" class="form-control">   
                                                            <option {{ $edit_compaigns['data']['lead_order'] === 'UP' ? 'selected' : '' }} value="Up">Newest to Oldest</option>
                                                            <option {{ $edit_compaigns['data']['lead_order'] === 'DOWN' ? 'selected' : '' }} value="Down">Oldest to Newest</option>
                                                            <option {{ $edit_compaigns['data']['lead_order'] === 'DOWN COUNT' ? 'selected' : '' }} value="Down Count">Most Called First</option>
                                                            <option {{ $edit_compaigns['data']['lead_order'] === 'UP COUNT' ? 'selected' : '' }} value="Up Count">Least Called First</option>
                                                            <option {{ $edit_compaigns['data']['lead_order'] === 'RANDOM' ? 'selected' : '' }}   value="RANDOM">Shuffled</option>


                                                            <!-- <option {{$edit_compaigns['data']['lead_order'] == 'Up' ? 'selected' : ''}}  value="Up">Newest to Oldest</option>
                                                            <option {{$edit_compaigns['data']['lead_order'] == 'Down' ? 'selected' : ''}} value="Down">Oldest to Newest</option>
                                                            <option {{$edit_compaigns['data']['lead_order'] == 'Down Count' ? 'selected' : ''}} value="Down Count">Most Called First</option>
                                                            <option {{$edit_compaigns['data']['lead_order'] == 'Up Count' ? 'selected' : ''}} value="Up Count">Least Called First</option>
                                                            <option {{$edit_compaigns['data']['lead_order'] == 'Random' ? 'selected' : ''}} value="Random">Shuffled</option> -->
                                                        </select>
                                                    </div>
                                                </div>

                                                

                                                <div class="col-sm-12 col-md-6 col-lg-6 {{ $velocity == '0' ? 'd-none' : '' }} " id="speed_div"  >
                                                    <input type="hidden" readonly id='velocity_val' value='{{$velocity}}'>
                                                    <div class="form-group "  >
                                                        <label for="Velocity" class="form-label">Velocity</label>
                                                        <select name="field_55" class="form-control" id="Velocity">
                                                        </select>
                                                    </div>
                                                </div>



                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="Call Guide" class="form-label">Call Guide</label>
                                                        {{-- <input type="text" class="form-control" id="group" name="group" value="{{ isset($dailer_agent_user['user_group']) ? $dailer_agent_user['user_group'] : '' }}" placeholder="Group .."> --}}
                                                        <select name="profile_script" class="form-control" id="call_guide">

                                                            @if ($get_Scripts != "" && $get_Scripts !=NULL)
                                                                @foreach ($get_Scripts['script_id'] as $i=>$scrip)
                                                                @if ($get_Scripts['active'][$i] == 'Y')
                                                                    <option {{$edit_compaigns['data']['campaign_script'] == $get_Scripts['script_id'][$i]  ? 'selected' : ''}}    value="{{$get_Scripts['script_id'][$i]}}">{{$get_Scripts['script_name'][$i]}}</option>
                                                                    
                                                                @endif
                                                            
                                                                @endforeach
                                                                
                                                            @else
                                                            <option value="">None</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label for="status" class="form-label">status</label>
                                                        



                                                        <select name="status" class="form-control select2" id="status">    
                                                            <option value="Y" {{ (isset($edit_compaigns['data']['active']) && $edit_compaigns['data']['active'] == 'Y') ? 'selected' : '' }}>Active</option>
                                                            <option value="N" {{ (isset($edit_compaigns['data']['active']) && $edit_compaigns['data']['active'] == 'N') ? 'selected' : '' }}>Not Active</option>
                                                        </select>

                                                    </div>
                                                </div>                  
                                            </div>
                                        
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                                    <a class="btn btn-default btn-md btn-block" href="{{ route('services.campaigns', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_service_id]) }}">Cancel</a>
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

                           


                        </div>

                        <div class="tab-pane fade  @if(session()->has('tab') && session('tab') == 'call-Logs') show active @endif " id="call-log" role="tabpanel" aria-labelledby="call-logs-tab">
                            <div class="card-body">
                                <section class="content">

                                    <!-- Default box -->
                                    <div class="card">
                                      <div class="card-header">
                                        <h3 class="card-title">All compaigns</h3>
                                
                                        {{-- <div class="card-tools">
                                
                                          <a href="javascript:void(0);" type="button" class="btn btn-sm btn-primary" onclick="show_add_compaign_modal();"> <i class="fas fa-plus"></i> Add compaigns </a>
                                
                                        </div> --}}
                                       
                                      </div>
                                      <div class="card-body" style="position: relative;">
                                
                                      <div class="acbz" style="position: absolute;width: 250px;right: 275px;top: 20px ;z-index:9;">
                                
                                        {{-- <div class="btn-group" style="margin-left:50px;">
                                            <button type="button" class="btn btn-default">Status - <span id="cur_status">All</span> </button>
                                            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <div class="dropdown-menu" role="menu" >
                                                <a class="dropdown-item" href="javascript:;" onclick="search_filter('all')">All</a>
                                                <a class="dropdown-item" href="javascript:" onclick="search_filter('Active')" >Active</a>
                                                <a class="dropdown-item" href="javascript:" onclick="search_filter('Not Active')" >Not Active</a>
                                            </div>
                                        </div> --}}
                                      </div>
                                
                                
                                        <table id="Compaign-table" class="table table-striped table-hover vonexta-table">
                                          <thead>
                                            <tr>
                                              <th>ID</th>
                                              <th>Name</th>
                                              <th>Time Protection</th>
                                              <th>Status</th>
                                              <th>Refresh Today</th>
                                              <th>Contacts Count</th>
                                              <th>Action</th>
                                            </tr>
                                          </thead>
                                          <tbody>

                                            

                                            @if ($get_lists != "" && $get_lists != Null)
                                                
                                            @foreach ($get_lists['list_id'] as $i => $get_list)
                                            <tr>
                                                <td>{{$get_lists['list_id'][$i]}}</td>
                                                <td>{{$get_lists['list_name'][$i]}}</td>
                                                <td>
                                                    @if ($get_lists['local_call_time'][$i] ==  'campaign')
                                                        Use Profile
                                                    @else
                                                        {{$get_lists['local_call_time'][$i] }}
                                                    @endif
                                                </td>
    
                                                <td>
                                                    @if ($get_lists['active'][$i] == 'Y')
                                                    <span class="text-success"> <strong>Active</strong> </span>
                                                    @else
                                                    <span class="text-danger"> <strong>Not Active</strong></span>
                                                    @endif
                                                </td>

                                                <td>{{$get_lists['resets_today'][$i]}}</td>
                                                <td>{{$get_lists['tally'][$i]}}</td>
                                                <td>
                                                    <div class="btn-group">
                                                      <button type="button" class="btn btn-default">Actions</button>
                                                      <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                                        <span class="sr-only">Toggle Dropdown</span>
                                                      </button>
                                                      <div class="dropdown-menu" role="menu">
                                                        <a class="dropdown-item" href="">Edit</a>
                                                        <a class="dropdown-item" href="">Refresh</a>
                                                      </div>
                                                    </div>
                                                  </td>


                                            </tr>
                                           
                                            @endforeach
                                
                                              
                                            @endif

                                            

                                          
                                
                                
                                          </tbody>
                                
                                        </table>
                                        <script>
                                          // Initialize DataTables
                                        </script>
                                      </div>
                                      <!-- /.card-body -->
                                      <div class="card-footer">
                                
                                      </div>
                                      <!-- /.card-footer-->
                                    </div>
                                    <!-- /.card -->
                                
                                  </section>
                            </div>

                        </div>

                        <div class="tab-pane fade" id="activity" role="tabpanel" aria-labelledby="activity-tab">

                                <div class="col-md-4 mt-3">
                                    <h4>advanced</h4>
                                </div>   
                        </div>
                        <div class="tab-pane fade" id="caller" role="tabpanel" aria-labelledby="Dynamic-tab">

                            <div class="col-md-4 mt-3">
                                <h4>Dynamic Caller ID</h4>
                            </div>   
                        </div>
                        <div class="tab-pane fade" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">

                            <form method="POST" action="{{ route('Outbount.customAttribute', ['service' => strtolower('Dailer')]) }}" class="form-horizontal">
                            @csrf
                                <input type="hidden" name="organization_service_id" value="" readonly>
                                <input type="hidden" name="profile_id" value="{{$edit_compaigns['data']['campaign_id']}}" readonly>
                              
                                @if(isset($GetCustomAttributes))

                                @php
                                    $fields = json_decode($GetCustomAttributes, true);
                                @endphp
                                <div class="card">
                                    <div class="card-header">
                                        <div class="row">
                                            <div class="col-md-6">
                                            <h3 class="card-title">Custom Attributes</h3>
                                            </div>
                                            <div class="col-md-6 text-end" style="text-align: end;">
                                            <button type="button" class="btn btn-primary btn-sm" onclick="add_custom_attribute();">Add Custom Attribute</button>
                                            </div>
                                        </div>
                                    
                                            

                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach($fields as $field)
                                                <div class="col-sm-12 col-md-6 col-lg-6">
                                                    <div class="form-group">
                                                        <label  class="form-label">{{ $field['display_name'] }}</label>

                                                        @php
                                                            $custom_value = custom_value($field['api_name'], $edit_compaigns['data']['custom_attributes']);
                                                        @endphp


                                                        <input type="text" class="form-control" value="{{$custom_value}}" name="{{ $field['api_name'] }}" >

                                                       
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                            <a class="btn btn-default btn-md btn-block" href="{{ route('services.campaigns', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_service_id]) }}">Cancel</a>
                                            </div>
                                            <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                            <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                            <button class="btn btn-success btn-md btn-block" type="submit">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /.card-footer-->
                                </div>
                                @endif
                                <!-- /.card -->
                            </form>
                        
                        
                        
                        </div>

                    </div>
                </div>

            </div>
    </div>
    <!-- /.card-body -->
</div>




 <!-- modal for custom_attribute -->
 <div class="modal fade" id="outbound_cutom_attributes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Custom Attribute :</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      <form id="myForm" method="post" action="{{ route('add-custom-attributes', ['service' => strtolower('Dailer')]) }}">
            <input type="hidden" class="form-control" id="organization_services_id" name="organization_services_id" value="{{$organization_service_id}}">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 ">
                        <div class="form-group">
                            <label  class="form-label">Api Name<span class="text-danger d-none" id="apiError">*</span></label>
                            <input type="text" class="form-control" name="api" id="apiInput" onchange="check_previous_api_name('apiInput', {{$organization_service_id}}, 'add-apiname')" required>
                            <span style="color:red" id="api-error"></span>
                            <span style="color:green" id="api-success"></span>
                        </div>
                    </div>
                
                    <div class="col-sm-6 col-md-6 ">
                        <div class="form-group">
                            <label  class="form-label">Display Name<span class="text-danger d-none" id="nameError">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                            
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" id="submitButton" class="btn btn-primary btn-sm" disabled>Save</button>
            </div>
        </form>

      </div>
      
    </div>
  </div>
</div>
</section>
<!-- /.content -->






    </div>
@endSection
    