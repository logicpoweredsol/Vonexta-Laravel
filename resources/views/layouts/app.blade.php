<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token"  id="csrf-token"  content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
        {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> --}}
        @stack('css')
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

        <!-- daterange picker -->
        {{-- <link rel="stylesheet" href="{{asset('plugins/daterangepicker/daterangepicker.css')}}"> --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
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

                    

                   
                <!-- Navbar Search -->
                {{-- <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                    <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                    <form class="form-inline">
                        <div class="input-group input-group-sm">
                        <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                            <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                            <i class="fas fa-times"></i>
                            </button>
                        </div>
                        </div>
                    </form>
                    </div>
                </li> --}}
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('views/Common.js') }}"></script>
         <!-- SweetAlert2 -->
          {{-- <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}


        <script src="{{ asset('views/profile/profile.js') }}"></script>
        
        <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

        {{-- select2 --}}
        {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
        <script>
            const baseUrl = "{{ url('/') }}";
        </script>
        @stack('scripts')
    </body>
</html>
