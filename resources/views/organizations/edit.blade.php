@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
    <!-- Nothing for now -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

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
    @endif
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Organizations</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item"><a href="{{ route('organizations') }}">Organizations</a></li>
              <li class="breadcrumb-item active">Edit Organization</li>
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
                        <a class="nav-link @if(session()->has('tab') && session('tab') == 'Organization-home')  active @endif  @if( !session()->has('tab') && !session('tab')) active @endif   " id="Organization-home-tab" data-toggle="pill" href="#Organization-home" role="tab" aria-controls="Organization-home" aria-selected="true">Organization Details</a>
                    </li>

                    <li class="nav-item vonext-campaign-item">
                        <a class="nav-link @if(session()->has('tab') && session('tab') == 'Organization-service')  active @endif " id="Organization-service-tab" data-toggle="pill" href="#Organization-service" role="tab" aria-controls="campaigns-disposition" aria-selected="false">Services</a>
                    </li>

                    <li class="nav-item vonext-campaign-item">
                        <a class="nav-link @if(session()->has('tab') && session('tab') == 'Organization-user')  active @endif " id="Organization-user-tab" data-toggle="pill" href="#Organization-user" onclick="change_tab('org-user');" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Users</a>
                    </li>
                    
                   
                </ul>

                <div class="tab-content" id="campaigns-tabs Content">

                    
                    <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Organization-home') show active @endif   @if( !session()->has('tab') && !session('tab')) show active @endif  "  id="Organization-home" role="tabpanel" aria-labelledby="Organization-home-tab">

                        <form method="POST" action="{{ route('organizations.edit', $organization->id) }}" class="form-horizontal">
                            @csrf
                            @method("PUT")
                            <input type="hidden" name="id" id="orgId" value="{{ $organization->id }}">
                            <div class="card">
                                {{-- <div class="card-header">
                                    <h3 class="card-title">Edit Organization</h3>
                                </div> --}}
                                <div class="card-body">
                                    @if(null!==session('msg'))
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <div class="alert alert-success alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    {{ session('msg'); }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if(null!==session('error'))
                                        <div class="row">
                                            <div class="col-sm-12 col-md-12 col-lg-12">
                                                <div class="alert alert-danger alert-dismissible">
                                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                    {{ session('error'); }}
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Name</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name')??$organization->name }}" placeholder="Legal company name">
                                            </div>
                                            <span>
                                                @error('name')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="phone" class="form-label">Phone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone')??$organization->phone }}" placeholder="Company contact">
                                            </div>
                                            <span>
                                                @error('phone')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="address" class="form-label">Address</label>
                                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address')??$organization->address }}" placeholder="Company address">
                                            </div>
                                            <span>
                                                @error('address')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="address2" class="form-label">Address 2</label>
                                                <input type="text" class="form-control" id="address2" name="address2" value="{{ old('address2')??$organization->address2 }}" placeholder="Company address 2">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="city" class="form-label">City</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city')??$organization->city }}" placeholder="City">
                                            </div>
                                            <span>
                                                @error('city')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="state" class="form-label">State</label>
                                                <input type="text" class="form-control @error('state') is-invalid @enderror" id="state" name="state" value="{{ old('state')??$organization->state }}" placeholder="State">
                                            </div>
                                            <span>
                                                @error('state')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="zip" class="form-label">Zip/Postal Code</label>
                                                <input type="text" class="form-control @error('zip') is-invalid @enderror" id="zip" name="zip" value="{{ old('zip')??$organization->zip }}" placeholder="Zip/Postal Code">
                                            </div>
                                            <span>
                                                @error('zip')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 col-lg-6">
                                            <div class="form-group">
                                                <label for="active" class="form-label">Active</label>
                                                <select name="active" class="form-control" id="active" active="{{ $organization->active }}">
                                                    <option value="" @if(old('active')=='' && $organization->active=="") {{ "selected" }} @endif>Select Option</option>
                                                    <option value="1" @if(old('active')=='1' || $organization->active==1) {{ "selected" }} @endif>Yes</option>
                                                    <option value="0" @if(old('active')=='0' || $organization->active==0) {{ "selected" }} @endif>No</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <hr> --}}
                                    
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <div class="row">
                                        <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                            <a class="btn btn-default btn-md btn-block" href="{{  route('organizations') }}">Cancel</a>
                                        </div>
                                        <div class="col-sm-12 col-md-8 col-lg-8"></div>
                                        <div class="col-sm-12 col-md-2 col-lg-2 mb-3">
                                            <button class="btn btn-success btn-md btn-block" type="submit" >Save</button>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-footer-->
                            </div>
                            <!-- /.card -->
                        </form>

                    </div>

                    <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Organization-service') show active @endif " id="Organization-service" role="tabpanel" aria-labelledby="Organization-service-tab">
                        <legend for="services">Services</legend>
                        <fieldset>
                            <div class="row">
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-group">
                                    <!-- <label for="services" class="form-label">Services</label> -->
                                        <div class="input-group">
                                            <select name="services" class="form-control" id="services_type">
                                                <option value="" @if(old('services')=='') {{ "selected" }} @endif>Select Service</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}">{{ $service->name }} | {{ $service->description }}</option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <span class="input-group-text"><button type="button" class="btn btn-primary btn-xs btn-block" id="btnAddService"><i class="fas fa-plus"></i></button></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                </div>
                            </div>
                            <div class="row" id="myServices">
                                <div class="col-sm-12 col-md-12 col-lg-12">
                                    <div class="card">
                                        <!-- /.card-header -->
                                        <div class="card-body table-responsive">
                                            <table id="servicesTable" class="table table-hover">
                                                <thead>
                                                <th> </th>
                                                <th>Service</th>
                                                <th>Service Nick Name</th>
                                                <th>Slug</th>
                                                <th>Action</th>
                                                </thead>
                                                <tbody>
                                                @foreach($organizationServices as $myService)


                                                
                                         
                                                @php
                                                   $check_valied =ceck_service_detail($myService->pivot->id);
                                                @endphp
                                                

                                                    @if ($check_valied)
                                                        
                                                        <tr class="myService">
                                                            <td>{{ $loop->index+1 }}</td>
                                                            <td>{{ $myService->name }}</td>
                                                            <td>{{ $myService->pivot->service_nick_name }}</td>
                                                            <td>{{ $myService->pivot->service_name }}</td>
                                                            <td>
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-primary btnEditService" data-id="{{ $myService->pivot->id  }}"><i class="fas fa-pencil-alt"></i></a>
                                                                <a href="javascript:void(0);" class="btn btn-sm btn-danger btnRemoveService" data-id="{{ $myService->pivot->id  }}"><i class="fas fa-trash"></i></a>
                                                            </td>
                                                        </tr>

                                                    @endif


                                                   
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>

                    <div class="tab-pane fade @if(session()->has('tab') && session('tab') == 'Organization-user') show active @endif  " id="Organization-user" role="tabpanel" aria-labelledby="Organization-user-tab">

                        <div id="org-user" @if(!session()->has('tab-action') || (session('tab-action') != 'org-user')) class='d-none' @endif>
                            @include('organizations/user/search')
                        </div>
                        
                        <div id="add-org-user" @if(!session()->has('tab-action') || (session('tab-action') != 'add-org-user')) class='d-none' @endif>
                            @include('organizations/user/add')
                        </div>
                        
                        <div id="edit-org-user" @if(!session()->has('tab-action') || (session('tab-action') != 'edit-org-user')) class='d-none' @endif>
                            @include('organizations/user/edit')
                        </div>

                        
                        
                        {{-- <div id="org-user" @if(session()->has('tab-action') && session('tab-action') != 'org-user') class='d-none'  @endif >
                            @include('organizations/user/search')
                        </div>

                        <div id="add-org-user" @if(session()->has('tab-action') && session('tab-action') != 'add-org-user') class='d-none'  @endif >
                            @include('organizations/user/add')
                        </div>


                        <div id="edit-org-user" @if(session()->has('tab-action') && session('tab-action') != 'edit-org-user') class='d-none'  @endif >
                            @include('organizations/user/edit')
                        </div> --}}


                       

                           
                    </div>

                   
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              
            </div>
            <!-- /.card-footer-->
        </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
    <!-- Modal to get connection parameters from the super admin to connect to the service... -->
    <div class="modal fade" id="modalConnectionParameters" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <input type="hidden" id="add_serive_name" value="">
            <input type="hidden" id="add_service_type" value="">
            {{-- <input type="hidden" id="add_org_id" value=""> --}}
            <input type="hidden" id="add_serive_Nickname" value="">
            
            <form id="formConnectionParameters" class="form-horizontal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add service connection</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body" id="connectionParams"></div>
                    <!-- <div class="modal-body" id="connectionParams">
                        <input type="hidden" name="id" id="os_id" value="">
                    </div> -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="btnAddConnectionParameters">Save changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

