@extends('layouts.app')

@section('content')


  @if (auth()->user()->hasRole('user'))
    <div class="content-wrapper" style="margin-left: 0rem">
  @else
    <div class="content-wrapper">
  @endif


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




      </div>

    </section>


  </div>
  <!-- /.content-wrapper -->
@endSection