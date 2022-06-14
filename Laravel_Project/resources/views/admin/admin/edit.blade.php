@extends('admin.layouts.app')



@section('title', 'Edit Admin')


@section('content-header', 'Edit Admin')


@section('style')

    <!-- iCheck for checkboxes and radio inputs -->
    <link rel="stylesheet" href="{{ asset('template_admin/plugins/iCheck/all.css') }}">

@endsection


@section('content')

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.admin.update', ['admin' => $admin->id]) }}" method="post">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-12">
                        <div @class([
                                'form-group',
                                'has-error' => $errors->has('name'),
                            ])>
                            <label>Name <span style="color: red">*</span></label>
                            <input name="name" type="text" value="{{ old('name', $admin->user->name) }}"
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
                            <input name="email" type="email" value="{{ old('email', $admin->user->email) }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('email')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Main Admin</label><br>
                                    <input name="main_admin" type="checkbox" class="icheckbox_flat"
                                           @if(old('main_admin', $admin->is_main_admin)) checked @endif>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('role'),
                                ])>
                                    <label>Role <span style="color: red">*</span></label>
                                    <input name="role" type="text" value="{{ old('role', $admin->role) }}"
                                           placeholder="Enter ..." class="form-control" required>
                                    @error('role')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
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
                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Edit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
