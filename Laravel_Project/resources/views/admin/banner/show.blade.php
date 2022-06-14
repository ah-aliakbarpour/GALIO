@extends('admin.layouts.app')



@section('title', 'Banner Details')


@section('content-header', 'Banner Details')


@section('content')

    <div class="box box-primary">
        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Images</th>
                            <td>
                                <a href="{{ $banner->imagePath() }}" target="_blank">
                                    <img src="{{ $banner->imagePath() }}" class="img-responsive">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>First Header</th>
                            <td>{{ $banner->first_header }}</td>
                        </tr>
                        <tr>
                            <th>Second Header</th>
                            <td>{{ $banner->second_header }}</td>
                        </tr>
                        <tr>
                            <th>Paragraph</th>
                            <td>{{ $banner->paragraph }}</td>
                        </tr>
                        <tr>
                            <th>Link</th>
                            <td><a href="ww.w3schools.com/" target="_blank">{{ $banner->link }}</a></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-right">
                        <a href="{{ route('admin.banner.edit', ['banner' => $banner->id]) }}"
                           class="btn btn-success btn-sm">Edit</a>
                        <form action="{{ route('admin.banner.destroy', ['banner' => $banner->id]) }}" method="post" style="display: inline-block;">
                            @csrf
                            @method('delete')
                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </span>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>

@endsection
