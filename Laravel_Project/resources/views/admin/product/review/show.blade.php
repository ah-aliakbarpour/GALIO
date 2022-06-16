@extends('admin.layouts.app')



@section('title', 'Review Details')


@section('content-header', 'Review Details')


@section('content')

    <div class="box box-primary">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Product Title</th>
                            <td>
                                <a href="{{ route('admin.product.show', ['product' => $review->product->id]) }}" target="_blank">
                                    {{ $review->product->title }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Author Name</th>
                            <td>{{ $review->author_name }}</td>
                        </tr>
                        <tr>
                            <th>Author Email</th>
                            <td>{{ $review->author_email }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $review->created_at->format('Y/m/d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            @if ($review->approved)
                                <td><span class="label label-success">Approved</span></td>
                            @else
                                <td><span class="label label-warning">Pending</span></td>
                            @endif
                        </tr>
                        @if ($review->approved)

                            <tr>
                                <th>Approved At</th>
                                <td>{{ date('Y/m/d H:i:s', strtotime($review->approved_at)) }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Rating</th>
                            <td><span style="color: orange;" class="fa fa-star"></span> {{ $review->rating }}</td>
                        </tr>
                        <tr>
                            <th>Context</th>
                            <td>
                                {{ $review->context }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-right">
                        @if ($review->approved)
                            <form action="{{ route('admin.product.review.destroy', ['review' => $review->id]) }}"
                                  method="post" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        @else
                            <form action="{{ route('admin.product.review.approve', ['review' => $review->id]) }}"
                                  method="post" style="display: inline-block;">
                                @csrf
                                <input type="submit" name="submit" value=Approve class="btn btn-success btn-sm">
                            </form>
                            <form action="{{ route('admin.product.review.destroy', ['review' => $review->id]) }}"
                                  method="post" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

@endsection
