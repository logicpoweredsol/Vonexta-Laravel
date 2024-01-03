@extends('layouts.app')

@section('content')

<style>
  .card {
    box-shadow: 15px 0px 35px rgba(0, 0, 0, 0.2); /* Adjusted values for a more visible shadow */
}
</style>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Dashboard</h1>
          </div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item active">Home</li>
              <!-- <li class="breadcrumb-item active">Blank Page</li> -->
            </ol>
          </div>

        </div>

        <div class="card">
          <div class="card-body mb-5">
           
            <div class="row justify-content-md-center mt-5" >
              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/agent-11.png')}}" alt="">
                    </div>


              

                    @if (auth()->user()->hasRole('superadmin'))
                      <div class="contentbox">
                        <h5>Agent</h5>
                        <p class="mt-1">communicate with your customers and prospects</p>
                      </div>
                    @else
                        
                    <div class="contentbox">
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          Agent
                        </button>

                        @php
                          $agent_user_detail = [];
                        @endphp

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          @foreach ($userAgents as $userAgent)

                          @php
                            $detail = get_agent_detail($userAgent->services_id , $userAgent->api_user );
                          @endphp

                          @if ($detail['result'] == 'success' )
                            @php
                                $detail = $detail['data'];
                            @endphp

                            <a class="dropdown-item" href="#"> {{$detail['full_name']}}</a>

                            @endif
                          @endforeach
                        </div>
                      </div>
                      <p class="mt-1">(communicate with your customers and prospects)</p>
                    </div>

                    @endif
                    

                   
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>
              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/images.png')}}" alt="">
                    </div>
                    <div class="contentbox">
                      <h5>customer support</h5>
                      <p class="mt-1">Access the support team , documentation , case and solutions</p>
                    </div>
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>
              
    
            </div>

            <div class="row justify-content-md-center mt-5" >
              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/images (1).png')}}" alt="">
                    </div>
                    <div class="contentbox">
                      <h5>Supervisor</h5>
                      <p class="mt-1">Manage your agents activities</p>
                    </div>
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>

              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/images (2).png')}}" alt="">
                    </div>
                    <div class="contentbox">
                      <h5>Training</h5>
                      <p class="mt-1">Access university for role-based training and certification for administrators</p>
                    </div>
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>
             
    
            </div>


            <div class="row justify-content-md-center mt-5" >
              @if (auth()->user()->hasRole('superadmin'))
              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/agent-11.png')}}" alt="">
                    </div>
                    <div class="contentbox">
                      <h5>Administrator</h5>
                      <p class="mt-1">Configure your contact center</p>
                    </div>
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>

              @endif

              <div class="col-md-5">
                <div class="contentbox-wrap">
                  <div class="inner d-flex">
                    <div class="img-wrapper">
                      <img src="{{asset('dist/img/images (3).png')}}" alt="">
                    </div>
                    <div class="contentbox">
                      <h5>My Settings</h5>
                      <p class="mt-1">Change your password and other personal settings</p>
                    </div>
                  </div>
                  <a href="#" class="overlaylink"></a>
                </div>
              </div>

    
            </div>
    
          

          </div>
          <!-- /.card-body -->
        </div>


      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    {{-- <section class="content">

      <!-- Default box -->
      <div class="card" style="display:none;">
        <div class="card-header">
          <h3 class="card-title">Title</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
              <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
              <i class="fas fa-times"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          Start creating your amazing application!
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
          Footer
        </div>
        <!-- /.card-footer-->
      </div>
      <!-- /.card -->

    </section> --}}
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
@endSection