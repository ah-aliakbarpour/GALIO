@extends('admin.layouts.app')



@section('title', 'Banners')


@section('content-header', 'Banners')


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
                <div class="box-body table-responsive no-padding">
                    @if($banners->isEmpty())
                        <h3 style="text-align: center">There isn't any banner!</h3>
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
                                <th  colspan="6">
                                    <form id="selections" action="{{ route('admin.banner.selections') }}" method="post">
                                        @csrf
                                        <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>image</th>
                                <th>First Header</th>
                                <th>Second Header</th>
                                <th>Paragraph</th>
                                <th>Link</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($banners as $banner)
                                <tr>
                                    <th>{{ $loop->iteration }}</th>
                                    <td><input form="selections" type="checkbox" name="banners_id[{{ $banner->id }}]" class="banners"></td>
                                    <td>
                                        <a href="{{ $banner->imagePath() }}" target="_blank">
                                            <img src="{{ $banner->imagePath() }}"
                                                 alt="Img" height="150px" style="border: 1px solid black">
                                        </a>
                                    </td>
                                    <td>{{ $banner->first_header }}</td>
                                    <td>{{ $banner->second_header }}</td>
                                    <td>{{ $banner->paragraph }}</td>
                                    <td title="{{ $banner->link }}"><a href="{{ $banner->link }}" target="_blank">Click</a></td>
                                    <td style="padding-top: 5px;">
                                        <a href="{{ route('admin.banner.show', ['banner' => $banner->id]) }}"
                                           class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                        <a href="{{ route('admin.banner.edit', ['banner' => $banner->id]) }}"
                                           class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                        <form action="{{ route('admin.banner.destroy', ['banner' => $banner->id]) }}"
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
            for (let checkbox of document.getElementsByClassName('banners'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
