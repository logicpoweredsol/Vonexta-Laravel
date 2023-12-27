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
          <div class="card-body">
           
            <div class="row justify-content-md-center" >
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img style="max-width: 18%; height: 0%" src="{{asset('dist/img/agent-11.png')}}" alt="">
                    <div>
                      <h5 class="mt-2" style="color: blue">Agent</h5>
                      <p class="mt-1">(communicate with your customers and prospects)</p>
                    </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img class="mb-2" style="max-width: 18%; height: 0%" src="{{asset('dist/img/images.png')}}" alt="">
                    <div>
                      <h5 class="mt-2 ml-2" style="color: blue">New customer support</h5>
                      <p class="mt-1 ml-2">(access the support team , documentation , case and solutions)</p>
                    </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
    
            </div>
    
            <div class="row justify-content-md-center">
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img class="mb-2" style="max-width: 18%; height: 0%" src="{{asset('dist/img/images (1).png')}}" alt="">
                    <div>
                    <h5 class="ml-2" style="color: blue">Supervisor</h5>
                    <p class="ml-2">(manage your agents activities)</p>
                    </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img style="max-width: 18%; height: 0%" src="{{asset('dist/img/images (2).png')}}" alt="">
                  <div>
                    <h5 class="ml-2" style="color: blue">Training</h5>
                    <p class="ml-2">(access university for role-based training and certification for administrators)</p>
                  </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
            </div>
    
    
            <div class="row justify-content-md-center">
              @if (auth()->user()->hasRole('superadmin'))
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img style="max-width: 18%; height: 0%" src="{{asset('dist/img/download.png')}}" alt="">
                    <div>
                    <h5 class="ml-2" style="color: blue">Administrator</h5>
                    <p class="ml-2">(configure your contact center)</p>
                   </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
              </div>
              @endif
             
              <div class="col-md-5 mt-4">
                <div class="small-box bg-light">
                  <div class="inner d-flex">
                    <img style="max-width: 16%; height: 0%" src="{{asset('dist/img/images (3).png')}}" alt="">
                    <div>
                    <h5 class="ml-2" style="color: blue">My Settings</h5>
                    <p class="ml-2">(Change your password and other personal settings)</p>
                    </div>
                  </div>
                  <div class="icon">
                    <i class="ion ion-bag"></i>
                  </div>
                  <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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