<!-- Modal to get connection parameters from the super admin to connect to the service... -->
<div class="modal fade" id="modalEditOgService" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalTitleEditOgService" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitleEditOgService">Edit service connection</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <input type="hidden" id="ogService_id" >
                    <input type="hidden" id="service_type" >

                    <div class="form-group row">
                        <label for="edit_service_name" class="col-sm-12 col-md-4 col-lg-4 col-form-label">Service slug</label>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <input type="text" class="form-control" id="edit_serive_name" required placeholder="Service slug">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="edit_service_nick" class="col-sm-12 col-md-4 col-lg-4 col-form-label">Nick name</label>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <input type="text" class="form-control" id="edit_serive_Nickname" required  placeholder="nick name">
                        </div>
                    </div>



                    <form id="formEditOgService" class="form-horizontal">
                        <div  id="editOgServiceBody" >

                        </div>
                    </form>
                   
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnUpdateOgService">Save changes</button>
                </div>
            </div>
        {{-- </form> --}}
    </div>
</div>
@endSection

@push('scripts')
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
    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    <script>
        const csrfToken = $('meta[name="csrf-token"]').attr('content');
    </script>
    
    <script src="{{ asset('views/organizations/edit.js') }}"></script>
    <script src="{{ asset('views/organizations/users.js') }}"></script>
@endpush

