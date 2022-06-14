@extends('admin.layouts.app')



@section('title', 'Admins')


@section('content-header', 'Admins')


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
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Admins</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    @if($admins->isEmpty())
                        <h3 style="text-align: center">There isn't any admin!</h3>
                    @else
                        <table class="table table-hover">
                            <thead>
                            @error('selections')
                            <br>
                            <tr class="row">
                                <div class="col-md-12">
                                    <div class="callout callout-danger">
                                        <p>{{ $message }}</p>
                                    </div>
                                </div>
                            </tr>
                            @enderror
                            <tr>
                                <th></th>
                                <th><input type="checkbox" id="check_all"></th>
                                <th>
                                    <form id="selections" action="{{ route('admin.admin.selections') }}" method="post">
                                        @csrf
                                        <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </th>
                                <td colspan="4">
                                    <center>Showing {{ $admins->firstItem() }}–{{ $admins->lastItem() }} Of {{ $admins->total() }} Results</center>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Main Admin</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $index = $admins->firstItem();
                            @endphp
                            @foreach($admins as $admin)
                                <tr>
                                    <th>{{ $index++ }}</th>
                                    <td><input form="selections" type="checkbox" name="admins_id[{{ $admin->id }}]" class="admins"></td>
                                    <td>{{ $admin->user->name }}</td>
                                    <td>{{ $admin->user->email }}</td>
                                    <td>{{ $admin->role }}</td>
                                    <td>
                                        @if($admin->is_main_admin)
                                            <span class="label label-success">YES</span>
                                        @else
                                            <span class="label label-danger">NO</span>
                                        @endif
                                    </td>
                                    <td style="padding-top: 5px;">
                                        <a href="{{ route('admin.admin.show', ['admin' => $admin->id]) }}"
                                           class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                        <a href="{{ route('admin.admin.edit', ['admin' => $admin->id]) }}"
                                           class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                        <form action="{{ route('admin.admin.destroy', ['admin' => $admin->id]) }}"
                                              method="post" style="display: inline-block;">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm" style="margin-top: 3px;">
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <center style="margin-inline: 10px">
                            {{ $admins->onEachSide(1)->links('admin.layouts.pagination') }}
                        </center>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>

@endsection


@section('script')

    <script>
        // Check all
        document.getElementById('check_all').onclick = function () {
            for (let checkbox of document.getElementsByClassName('admins'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
