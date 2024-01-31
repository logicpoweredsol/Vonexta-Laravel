<form id="" action="{{route('organizations.user.store')}}" method="post" class="form-horizontal">
    @csrf
    <!-- Default box -->
    <input type="hidden" name="org_id" value="{{ $organization->id }}">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Add User</h3>

            <div class="card-tools">
                <a href="javascript:;" onclick="change_tab('org-user');" class="btn btn-sm btn-primary">
                Users List 
                </a>
            </div>

        </div>
        <div class="card-body">
            <div class="form-group row">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name')  }}" placeholder="Name of the user" @error('name') aria-invalid="true" @enderror>
                    <span class="error">
                    @error('name')
                        <label id="name-error" class="error invalid-feedback" for="name" style="display: inline-block;">{{ $message }}</label>
                    @enderror
                </span>
                </div>
            </div>
            <div class="form-group row">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email')  }}" placeholder="Email" @error('email') aria-invalid="true" @enderror>
                    <span class="error">
                        @error('email')
                            <label id="email-error" class="error invalid-feedback" for="email" style="display: inline-block;">{{ $message }}</label>
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label for="Phone" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control @error('Phone') is-invalid @enderror" id="Phone" name="Phone" value="{{ old('Phone')  }}" placeholder="Phone" @error('Phone') aria-invalid="true" @enderror>
                    <span class="error">
                        @error('Phone')
                            <label id="Phone-error" class="error invalid-feedback" for="Phone" style="display: inline-block;">{{ $message }}</label>
                        @enderror
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label for="password" class="col-sm-2 col-form-label">Password</label>
                <div class="col-sm-10">
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old('password')  }}" placeholder="Password"  @error('password') aria-invalid="true" @enderror>
                    <span class="error">
                        @error('password')
                        <label id="password-error" class="error invalid-feedback" for="password" style="display: inline-block;">{{ $message }}</label>
                    @enderror
                    </span>
                </div>
            </div>
            <div class="form-group row">
                <label for="role" class="col-sm-2 col-form-label">Role</label>
                <div class="col-sm-10">
                    <select class="form-control" id="role" name="role" value="{{ old('role')  }}">
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                    <span class="error"></span>
                </div>
            </div>
            <div class="form-group row">
                <label for="Services_row" class="col-sm-2 col-form-label">Services</label>
                <div class="col-sm-10">
                    <div class="row" id="Services_row" @error('Services') aria-invalid="true" @enderror>
                        @foreach($organizationServices as $Services)

                     
                        @php
                            $check_valied = true;
                            if($Services->name == 'Dialer'){
                                $check_valied =ceck_service_detail($Services->pivot->id);
                            }

                        @endphp
                         @if ($check_valied)

                        <div class="col-sm-6 mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="{{ str_replace(" ", "_", $Services->pivot->service_nick_name) }}" name="Services[]" data-bootstrap-switch data-off-color="danger" data-on-color="success" checked value="{{ $Services->pivot->id }}">
                                <label class="form-check-label" for="{{ str_replace(" ", "_", $Services->pivot->service_nick_name) }}"> {{ ucwords($Services->pivot->service_nick_name)}}</label>
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
        <!-- /.card-body -->
        <div class="card-footer">
            <button type="submit" class="btn btn-primary float-right">Save</button>
            <a href="javascript:;" onclick="change_tab('org-user');"  class="btn btn-default">Cancel</a>
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->
</form>

@push('scripts')
    <!-- Bootstrap Switch -->
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>
    <!-- jquery-validation -->
    <script src="{{ asset('plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-validation/additional-methods.min.js') }}"></script>
    {{-- <script src="{{ asset('views/system/users/add.js') }}"></script> --}}
    <script src="{{ asset('views/system/users/common.js') }}"></script>
@endpush
