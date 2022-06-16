@extends('admin.layouts.app')



@section('title', 'Comment Details')


@section('content-header', 'Comment Details')


@section('content')

    <div class="box box-primary">
        <!-- /.box-header -->
        <div class="box-body">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Blog Title</th>
                            <td>
                                <a href="{{ route('admin.blog.show', ['blog' => $comment->blog->id]) }}" target="_blank">
                                    {{ $comment->blog->title }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <th>Author Name</th>
                            <td>{{ $comment->author_name }}</td>
                        </tr>
                        <tr>
                            <th>Author Email</th>
                            <td>{{ $comment->author_email }}</td>
                        </tr>
                        <tr>
                            <th>Created At</th>
                            <td>{{ $comment->created_at->format('Y/m/d H:i:s') }}</td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            @if ($comment->approved)
                                <td><span class="label label-success">Approved</span></td>
                            @else
                                <td><span class="label label-warning">Pending</span></td>
                            @endif
                        </tr>
                        @if ($comment->approved)

                            <tr>
                                <th>Approved At</th>
                                <td>{{ date('Y/m/d H:i:s', strtotime($comment->approved_at)) }}</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <th>Context</th>
                            <td>
                                {{ $comment->context }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <span class="pull-right">
                        @if ($comment->approved)
                            <form action="{{ route('admin.blog.comment.destroy', ['comment' => $comment->id]) }}"
                                  method="post" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        @else
                            <form action="{{ route('admin.blog.comment.approve', ['comment' => $comment->id]) }}"
                                  method="post" style="display: inline-block;">
                                @csrf
                                <input type="submit" name="submit" value=Approve class="btn btn-success btn-sm">
                            </form>
                            <form action="{{ route('admin.blog.comment.destroy', ['comment' => $comment->id]) }}"
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
