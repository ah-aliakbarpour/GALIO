@extends('admin.layouts.app')



@section('title', 'Create Product')


@section('content-header', 'Create Product')


@section('content')

    <div class="box box-success">
        <div class="box-body">
            <form action="{{ route('admin.product.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-7">
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
                        <div class="form-group">
                            <label>Introduction</label>
                            <textarea name="introduction"  placeholder="Enter ..." rows="4"
                                      class="form-control" style="resize: vertical;"
                            >{{ old('introduction') }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description"  placeholder="Enter ..." rows="11"
                                      class="form-control" style="resize: vertical;"
                            >{{ old('description') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-5">
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
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('stock'),
                        ])>
                            <label>Stock <span style="color: red">*</span></label>
                            <input name="stock" type="number" value="{{ old('stock') }}" placeholder="Enter ..."
                                   class="form-control" min="0" max="1000000000" required>
                            @error('stock')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div @class([
                                'col-md-8', 'form-group',
                                'has-error' => $errors->has('price'),
                            ])>
                                <label>Price($) <span style="color: red">*</span></label>
                                <input name="price" type="number" step="0.01" value="{{ old('price') }}" placeholder="Enter ..."
                                       class="form-control" min="0" max="999999999.99" required>
                                @error('price')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                            <div @class([
                                'col-md-4', 'form-group',
                                'has-error' => $errors->has('discount'),
                            ])>
                                <label>Discount(%)</label>
                                <input name="discount" type="number" value="{{ old('discount') }}"
                                       placeholder="Enter ..." class="form-control">
                                @error('discount')
                                    <span class="help-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Category</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="category" class="form-control select2" style="width: 100%">
                                        <option value="none" selected>None</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @if($category->id == old('category')) selected @endif>
                                                {{ $category->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Tags</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <select name="tags[]" class="form-control select2" multiple data-placeholder="None" style="width: 100%">
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
                        <div class="form-group">
                            <label style="margin-bottom: 0px;">Information</label>
                            <table class="table" style="margin-bottom: 12px;">
                                <thead>
                                <tr style="margin-bottom: 0;">
                                    <td class="col-md-6">Key</td>
                                    <td class="col-md-6">Value</td>
                                </tr>
                                </thead>
                                <tbody id="tbody">
                                    <script>
                                        let row = 0;
                                    </script>
                                    @php
                                        $oldInformation = old('information');
                                    @endphp
                                    @for($i = 0;$oldInformation && $i < count($oldInformation['keys']); $i++)
                                        <script>
                                            row += 1;
                                        </script>
                                        <tr id="information_{{ $i + 1 }}">
                                            <td @class([
                                                'col-md-6',
                                                'form-group',
                                                'has-error' => $errors->has("information.keys.{$i}"),
                                            ])>
                                                <input type="text" class="form-control"  name="information[keys][]"
                                                       value="{{ $oldInformation['keys'][$i] }}" placeholder="Enter ..." required>
                                                @error("information.keys.{$i}")
                                                    <span class="help-block">This field can't be empty.</span>
                                                @enderror
                                            </td>
                                            <td @class([
                                                'col-md-6',
                                                'form-group',
                                                'has-error' => $errors->has("information.values.{$i}"),
                                            ])>
                                                <input type="text" class="form-control"  name="information[values][]"
                                                       value="{{ $oldInformation['values'][$i] }}" placeholder="Enter ..." required>
                                                @error("information.values.{$i}")
                                                    <span class="help-block">This field can't be empty.</span>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                            <p id="add_btn" class="btn btn-primary btn-xs" style="display: inline;margin-top: 0; margin-left: 10px;">Add</p>
                            <p id="delete_btn" class="btn btn-danger btn-xs" style="display: none;margin-top: 0;">Delete</p>
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


@section('script')

    <script>
        // Key-Value of information | If any field exists show the delete button
        if (row > 0)
            document.getElementById('delete_btn').style.display = 'inline';

        // Key-Value of information | Add button
        document.getElementById('add_btn').onclick = function () {
            row += 1;

            if (row > 0)
                document.getElementById('delete_btn').style.display = 'inline';

            let tr = document.createElement('tr');
            tr.setAttribute('id', 'information_' + row);
            tr.innerHTML =
                '<td class="col-md-6 form-group">' +
                    '<input type="text" class="form-control"  name="information[keys][]" placeholder="Enter ..." required>' +
                '</td>' +
                '<td class="col-md-6 form-group">' +
                    '<input type="text" class="form-control"  name="information[values][]" placeholder="Enter ..." required>' +
                '</td>';
            document.getElementById('tbody').appendChild(tr);
        }

        // Key-Value of information | Delete button
        document.getElementById('delete_btn').onclick = function () {
            document.getElementById('information_' + row).remove();

            row -= 1;

            if (row < 1)
                document.getElementById('delete_btn').style.display = 'none';
        }
    </script>

@endsection
