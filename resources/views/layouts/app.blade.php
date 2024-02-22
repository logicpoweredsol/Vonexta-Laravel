<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token"  id="csrf-token"  content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
     
        {{-- @stack('css') --}}
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])


          <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- JQVMap -->
        <link rel="stylesheet" href="{{ asset('plugins/jqvmap/jqvmap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        <!-- overlayScrollbars -->
        <link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
        
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
        <!-- summernote -->
        <link rel="stylesheet" href="{{ asset('plugins/summernote/summernote-bs4.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

          <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">


        <link rel="stylesheet" href="{{ asset('plugins/bs-stepper/css/bs-stepper.min.css') }}">
        <!-- Ion Slider -->
        <link rel="stylesheet" href="{{ asset('plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
        <!-- bootstrap slider -->
        <link rel="stylesheet" href="{{ asset('plugins/bootstrap-slider/css/bootstrap-slider.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
        

        <!-- sweetalert -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    </head>
    <body class="hold-transition sidebar-mini layout-fixed">
        <!-- Site wrapper -->
        <div class="wrapper">
            <!-- Navbar -->
            @if (auth()->user()->hasRole('user'))
                <nav class="main-header navbar navbar-expand navbar-dark navbar-light" style="margin-left: 0rem">
            @else
                <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
            @endif

          
           
                <!-- Left navbar links -->
                <ul class="navbar-nav">
                    @if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin') )
                        <li class="nav-item">
                            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                        </li>
                    @endif

                {{-- <li class="nav-item d-none d-sm-inline-block">
                    <a href="../../index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li> --}}
                </ul>


                @if( ! auth()->user()->hasRole('superadmin'))
                <div class="text-center">
                    <a href="#" style="font-size: x-large !important;" class="d-block font-weight-bold text-white">{{Auth::user()->organizations[0]->name  }}</a>
                </div>  
              
                @endif


                <!-- Right navbar links -->
                <ul class="navbar-nav ml-auto">
                    <div>
                        @if (Session::has('original_user_id') && Session::has('impersonated_user_id'))

                        @php
                          $impersonatedUser = Session::get('impersonated_user_id');
       
                        @endphp                          
                          <p style="color: white;">Currently Impersonating {{ $impersonatedUser->name }} - {{$impersonatedUser->organizations[0]->name}} <a class="btn btn-secondary" onclick="event.preventDefault(); $('#leaveimpersonate').submit();"  href="javascript:;"> Leave Impersonation </a> </p> 
                          <form method="POST" action="{{ route('leaveImpersonation') }}" id="leaveimpersonate">
                            @csrf
                        </form>
                        @endif
                    </div>

                    
                <li class="nav-item">
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link> --}}


                            <x-dropdown-link href="#" onclick="OpenResetModel()">
                                {{ __('Reset Password') }}
                            </x-dropdown-link>


                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                            
                        </x-slot>
                    </x-dropdown>
                </div>
                </li>
                </ul>
            </nav>
            @if(auth()->user()->hasRole('superadmin'))
                @include('layouts.superadmin.navigation')
            @else
                @include('layouts.navigation')
            @endif
            @yield('content')
        </div>
        <!-- /.navbar -->
        <!-- jQuery -->


        

        {{-- Start  common js --}}
            <script src="{{ asset('views/Common.js') }}"></script>
            <!-- jQuery -->
            <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
            <script src="{{ asset('plugins/moment/moment.min.js')}}"></script>
            <script src="{{ asset('plugins/daterangepicker/daterangepicker.js')}}"></script>
            <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>


  
            <!-- Bootstrap 4 -->
            <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
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
            <!-- <script src="{{ asset('plugins/datatables-buttons/js/buttons.status.min.js') }}"></script> -->
            <!-- Custom Datatable -->
            <script src="{{ asset('views/datatable/datatable.js') }}"></script>
            <!-- Bootstrap Switch -->
            <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}" ></script>
            <!-- Ion Slider -->
            <script src="{{ asset('plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
            <!-- Bootstrap slider -->
            <script src="{{ asset('plugins/bootstrap-slider/bootstrap-slider.min.js') }}"></script>
            <!-- SweetAlert2 -->
            <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
            <script src="{{ asset('plugins/bs-stepper/js/bs-stepper.min.js') }}"></script>
            
            

            <!-- AdminLTE App -->
            <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

         {{-- END  common js --}}



       


         {{-- Start Pages JS --}}

         {{-- Request::path() --}}
         {{-- str_contains(Request::path(), 'agency') --}}
    
         @if (Request::route()->getName() == 'services.agents')
            <script src="{{ asset('views/services/dialler/users/index.js') }}"></script>
            <script src="{{ asset('views/Common.js') }}"></script>
         @endif
         @if (Request::route()->getName() == 'services.agents.edit')
            <script src="{{ asset('views/services/dialler/users/edit.js') }}"></script>
            <script src="{{ asset('views/Common.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'services.campaigns')
         <script src="{{ asset('views/services/dialler/campaigns/index.js') }}"></script>
         @endif
         @if (Request::route()->getName() == 'services.campaigns.edit')
         <script src="{{ asset('views/services/dialler/campaigns/edit.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'services.agent-role')
         <script src="{{ asset('views/agentrole/index.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'services.agent-role-edit')
         <script src="{{ asset('views/agentrole/edit.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'administration.users')
        <script src="{{ asset('views/administration/users/index.js') }}"></script>
        <script src="{{ asset('views/administration/users/common.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'organizations.view')
            <script src="{{ asset('views/organizations/edit.js') }}"></script>
            <script src="{{ asset('views/organizations/users.js') }}"></script>
        @endif


         @if (Request::route()->getName() == 'organizations')
         <script src="{{ asset('views/organizations/users.js') }}"></script>
         <script src="{{ asset('views/organizations/index.js') }}"></script>
         
         @endif
         @if (Request::route()->getName() == 'accounts')
         <script src="{{ asset('views/superadmin/super-admin.js') }}"></script>
         @endif

         @if (Request::route()->getName() == 'administration.users.view')
        <script src="{{ asset('views/administration/users/edit.js') }}"></script>
        <script src="{{ asset('views/administration/users/common.js') }}"></script>
         @endif
         
         
         

         {{-- End Pages JS --}}



        <script>
            const baseUrl = "{{ url('/') }}";
        </script>
        {{-- @stack('scripts') --}}
    </body>
</html>
