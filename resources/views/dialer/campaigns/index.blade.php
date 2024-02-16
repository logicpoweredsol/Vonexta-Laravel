@extends('layouts.app')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Outbound Profiles</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">

          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              @php $nick_name  =service_name($organization_servicesID); @endphp
          <li class="breadcrumb-item"><a href="{{ route('services.agents', ['service' => strtolower('dailer'), 'organization_services_id' => $organization_servicesID]) }}">{{$nick_name}} </a></li>
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

    <!-- Default box -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">All compaigns</h3>

        <div class="card-tools">

          <a href="javascript:void(0);" type="button" class="btn btn-sm btn-primary" onclick="show_add_compaign_modal();"> <i class="fas fa-plus"></i> Add compaigns </a>

        </div>
       
      </div>
      <div class="card-body" style="position: relative;">

      <div class="acbz" style="position: absolute;width: 250px;right: 275px;top: 20px ;z-index:9;">

        {{-- <div class="btn-group" style="margin-left:50px;">
            <button type="button" class="btn btn-default">Status - <span id="cur_status">All</span> </button>
            <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                <span class="sr-only">Toggle Dropdown</span>
            </button>
            <div class="dropdown-menu" role="menu" >
                <a class="dropdown-item" href="javascript:;" onclick="search_filter('All')">All</a>
                <a class="dropdown-item" href="javascript:" onclick="search_filter('Active')" >Active</a>
                <a class="dropdown-item" href="javascript:" onclick="search_filter('Not Active')" >Not Active</a>
            </div>
        </div> --}}
      </div>


        <table id="Compaign-tableSS" class="table table-striped table-hover vonexta-table">
          <thead>
            <tr>
              <th>Profile ID</th>
              <th>Profile Name</th>
              <th>Type</th>
              <th>Velocity</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>

            @foreach($get_compaignSkills['campaign_id'] as $i=> $get_compaignSkill)


            <tr>
              <td>{{$get_compaignSkills['campaign_id'][$i]}}</td>
              <td>{{$get_compaignSkills['campaign_name'][$i]}}</td>
              <td>{{$get_compaignSkills['dial_method'][$i]}}</td>
              <td></td>

              <td>
                @if ($get_compaignSkills['active'][$i] == 'Y')
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
                    <a class="dropdown-item" href="{{ route('services.campaigns.edit', ['service' => strtolower($service), 'organization_services_id' => $organization_servicesID , 'CampaignID' =>$get_compaignSkills['campaign_id'][$i] ] ) }}">Edit</a>
                    <a class="dropdown-item" href="">Leads Pool</a>
                    <a class="dropdown-item" href="">Lists</a>
                    <a class="dropdown-item" href="">Dispositions</a>
                  </div>
                </div>
              </td>
            </tr>
            @endforeach


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
  
    <!-- add-compaign-modal -->
    <div class="modal fade" id="compaignn-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Create Compaigns</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <form method="POST" action="" id="form_add_agent_role" class="form-horizontal">

            @csrf
            <input type="hidden" class="form-control" id="organization_service_id" name="organization_service_id" value="">

            <div class="modal-body">
              <div class="row">
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="name" class="form-label">Profile ID</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="profile_id" name="profile_id" value="" placeholder="Profile ID">
                  </div>
                  <span>
                    @error('name')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                  </span>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="Profile Name" class="form-label">Profile Name</label>
                    <input type="text" class="form-control" id="profile_name" name="profile_name" value="Profile Name">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="Type" class="form-label">Type</label>

                    <input type="text" class="form-control" id="type" name="Type" value="Type">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="velocity" class="form-label">Velocity</label>
                    <input type="text" class="form-control" id="velocity" name="velocity" value="Velocity">
                  </div>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-6">
                  <div class="form-group">
                    <label for="active" class="form-label">Status</label>
                    <select name="active" class="form-control data select5" id="active" active="">
                      <option value="Y">Active</option>
                      <option value="N">Not Active</option>
                    </select>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary btn-sm">Add Compaigns</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </section>


  <!-- /.content-wrapper -->
  <!-- Adding Modals here --- Only Necessary Modals to be included... -->
  @include('dialer.campaigns.modals.add_campaign')
  @endSection

