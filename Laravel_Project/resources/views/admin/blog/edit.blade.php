@extends('admin.layouts.app')



@section('title', 'Edit Blog')


@section('content-header', 'Edit Blog')


@section('content')

    <form id="delete_images" action="{{ route('admin.blog.delete-images', ['blog' => $blog->id]) }}" method="post">
        @csrf
        @method('delete')
    </form>

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

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.blog.update', ['blog' => $blog->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Images</th>
                                <td>
                                    @forelse($blog->images as $image)
                                        <a href="{{ $blog->imagePath($image) }}" target="_blank">
                                            <img src="{{ $blog->imagePath($image) }}" alt="" height="150px"
                                                 style="margin-left: 5px; margin-top: 5px; border: 2px solid black">
                                        </a>
                                    @empty
                                        <p>There isn't any image.</p>
                                    @endforelse
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-8">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('title'),
                        ])>
                            <label>Title <span style="color: red">*</span></label>
                            <input name="title" type="text" value="{{ old('title', $blog->title) }}"
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
                            <input name="author" type="text" value="{{ old('author', $blog->author) }}"
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
                                      style="resize: vertical;" required>{{ old('context', $blog->context) }}</textarea>
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
                            @if($blog->images->isEmpty())
                                <label>Images</label>
                                <input name="images[]" type="file" multiple>
                                @error('images.*')
                                <span class="help-block">{{ $message }}</span>
                                @enderror
                            @else
                                <label style="visibility: hidden;">Image</label><br>
                                <input form="delete_images" type="submit" name="submit"
                                       class="btn btn-danger btn-sm" value="Delete Images">
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control select2">
                                <option style="color: darkgray;" value="none">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($category->id == old('category', $blog->category->id ?? [])) selected @endif>
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
                                            @if(in_array($tag->id, old('tags', $blogTagsId))) selected @endif>
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
                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Edit">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
