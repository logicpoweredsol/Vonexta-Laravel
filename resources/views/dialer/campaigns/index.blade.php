@extends('layouts.app')
@push('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endpush
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Campaigns</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
              <li class="breadcrumb-item active">Campaigns</li>
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
          <h3 class="card-title">All Campaigns</h3>

          <div class="card-tools">
            <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button> -->
            <a href="javascript:void(0);" class="btn btn-sm btn-primary" id="btnAddCampaign" title="Add Campaign">
              <i class="fas fa-bullhorn"></i>
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-secondary" id="btnAddDisposition" title="Add Disposition">
              <i class="fas fa-tags"></i>
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-warning" id="btnAddLeadRecycling" title="Add Lead Recycling">
              <i class="fas fa-recycle"></i>
            </a>
            <a href="javascript:void(0);" class="btn btn-sm btn-info" id="btnAddAreaCode" title="Add Area Code">
              <i class="fas fa-map"></i>
            </a>
          </div>
        </div>
        <div class="card-body">
            @if(null!==session('msg'))
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('msg'); }}
                        </div>
                    <div>
                </div>
            @endif
            @if(null!==session('error'))
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-lg-12">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            {{ session('error'); }}
                        </div>
                    <div>
                </div>
            @endif
            <ul class="nav nav-tabs vonexta-nav" id="campaigns-tabs" role="tablist">
                <li class="nav-item vonext-campaign-item">
                    <a class="nav-link active" id="campaigns-home-tab" data-toggle="pill" href="#campaigns-home" role="tab" aria-controls="campaigns-home" aria-selected="true">Campaigns</a>
                </li>
                <li class="nav-item vonext-campaign-item">
                    <a class="nav-link" id="campaigns-disposition-tab" data-toggle="pill" href="#campaigns-disposition" role="tab" aria-controls="campaigns-disposition" aria-selected="false">Dispisitions</a>
                </li>
                <li class="nav-item vonext-campaign-item">
                    <a class="nav-link" id="campaigns-leadRecycling-tab" data-toggle="pill" href="#campaigns-leadRecycling" role="tab" aria-controls="campaigns-leadRecycling" aria-selected="false">Lead Recycling</a>
                </li>
                <li class="nav-item vonext-campaign-item">
                    <a class="nav-link" id="campaigns-areacodeCID-tab" data-toggle="pill" href="#campaigns-areacodeCID" role="tab" aria-controls="campaigns-areacodeCID" aria-selected="false">Area Code CID</a>
                </li>
            </ul>
            <div class="tab-content" id="campaigns-tabsContent">
                <div class="tab-pane fade show active" id="campaigns-home" role="tabpanel" aria-labelledby="campaigns-home-tab">
                    @include('dialer.campaigns.tabs.campaigns')
                </div>
                <div class="tab-pane fade" id="campaigns-disposition" role="tabpanel" aria-labelledby="campaigns-disposition-tab">
                    @include('dialer.campaigns.tabs.dispositions')
                </div>
                <div class="tab-pane fade" id="campaigns-leadRecycling" role="tabpanel" aria-labelledby="campaigns-leadRecycling-tab">
                    @include('dialer.campaigns.tabs.lead_recycling')
                </div>
                <div class="tab-pane fade" id="campaigns-areacodeCID" role="tabpanel" aria-labelledby="campaigns-areacodeCID-tab">
                    @include('dialer.campaigns.tabs.accid')
                </div>
            </div>
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
  <!-- /.content-wrapper -->
  <!-- Adding Modals here --- Only Necessary Modals to be included... -->
  @include('dialer.campaigns.modals.add_campaign')
@endSection

@push('scripts')
    <script>
        //Any constants to be used by this service/module...
        const service = "{{ $service }}";
        // {{ route('services.users.new',['service' => $service]) }}
    </script>
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
    <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
    <script src="{{ asset('views/services/dialer/campaigns/index.js') }}"></script>
@endpush
