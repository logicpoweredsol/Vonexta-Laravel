<style>All Users
    .bootstrap-switch.bootstrap-switch-wrapper.bootstrap-switch-animate{
        width: 80px !important;
    }
</style>


<div class="card">
    <div class="card-header">
      <h3 class="card-title">All Users</h3>

      <div class="card-tools">
        <!-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
          <i class="fas fa-minus"></i>
        </button> -->
        <a href="javascript:;" onclick="change_tab('add-org-user');" class="btn btn-sm btn-primary">
          <i class="fas fa-plus"></i> Add User
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
        <table id="usersDT" class="table table-striped table-hover vonexta-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($UserOrganization as $user)

                        <tr>
                            <td>
                                {{ $user->organization_user->name }}
                            </td>
                            <td>
                                {{ $user->organization_user->email }}
                            </td>
                            <td>

                            {{$user->organization_user->phone}}
                                
                            </td>
                        
                            <td>
                                
                                @if($user->organization_user->active==1)
                                {{-- <span class="badge badge-success"> <strong>Active</strong> </span> --}}
                                    <span class="text-success"> <strong>Active</strong> </span>
                                @else
                                <span class="text-danger"> <strong>Not Active</strong></span>
                                {{-- <span class="badge badge-danger"> <strong>Not Active</strong></span> --}}
                                @endif

                            </td>

                            @php
                                $roles = $user->organization_user->getRoleNames();
                            @endphp

                            <td>
                                @if ($user->organization_user->is_owner == 1)
                                    <span class="text-success"> <strong>Main</strong> </span> - {{ucfirst($roles[0])}}
                                @else
                                    {{ucfirst($roles[0])}}
                                @endif
                            </td>

                            <td>
                            <div class="btn-group">
                                    <button type="button" class="btn btn-default">Actions</button>
                                    <button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">
                                        <span class="sr-only">Toggle Dropdown</span>
                                    </button>
                                    <div class="dropdown-menu" role="menu">
                                    @if(Auth::user()->can('edit users'))
                                        <a class="dropdown-item" href="{{ url('organizations/user/edit/'.$user->organization_user->id .'/'. $user->organization_id) }}">Edit</a>
                                    @endif
                                    @if(Auth::user()->can('delete users') && $user->organization_user->is_owner != 1)
                                        <a class="dropdown-item btnDelete" data-id="{{ $user->id }}" href="#">Delete</a>
                                    @endif

                                    @if ($roles[0] == 'admin')
                                    <a class="dropdown-item Impersonate" data-id="" onclick="impersonate_modal({{ $user->organization_user->id }});" href="javascript:;">Impersonate</a>
                                    @endif

                                    </div>
                            </div>
                                <!-- @if(Auth::user()->can('edit users'))
                                    <a href="{{ url('organizations/user/edit/'.$user->organization_user->id .'/'. $user->organization_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i><a>
                                @endif
                                
                                @if(Auth::user()->can('delete users') && $user->organization_user->is_owner != 1)
                                    <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="{{ $user->id }}"><i class="fas fa-trash"></i><a>
                                @endif -->
                            </td>
                            
                        </tr>
                    @endforeach

            
            </tbody>
        </table>
    </div>


<!-- Modal -->
<div class="modal fade" id="Impersonate-Modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <!-- <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div> -->
      <div class="modal-body">
        <input type="hidden" readonly id="org_user_id">
        <div class="userauth-box" id="user_email_box">
            <div class="iconwrap">
                <span><i class="fas fa-lock"></i></span>
                <h2>User Authentication</h2>
            </div>
            <div class="optioncontent" >
                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <label class="btn btn-secondary">
                        <input type="radio" value="text" name="options" id="option1">
                        <div class="radiocontent">
                            <i class="fas fa-mobile-alt"></i>
                            <span>Text message</span>
                        </div>
                    </label>
                    <label class="btn btn-secondary">
                        <input type="radio" value="email" name="options" id="option2">
                        <div  class="radiocontent">
                            <i class="fas fa-envelope"></i>
                            <span>Email</span>
                        </div>
                    </label>
                </div>
            </div>
        </div>
        <form action="{{route('organizations.user.verified')}}" method="post" id="verify_form">
            @csrf
            <input type="hidden" name="user_idd" readonly id="user_idd">
            <div class="userauth-box codebox d-none" id="user_password_box">
                <div class="iconwrap">
                    <span><i class="fas fa-lock"></i></span>
                    <h2>Insert Code</h2>
                </div>
                <div class="codecontent-wrap">
                    <ul class="d-flex">
                        <li><input maxlength = "1" name="code_1" type="text"></li>
                        <li><input maxlength = "1" name="code_2" type="text"></li>
                        <li><input maxlength = "1" name="code_3" type="text"></li>
                        <li><input maxlength = "1" name="code_4" type="text"></li>
                        <li><input maxlength = "1" name="code_5" type="text"></li>
                        <li><input maxlength = "1" name="code_6" type="text"></li>
                    </ul>
                    <span class="digitleft"><em>6</em> Digits left</span>
                </div>
            </div>
        </form>
       
      </div>
      <div class="modal-footer">
         <button type="button" onclick="send_impersonation_email();" id="Send"  class="btn btn-primary">Send</button>
         <button type="button" onclick="verifoy();"  class="btn btn-primary d-none" id="Verify">Verify</button>
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Cancel</button>
      </div>
    </div>
  </div>
</div>
  </div>

    
