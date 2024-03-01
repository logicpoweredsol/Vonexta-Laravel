@if(session()->has('org-user-edit'))

@php
    $edit_data = session('org-user-edit');


@endphp

    <form id="" action="{{route('organization.user.update')}}" method="post" class="form-horizontal">
        @csrf

        <input type="hidden" name="user_id" value="{{ $edit_data['user']->id }}">
        <input type="hidden" name="org_id" value="{{ $edit_data['organization']->id }}">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Update User</h3>
                <div class="card-tools">
                    <a href="javascript:;" onclick="change_tab('org-user');" class="btn btn-sm btn-primary">
                    Users List
                    </a>
                </div>

            </div>
            <div class="card-body">

                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') ?? $edit_data['user']->name }}" placeholder="Name of the user" @error('name') aria-invalid="true" @enderror>
                            <span class="error">
                                @error('name')
                                    <label id="name-error" class="error invalid-feedback" for="name" style="display: inline-block;">{{ $message }}</label>
                                @enderror
                            </span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') ?? $edit_data['user']->email  }}"  placeholder="Email" @error('email') aria-invalid="true" @enderror>
                            @error('email')
                                <label id="email-error" class="error invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                            </span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" value="{{$edit_data['user']->phone}}"  placeholder="Phone" @error('Phone') aria-invalid="true" @enderror>
                            @error('email')
                                <label id="Phone-error" class="error invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                        </span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" value="{{ old('password')  }}" placeholder="Password">
                            <span class="error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-control" id="role" name="role" value="{{ old('role')  }}">
                                <option @if( $edit_data['userRoles'][0] == 'admin') {{ "selected" }} @endif  value="admin">Admin</option>
                                <option @if($edit_data['userRoles'][0] == 'user' ) {{ "selected" }} @endif   value="user">User</option>
                            </select>
                            <span class="error"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="active" class="form-label">Active</label>
                            <select class="form-control" id="active" name="active">
                                <option value="1" @if($edit_data['user']->active==1) {{ "selected" }} @endif>Yes</option>
                                <option value="0" @if($edit_data['user']->active==0) {{ "selected" }} @endif>No</option>
                            </select>
                            <span class="error"></span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-12 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label for="Services_row" class="form-label">Services</label>
                            <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>
                            @foreach($edit_data['organizationServices'] as $Services)





                            @php
                                $check_valied = true;
                                if($Services->name == 'Dialer'){
                                    $check_valied = ceck_service_detail($Services->pivot->id);
                                }
                            @endphp


                         @if ($check_valied)

                            <div class="col-sm-4 mb-3">
                                <div class="form-check p-0">
                                    <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->pivot->id) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success"  @if (in_array($Services->pivot->id, $edit_data['user_have_service'])) checked @endif value="{{$Services->pivot->id }}">
                                    <label class="form-check-label" for="{{ str_replace(" ", "_", $Services->pivot->id) }}"> {{ ucwords($Services->pivot->service_nick_name)}}</label>
                                </div>
                            </div>

                            @endif

                            @endforeach
                        </div>
                        <span class="error">
                            @error('Services')
                                <label id="Services-error" class="error invalid-feedback" for="Services_row" style="display: inline-block;">{{ $message }}</label>
                            @enderror
                        </span>
                        </div>
                    </div>

                </div>

                {{-- <input type="hidden" name="close_service[]" id="close_service"> --}}


            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-primary float-right">Save</button>
                <a href="javascript:;" onclick="change_tab('org-user');"  class="btn btn-default">Cancel</a>
            </div>
            <!-- /.card-footer-->
        </div>
        <!-- /.card -->
    </form>

@endif



