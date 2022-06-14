@extends('admin.layouts.app')



@section('title', 'Advertisements')


@section('content-header', 'Advertisements')


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
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h1 class="box-title">Position</h1>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <a href="{{ asset('template_admin/dist/img/advertisements_position.png') }}" target="_blank">
                                <img src="{{ asset('template_admin/dist/img/advertisements_position.png') }}" alt="" class="img-responsive">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-danger">
                <div class="box-header with-border">
                    <h1 class="box-title">Advertisements List</h1>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>Position</th>
                            <th>Image</th>
                            <th>Link</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $fitDimensions = [
                                1 => '(Fit Dimensions: Horizontal=270px, Vertical=355px)',
                                2 => '(Fit Dimensions: Horizontal=495px, Vertical=171px)',
                                3 => '(Fit Dimensions: Horizontal=495px, Vertical=171px)',
                                4 => '(Fit Dimensions: Horizontal=370px, Vertical=355px)',
                                5 => '(Fit Dimensions: Horizontal=870px, Vertical=197px)',
                                6 => '(Fit Dimensions: Horizontal=600px, Vertical=849px)',
                            ]
                        @endphp
                        @for($i = 1; $i <= 6; $i++)
                            <tr>
                                @if($advertisements[$i] == null)
                                    <th>{{ $i }} - Not Exist!</th>
                                    <form id="create_{{ $i }}" action="{{ route('admin.advertisement.store') }}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input name="position" type="hidden" value="{{ $i }}">
                                        <td @class([
                                                'form-group',
                                                'has-error' => $errors->has('image_' . $i),
                                            ])>
                                            <p>{{ $fitDimensions[$i] }}</p>
                                            <input name="image_{{ $i }}" type="file">
                                            @error('image_' . $i)
                                            <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td @class([
                                                'form-group',
                                                'has-error' => $errors->has('link_' . $i),
                                            ])>
                                            <input name="link_{{ $i }}" type="text" value="{{ old('link_' . $i) }}"
                                                   placeholder="Enter ..." class="form-control">
                                            @error('link_' . $i)
                                            <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </td>
                                        <td>
                                            <input type="submit" name="submit" value="Create" class="btn btn-success btn-sm">
                                        </td>
                                    </form>
                                @else
                                    <th>{{ $i }}</th>
                                    <form id="edit_{{ $i }}" action="{{ route('admin.advertisement.update', ['advertisement' => $advertisements[$i]->id]) }}"
                                          method="post" enctype="multipart/form-data">
                                        @csrf
                                        @method('patch')
                                        <input name="position" type="hidden" value="{{ $i }}">
                                        <td>
                                            <div @class([
                                                'form-group',
                                                'has-error' => $errors->has('image_' . $i),
                                            ])>
                                                <p>(Fit Dimensions: Horizontal=1170px, Vertical=475px)</p>
                                                <input name="image_{{ $i }}" type="file">
                                                @error('image_' . $i)
                                                    <span class="help-block">{{ $message }}</span>
                                                @enderror
                                            </div>
                                            <a href="{{ $advertisements[$i]->imagePath() }}" target="_blank">
                                                <img src="{{ $advertisements[$i]->imagePath() }}"  alt="sorrry"
                                                     height="150px" style="border: 1px solid black">
                                            </a>
                                        </td>
                                        <td>
                                        <div @class([
                                            'form-group',
                                            'has-error' => $errors->has('link_' . $i),
                                        ])>
                                            <input name="link_{{ $i }}" type="text" value="{{ old('link_' . $i, $advertisements[$i]->link) }}"
                                                   placeholder="Enter ..." class="form-control">
                                            @error('link_' . $i)
                                                <span class="help-block">{{ $message }}</span>
                                            @enderror
                                        </div>
                                            <a title="{{ $advertisements[$i]->link }}" href="{{ $advertisements[$i]->link }}" target="_blank">Click</a>
                                        </td>
                                    </form>
                                    <td style="padding-top: 5px;">
                                        <input form="edit_{{ $i }}" type="submit" name="submit" value="Edit" class="btn btn-success btn-sm" style="margin-top: 3px;">
                                        <form action="{{ route('admin.advertisement.destroy', ['advertisement' => $advertisements[$i]->id]) }}"
                                              method="post" style="display: inline-block;">
                                            @csrf
                                            @method('delete')
                                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm" style="margin-top: 3px;">
                                        </form>
                                    </td>
                                @endif
                            </tr>
                        @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
