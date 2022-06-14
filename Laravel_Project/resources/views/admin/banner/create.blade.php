@extends('admin.layouts.app')



@section('title', 'Create Banner')


@section('content-header', 'Create Banner')


@section('content')

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.banner.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('image'),
                        ])>
                            <label>Images <span style="color: red">*</span></label>
                            <p>(Fit Dimensions: Horizontal=1170px, Vertical=475px)</p>
                            <input name="image" type="file" >
                            @error('image')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('first_header'),
                        ])>
                            <label>First Header <span style="color: red">*</span></label>
                            <input name="first_header" type="text" value="{{ old('first_header') }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('first_header')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('second_header'),
                        ])>
                            <label>Second Header <span style="color: red">*</span></label>
                            <input name="second_header" type="text" value="{{ old('second_header') }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('second_header')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('paragraph'),
                        ])>
                            <label>Paragraph <span style="color: red">*</span></label>
                            <textarea name="paragraph" class="form-control" rows="2" placeholder="Enter ..."
                                      required>{{ old('paragraph') }}</textarea>
                            @error('paragraph')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('link'),
                        ])>
                            <label>Link <span style="color: red">*</span></label>
                            <input name="link" type="text" value="{{ old('link') }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('link')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row" style="text-align: right; margin-top: 10px;">
                    <div class="col-md-12">
                        <div class="form-group">
                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Create">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
