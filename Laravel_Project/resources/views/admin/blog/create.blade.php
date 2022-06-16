@extends('admin.layouts.app')



@section('title', 'Create Blog')


@section('content-header', 'Create Blog')


@section('content')

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.blog.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('title'),
                        ])>
                            <label>Title <span style="color: red">*</span></label>
                            <input name="title" type="text" value="{{ old('title') }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('title')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('author'),
                        ])>
                            <label>Author <span style="color: red">*</span></label>
                            <input name="author" type="text" value="{{ old('author') }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('author')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('context'),
                        ])>
                            <label>Context <span style="color: red">*</span></label>
                            <textarea name="context" class="form-control" rows="12" placeholder="Enter ..."
                                style="resize: vertical;" required>{{ old('context') }}</textarea>
                            @error('context')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('images.*'),
                        ])>
                            <label>Images</label>
                            <input name="images[]" type="file" multiple>
                            @error('images.*')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control">
                                <option style="color: darkgray;" value="none">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($category->id == old('category')) selected @endif>
                                        {{ $category->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Tags</label>
                            <select name="tags[]" class="form-control select2" multiple data-placeholder="None">
                                @foreach($tags as $tag)
                                    <option value="{{ $tag->id }}"
                                            @if(in_array($tag->id, old('tags', []))) selected @endif>
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
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
