@extends('admin.layouts.app')



@section('title', 'Coupon')


@section('content-header', 'Coupon')


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
                    <h3 class="box-title">Coupons</h3>
                </div>
                <div class="box-body table-responsive no-padding">
                    @if($coupons->isEmpty())
                        <h3 style="text-align: center">There isn't any coupon!</h3>
                    @else
                        <table class="table table-hover">
                            <thead>
                            @error('selections')
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
                                    <form id="selections" action="{{ route('admin.coupon.selections') }}" method="post">
                                        @csrf
                                        <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                    </form>
                                </th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th>Code</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($coupons as $coupon)
                                <tr>
                                    <th>{{ $index++ }}</th>
                                    <td><input form="selections" type="checkbox" name="coupons_id[{{ $coupon->id }}]"
                                               class="coupons"></td>
                                    <td>{{ $coupon->code }}</td>
                                    <td>{{ number_format($coupon->price, 2) }}$</td>
                                    <td style="padding-top: 5px;">
                                        <a href="{{ route('admin.coupon.edit', ['coupon' => $coupon->id]) }}"
                                           class="btn btn-success btn-sm" style="margin-top: 3px;">Edit</a>
                                        <form action="{{ route('admin.coupon.destroy', ['coupon' => $coupon->id]) }}"
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
                            {{ $coupons->onEachSide(1)->links('admin.layouts.pagination') }}
                        </center>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h1 class="box-title">Create Coupon</h1>
                </div>
                <div class="box-body">
                    <form action="{{ route('admin.coupon.store') }}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div @class([
                                    'form-group', 'row',
                                    'has-error' => $errors->has('code'),
                                ])>
                                    <label class="col-sm-2 control-label">Code</label>
                                    <div class="col-sm-10">
                                        <input name="code" type="text" value="{{ old('code') }}"
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
                                        <input name="price" type="number" step="0.01" value="{{ old('price') }}"
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
                                <input name="submit" type="submit" value="Create" class="btn btn-success pull-right">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')

    <script>
        // Check all
        document.getElementById('check_all').onclick = function () {
            for (let checkbox of document.getElementsByClassName('coupons'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
