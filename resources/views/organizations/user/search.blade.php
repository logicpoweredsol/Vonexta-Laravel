<style>
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
        <table id="usersDT" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    {{-- <th>#</th> --}}
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Role</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

                @foreach($UserOrganization as $user)

                        <tr>
                            {{-- <td>
                                {{ $loop->index + 1 }}
                            </td> --}}
                            <td>
                                {{ $user->organization_user->name }}
                            </td>
                            <td>
                                {{ $user->organization_user->email }}
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
                                @if(Auth::user()->can('edit users'))
                                    <a href="{{ url('organizations/user/edit/'.$user->organization_user->id .'/'. $user->organization_id) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i><a>
                                @endif
                                
                                @if(Auth::user()->can('delete users') && $user->organization_user->is_owner != 1)
                                    <a href="#" class="btn btn-sm btn-danger btnDelete" data-id="{{ $user->id }}"><i class="fas fa-trash"></i><a>
                                @endif
                            </td>
                            
                        </tr>
                    @endforeach

            
            </tbody>
        </table>
    </div>
  </div>