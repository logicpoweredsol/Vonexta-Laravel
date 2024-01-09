<!-- Main Sidebar Container -->
@php
$main_menu =  request()->segment(1);
$sub_menu = request()->segment(2);
$sub_menu_main = null!==request()->segment(3) && !is_numeric(request()->segment(3)) ? request()->segment(3) : '';
$sub_menu_sub = null!==request()->segment(4) && !is_numeric(request()->segment(4)) ? request()->segment(4) : '';
@endphp


@if (auth()->user()->hasRole('superadmin') || auth()->user()->hasRole('admin') )

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('dashboard') }}" class="brand-link" style='height: 55px;'>
      <img src="{{ asset('dist/img/vonexta_logo.png') }}" alt="{{ env('APP_NAME') }}" class="brand-image img-circle" style="opacity: .8">
      <!-- <span class="brand-text font-weight-light">AdminLTE 3</span> -->
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->name }}</a>
        </div>
      </div>
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link @if(request()->route()->getName()=='dashboard') {{ "active" }} @endif">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                Dashboard
                </p>
            </a>
            </li>

            @php
                 $services = get_user_nav(Auth::user()->id);
            
            @endphp
                  

            @foreach($services as $service)
                @if (count($service->user_have_service) > 0)
                    @if($service->name == 'Dialer')
                    <li class="nav-header">{{strtoupper($service->name)}}</li>

                    @foreach ($service->user_have_service as $user_have)
            
                    @php
                      $server_check = ceck_service_detail($user_have->user_have_service->id);
                    @endphp

                    @if ( $server_check)
                
                    <li class="nav-item @if($sub_menu == strtolower($user_have->user_have_service->service_nick_name)) {{ 'menu-open' }} @endif">
                        <a href="#" class="nav-link @if($sub_menu==strtolower($user_have->user_have_service->service_nick_name)) {{ 'active' }} @endif">
                        <i class="nav-icon fas fa-tty"></i>
                        <p>
                        {{$user_have->user_have_service->service_nick_name}}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('services.agents', ['service' => strtolower($service->name), 'organization_services_id' => $user_have->user_have_service->id]) }}" class="nav-link @if($sub_menu_main=='agents') {{ 'active' }} @endif">
                                <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        Agents 
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('services.campaigns',['service' => strtolower($service->name)]) }}" class="nav-link @if($sub_menu_main=='campaigns') {{ 'active' }} @endif">
                                <i class="nav-icon fas fa-bullhorn"></i>
                                    <p>
                                        Campaigns
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link @if($sub_menu_main=='lists') {{ 'active' }} @endif">
                                <i class="nav-icon fas fa-bars"></i>
                                    <p>
                                        Lists
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-reply nav-icon"></i>
                                    <p>Inbound</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                                <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-people-arrows nav-icon"></i>
                                    <p>Inbound Groups</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-sim-card nav-icon"></i>
                                    <p>DIDs</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-tape nav-icon"></i>
                                    <p>IVRs</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-filter"></i>
                                    <p>
                                        Filters
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-scroll"></i>
                                    <p>
                                        Scripts
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-user-shield nav-icon"></i>
                                    <p>Admin</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                                <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="far fa-clock nav-icon"></i>
                                    <p>Call Times</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-file-audio nav-icon"></i>
                                    <p>Audio Files</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-voicemail nav-icon"></i>
                                    <p>Voicemails</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-user-friends nav-icon"></i>
                                    <p>User Groups</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-layer-group nav-icon"></i>
                                    <p>Statuses</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="fas fa-receipt nav-icon"></i>
                                    <p>Reports</p>
                                    <i class="right fas fa-angle-left"></i>
                                </a>
                                <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-phone-alt nav-icon"></i>
                                    <p>Call Reports</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                    <i class="fas fa-user-clock nav-icon"></i>
                                    <p>Agent Reports</p>
                                    </a>
                                </li>
                                </ul>
                            </li>
                        </ul>
                    </li>

                    @endif

                    @endforeach
                
                @endif
                @endif
              

                @if($service->name == 'Automation')
                    @if (count($service->user_have_service) > 0)
                        <li class="nav-header">{{strtoupper($service->name)}}</li>

                        @foreach ($service->user_have_service as $user_have)
                        
                        <li class="nav-item @if($sub_menu == strtolower($user_have->user_have_service->service_nick_name)) {{ 'menu-open' }} @endif">
                            <a href="#" class="nav-link @if($sub_menu==strtolower($user_have->user_have_service->service_nick_name)) {{ 'active' }} @endif">
                            <i class="nav-icon fas fa-solid fa-robot"></i>
                            <p>
                            {{$user_have->user_have_service->service_nick_name}}
                                <i class="right fas fa-angle-left"></i>
                            </p>
                            </a>
                            
                        </li>
    
                        @endforeach
                    @endif
                 



                @endif


            @endforeach



            @if (!auth()->user()->hasRole('user'))
            <li class="nav-item @if($main_menu == 'administration') {{ 'menu-open' }} @endif">
                <a href="#" class="nav-link @if($main_menu=='administration') {{ 'active' }} @endif">
                    <i class="nav-icon fas fa-tools"></i>
                    <p>Administration
                        <i class="right fas fa-angle-left"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{ route('administration.users') }}" class="nav-link @if($sub_menu=='users') {{ 'active' }} @endif">
                        <i class="nav-icon fas fa-users"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                </ul>
            </li>
            @endif
            


        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>


@endif
