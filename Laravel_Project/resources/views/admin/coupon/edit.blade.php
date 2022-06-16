@extends('admin.layouts.app')



@section('title', 'Edit Coupon')


@section('content-header', 'Edit Coupon')


@section('content')

    <div class="row">
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h1 class="box-title">Create Coupon</h1>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.coupon.update', ['coupon' => $coupon->id]) }}" method="post">
                        @csrf
                        @method('patch')
                        <div class="row">
                            <div class="col-md-12">
                                <div @class([
                                    'form-group', 'row',
                                    'has-error' => $errors->has('code'),
                                ])>
                                    <label class="col-sm-2 control-label">Code</label>
                                    <div class="col-sm-10">
                                        <input name="code" type="text" value="{{ old('code', $coupon->code) }}"
                                               placeholder="Enter ..." class="form-control" required>
                                        @error('code')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div @class([
                                        'form-group', 'row',
                                        'has-error' => $errors->has('price'),
                                    ])>
                                    <label class="col-sm-2 control-label">Price</label>
                                    <div class="col-sm-10">
                                        <input name="price" type="number" step="0.01" value="{{ old('price', $coupon->price) }}"
                                               placeholder="Enter ..." class="form-control" min="0" max="999999999.99" required>
                                        @error('price')
                                            <span class="help-block">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2 pull-right">
                                <input name="submit" type="submit" value="Edit" class="btn btn-success pull-right">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
