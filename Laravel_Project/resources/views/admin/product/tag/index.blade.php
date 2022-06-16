@extends('admin.layouts.app')



@section('title', 'Product Tags')


@section('content-header', 'Product Tags')


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
        <div class="col-md-8">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h3 class="box-title">Tags</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    @if($tags->isEmpty())
                        <h3 style="text-align: center">There isn't any tag!</h3>
                    @else
                        <table class="table table-hover">
                            <thead>@error('selections')
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
                                <th colspan="9">
                                    <form id="selections" action="{{ route('admin.product.tag.selections') }}" method="post">
                                        @csrf
                                        <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>title</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($tags as $tag)
                                <tr>
                                    <th>{{ $index++ }}</th>
                                    <td><input form="selections" type="checkbox" name="tags_id[{{ $tag->id }}]"
                                               class="tags"></td>
                                    <td>{{ $tag->title }}</td>
                                    <td style="padding-top: 5px;">
                                        <a href="{{ route('admin.product.tag.edit', ['tag' => $tag->id]) }}"
                                            class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                        <form action="{{ route('admin.product.tag.destroy', ['tag' => $tag->id]) }}"
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
                            {{ $tags->onEachSide(1)->links('admin.layouts.pagination') }}
                        </center>
                    @endif
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h1 class="box-title">Create Tag</h1>
                </div>
                <div class="box-body">
                    <div class="row">
                        <form action="{{ route('admin.product.tag.store') }}" method="post">
                            @csrf
                            <div class="col-md-10">
                                <div @class([
                                    'form-group', 'row',
                                    'has-error' => $errors->has('title'),
                                ])>
                                    <label class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input name="title" type="text" value="{{ old('title') }}"
                                               placeholder="Enter ..." class="form-control" required>
                                        @error('title')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input name="submit" type="submit" value="Create" class="btn btn-success pull-right">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('script')

    <script>
        // Check all
        document.getElementById('check_all').onclick = function () {
            for (let checkbox of document.getElementsByClassName('tags'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
