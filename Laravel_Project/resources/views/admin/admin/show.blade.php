@extends('admin.layouts.app')



@section('title', 'Admin Details')


@section('content-header', 'Admin Details')


@section('content')

    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Name</th>
                            <td>{{ $admin->user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $admin->user->email }}</td>
                        </tr>
                        <tr>
                            <th>Email Verified At</th>
                            <td>{!! $admin->user->email_verified_at ?? '&#9866;' !!}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $admin->user->created_at->format('Y/m/d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Updated At</th>
                            <td>{{ $admin->user->updated_at->format('Y/m/d H:i:s') }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Main Admin</th>
                                <td>
                                    @if($admin->is_main_admin)
                                        <span class="label label-success">YES</span>
                                    @else
                                        <span class="label label-danger">NO</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>{{ $admin->role }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="{{ route('admin.admin.edit', ['admin' => $admin->id]) }}"
                           class="btn btn-success btn-sm">Edit</a>
                        <form action="{{ route('admin.admin.destroy', ['admin' => $admin->id]) }}"
                              method="post" style="display: inline-block;">
                            @csrf
                            @method('delete')
                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
