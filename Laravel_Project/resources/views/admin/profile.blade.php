@extends('admin.layouts.app')



@section('title', 'Profile')


@section('content-header', 'Profile')


@section('content')

    <!-- Alert -->
    @if(Session()->exists('alert'))
        <div class="row">
            <div class="col-md-12">
                <p class="callout callout-{{ Session()->get('alert')['type'] }}">
                    {{ Session()->get('alert')['massage'] }}
                </p>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Details</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.profile.save-changes') }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-12">
                                <div @class([
                                        'form-group',
                                        'has-error' => $errors->has('name'),
                                    ])>
                                    <label>Name <span style="color: red">*</span></label>
                                    <input name="name" type="text" value="{{ old('name', $user->name) }}"
                                           placeholder="Enter ..." class="form-control" required>
                                    @error('name')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div @class([
                                        'form-group',
                                        'has-error' => $errors->has('email'),
                                    ])>
                                    <label>Email <span style="color: red">*</span></label>
                                    <input name="email" type="text" value="{{ old('email', $user->email) }}"
                                           placeholder="Enter ..." class="form-control" required disabled>
                                    @error('email')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row" style="text-align: right; margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success btn-lg" name="submit" value="Save Changes">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">Change Password</h3>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.profile.change-password') }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-12">
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('current_password'),
                                ])>
                                    <label>Current Password <span style="color: red">*</span></label>
                                    <input name="current_password" type="password"
                                           placeholder="Enter ..." class="form-control" required>
                                    @error('current_password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('password'),
                                ])>
                                    <label>Password <span style="color: red">*</span></label>
                                    <input name="password" type="password"
                                           placeholder="Enter ..." class="form-control" required>
                                    @error('password')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('password_confirmation'),
                                ])>
                                    <label>Confirm Password <span style="color: red">*</span></label>
                                    <input name="password_confirmation" type="password"
                                           placeholder="Enter ..." class="form-control" required>
                                    @error('password_confirmation')
                                        <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!-- /.row -->
                        <div class="row" style="text-align: right; margin-top: 10px;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="submit" class="btn btn-success btn-lg" name="submit" value="Change Password">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
