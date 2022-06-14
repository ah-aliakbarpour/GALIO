@extends('admin.layouts.app')



@section('title', 'Edit Blog Tag')


@section('content-header', 'Edit Blog Tag')


@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-body">
                    <div class="row">
                        <form action="{{ route('admin.blog.tag.update', ['tag' => $tag->id]) }}" method="post">
                            @csrf
                            @method('patch')
                            <div class="col-md-10">
                                <div @class([
                                    'form-group', 'row',
                                    'has-error' => $errors->has('title'),
                                ])>
                                    <label class="col-sm-2 control-label">Title</label>
                                    <div class="col-sm-10">
                                        <input name="title" type="text" value="{{ old('title', $tag->title) }}"
                                               class="form-control" required>
                                        @error('title')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <input name="submit" type="submit" value="Edit" class="btn btn-success pull-right">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
