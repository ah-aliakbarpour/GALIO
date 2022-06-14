@extends('admin.layouts.app')



@section('title', 'Edit Product')


@section('content-header', 'Edit Product')


@section('content')

    <form id="delete_images" action="{{ route('admin.product.delete-images', ['product' => $product->id]) }}" method="post">
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
            <form action="{{ route('admin.product.update', ['product' => $product->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('patch')
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-hover">
                            <tbody>
                            <tr>
                                <th>Images</th>
                                <td>
                                    @forelse($product->images as $image)
                                        <a href="{{ $product->imagePath($image) }}" target="_blank">
                                            <img src="{{ $product->imagePath($image) }}" alt="" height="150px"
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
                    <div class="col-md-7">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('title'),
                        ])>
                            <label>Title <span style="color: red">*</span></label>
                            <input name="title" type="text" value="{{ old('title', $product->title) }}"
                                   placeholder="Enter ..." class="form-control" required>
                            @error('title')
                                <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Introduction</label>
                            <textarea name="introduction"  placeholder="Enter ..." rows="4"
                                      class="form-control" style="resize: vertical;"
                            >{{ old('introduction', $product->introduction) }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <textarea name="description"  placeholder="Enter ..." rows="11"
                                      class="form-control" style="resize: vertical;"
                            >{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('images.*'),
                        ])>
                            @if($product->images->isEmpty())
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
                        <div @class([
                            'form-group',
                            'has-error' => $errors->has('stock'),
                        ])>
                            <label>Stock <span style="color: red">*</span></label>
                            <input name="stock" type="number" value="{{ old('stock', $product->stock) }}" placeholder="Enter ..."
                                   class="form-control" min="0" max="1000000000" required>
                            @error('stock')
                            <span class="help-block">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('price'),
                                ])>
                                    <label>Price($) <span style="color: red">*</span></label>
                                    <input name="price" type="number" step="0.01" value="{{ old('price', $product->price) }}"
                                           placeholder="Enter ..." class="form-control" min="0" max="999999999.99" required>
                                    @error('price')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div @class([
                                    'form-group',
                                    'has-error' => $errors->has('discount'),
                                ])>
                                    <label>Discount(%)</label>
                                    <input name="discount" type="number" value="{{ old('discount', $product->discount) }}"
                                           placeholder="Enter ..." class="form-control">
                                    @error('discount')
                                    <span class="help-block">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category" class="form-control select2">
                                <option style="color: darkgray;" value="none">None</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            @if($category->id == old('category', $product->category->id ?? [])) selected @endif>
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
                                            @if(in_array($tag->id, old('tags', $productTagsId))) selected @endif>
                                        {{ $tag->title }}
                                    </option>
                                @endforeach
                            </select>
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
                                @if($oldInformation)
                                    @for($i = 0;$i < count($oldInformation['keys']); $i++)
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
                                @else
                                    @foreach($product->information as $i => $information)
                                        <script>
                                            row += 1;
                                        </script>
                                        <tr id="information_{{ $i + 1 }}">
                                            <td class="col-md-6 form-group">
                                                <input type="text" class="form-control"  name="information[keys][]"
                                                       value="{{ $information->key }}" placeholder="Enter ..." required>
                                            </td>
                                            <td class="col-md-6 form-group">
                                                <input type="text" class="form-control"  name="information[values][]"
                                                       value="{{ $information->value }}" placeholder="Enter ..." required>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
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
                            <input type="submit" class="btn btn-success btn-lg" name="submit" value="Edit">
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
                '<td class="col-md-6">' +
                    '<input type="text" class="form-control"  name="information[keys][]" placeholder="Enter ..." required>' +
                '</td>' +
                '<td class="col-md-6">' +
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
