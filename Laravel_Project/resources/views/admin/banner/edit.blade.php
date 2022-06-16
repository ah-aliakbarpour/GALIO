@extends('admin.layouts.app')



@section('title', 'Edit Banner')


@section('content-header', 'Edit Banner')


@section('content')

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.banner.update', ['banner' => $banner->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Image</th>
                                <td>
                                    <a href="{{ $banner->imagePath() }}" target="_blank">
                                        <img src="{{ $banner->imagePath() }}" alt="" height="150px"
                                             style="margin-left: 5px; margin-top: 5px; border: 2px solid black">
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <b>Note: </b>To delete this image, upload a new one.
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('image'),
                        ])>
                            <label>Images</span></label>
                            <input name="image" type="file">
                            @error('image')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('first_header'),
                        ])>
                            <label>First Header <span style="color: red">*</span></label>
                            <input name="first_header" type="text" value="{{ old('first_header', $banner->first_header) }}"
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
                            <input name="second_header" type="text" value="{{ old('second_header', $banner->second_header) }}"
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
                                required>{{ old('paragraph', $banner->paragraph) }}</textarea>
                            @error('paragraph')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('link'),
                        ])>
                            <label>Link <span style="color: red">*</span></label>
                            <input name="link" type="text" value="{{ old('link', $banner->link) }}"
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
                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Edit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
