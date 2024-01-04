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
    
    

    <div class="container mt-5">

        <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"  class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Sign out</a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>


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
    
                              @if($detail)
                                @if ($detail['result'] == 'success' )
                                @php
                                    $detail = $detail['data'];
                                    $service = 'Dialler';
                                @endphp
                                <a class="dropdown-item" href="{{ route('services.agents.edit', ['service' => strtolower($service), 'organization_services_id' => $userAgent->services_id ,'AgentID' => $detail['user']  ] ) }}"> {{$detail['full_name']}}</a>
                                @endif
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
    
                @if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin') )
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
     <script>
         const baseUrl = "{{ url('/') }}";
     </script>
     @stack('scripts')

</body>
</html>