<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home</title>

    <meta name="csrf-token"  id="csrf-token"  content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @stack('css')
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        .card {
          box-shadow: 15px 0px 35px rgba(0, 0, 0, 0.2); /* Adjusted values for a more visible shadow */
      }
      </style>


</head>
<body>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        <div class="homecontent-wrap">
          <div class="homeheader-wrap">
            <div class="row">
              <div class="col-sm-3 offset-sm-9 justify-content-end d-flex">
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    
                  </button>
                  <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <a class="dropdown-item" href="#" onclick="OpenResetModel()">Forgot Password</a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          <div class="homecontent">
            <div class="homelogo">
              <a href="#"><img src="https://admin-vn21.vonexta.com/dist/img/vonexta_logo.png" alt="Vonexta"></a>
            </div>
            <div class="card-body mb-5">
            
              <div class="row justify-content-md-center mt-5" >
              
                <di v class="col-md-5">
                    <div class="contentbox-wrap">
                      <div class="inner">
                        <div class="img-wrapper">
                          <img src="{{asset('dist/img/agent-11.png')}}" alt="">
                        </div>
                        @if (auth()->user()->hasRole('superadmin'))
                          <div class="contentbox">
                            <h5>Agent</h5>
                            <p class="mt-1">communicate with your customers and prospects</p>
                          </div>
                        @else

                            

                        @php
                            $check_number = count($userAgents);
                        @endphp
                        <div class="contentbox">
                          <div class="dropdown">
                            <button class="btn btn-secondary @if($check_number > 1) dropdown-toggle @endif " type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              Agent
                            </button>
    
                            @php
                              $agent_user_detail = [];
                              $check_number = count($userAgents);

                      
                            @endphp

                            @if ($check_number > 1)
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                              @foreach ($userAgents as $userAgent)
                               <a class="dropdown-item" href="{{ route('services.agents.detail', ['service' => strtolower($service), 'organization_services_id' => $userAgent->services_id ,'AgentID' => $userAgent->api_user  ] ) }}">{{$userAgent->api_user }} - {{$userAgent->name }}</a>
                              @endforeach
                            </div>
                            @endif
                            
                          </div>
                          <p class="mt-1">(communicate with your customers and prospects)</p>
                        </div>
    
                        @endif
                        
                        <i class="fas fa-arrow-right"></i>
                      
                      </div>
                      <a href="#" class="overlaylink"></a>
                    </div>
                </di>
    
                @if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin') )
                  <div class="col-md-5">
                    <div class="contentbox-wrap">
                      <div class="inner">
                        <div class="img-wrapper">
                          <img src="{{asset('dist/img/agent-11.png')}}" alt="">
                        </div>
                        <div class="contentbox">
                          <h5>Administrator</h5>
                          <p class="mt-1">Configure your contact center</p>
                        </div>
                        <i class="fas fa-arrow-right"></i>
                      </div>
                      <a href="{{url('dashboard')}}" class="overlaylink"></a>
                    </div>
                  </div>
                @endif
    
                
      
              </div>
    
          
    
            </div>
          </div>
        </div>

   
   
     <!-- jQuery -->
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <script src="{{ asset('views/Common.js') }}"></script>
      <!-- SweetAlert2 -->
       {{-- <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script> --}}


     <script src="{{ asset('views/profile/profile.js') }}"></script>
     <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
     <!-- Bootstrap 4 -->
     <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <!-- AdminLTE App -->
     <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>
     <script src="{{ asset('views/profile/profile.js') }}"></script>
     <script>
         const baseUrl = "{{ url('/') }}";
     </script>
     @stack('scripts')

</body>
</html>