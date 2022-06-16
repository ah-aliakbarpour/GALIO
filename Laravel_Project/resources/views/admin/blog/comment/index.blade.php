@extends('admin.layouts.app')



@section('title', 'Comments')


@section('content-header', 'Comments')


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

    <div class="box box-warning">
        <div class="box-header with-border">
            <h3 class="box-title">Pending Comments</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            @if($pendingComments->isEmpty())
                <h3 style="text-align: center">There isn't any comment!</h3>
            @else
                <table class="table table-hover">
                    <thead>
                    @error('selections_pending')
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
                        <th><input type="checkbox" id="check_all_pending"></th>
                        <th colspan="9">
                            <form id="selections_pending" action="{{ route('admin.blog.comment.selections') }}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="pending">
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                                <input type="submit" name="submit" value="Approve" class="btn btn-success btn-sm">
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Blog Title</th>
                        <th>Context</th>
                        <th>Author Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pendingComments as $comment)
                        <tr>
                            <th>{{ $indexPendingComments++ }}</th>
                            <td><input form="selections_pending" type="checkbox" name="comments_id[{{ $comment->id }}]"
                                       class="pending_comments"></td>
                            <td>
                                <a href="{{ route('admin.blog.show', ['blog' => $comment->blog->id]) }}">
                                    {{ $comment->blog->title }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($comment->context, 30, ' ...') }}</td>
                            <td>{{ $comment->author_name }}</td>
                            <td>{{ $comment->created_at->format('Y/m/d') }}</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.blog.comment.show', ['comment' => $comment->id]) }}"
                                   class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                <form action="{{ route('admin.blog.comment.approve', ['comment' => $comment->id]) }}"
                                      method="post" style="display: inline-block;">
                                    @csrf
                                    <input type="submit" name="submit" value=Approve class="btn btn-success btn-sm" style="margin-top: 3px;">
                                </form>
                                <form action="{{ route('admin.blog.comment.destroy', ['comment' => $comment->id]) }}"
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
                    {{ $pendingComments
                        ->appends(['approvedComments' => $approvedComments->currentPage()])
                        ->onEachSide(1)
                        ->links('admin.layouts.pagination') }}
                </center>
            @endif
        </div>
    </div>

    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Approved Comments</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body table-responsive no-padding">
            @if($approvedComments->isEmpty())
                <h3 style="text-align: center">There isn't any comment!</h3>
            @else
                <table class="table table-hover">
                    <thead>
                    @error('selections_approved')
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
                        <th><input type="checkbox" id="check_all_approved"></th>
                        <th colspan="9">
                            <form id="selections_approved" action="{{ route('admin.blog.comment.selections') }}" method="post">
                                @csrf
                                <input type="hidden" name="type" value="approved">
                                <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                            </form>
                        </th>
                    </tr>
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Blog Title</th>
                        <th>Context</th>
                        <th>Author Name</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($approvedComments as $comment)
                        <tr>
                            <th>{{ $indexApprovedComments++ }}</th>
                            <td><input form="selections_approved" type="checkbox" name="comments_id[{{ $comment->id }}]"
                                       class="approved_comments"></td>
                            <td>
                                <a href="{{ route('admin.blog.show', ['blog' => $comment->blog->id]) }}">
                                    {{ $comment->blog->title }}
                                </a>
                            </td>
                            <td>{{ \Illuminate\Support\Str::limit($comment->context, 30, ' ...') }}</td>
                            <td>{{ $comment->author_name }}</td>
                            <td>{{ $comment->created_at->format('Y/m/d') }}</td>
                            <td><span class="label label-success">Approved</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.blog.comment.show', ['comment' => $comment->id]) }}"
                                   class="btn btn-primary btn-sm" style="margin-top: 3px;">Details</a>
                                <form action="{{ route('admin.blog.comment.destroy', ['comment' => $comment->id]) }}"
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
                    {{ $approvedComments
                        ->appends(['pendingComments' => $pendingComments->currentPage()])
                        ->onEachSide(1)
                        ->links('admin.layouts.pagination') }}
                </center>
            @endif
        </div>
    </div>


@endsection


@section('script')

    <script>
        // Check all | Pending
        // Why did we add the if statement? If we don't do this check all approved doesn't
        // work when there isn't any pending comment!!!
        @if($pendingComments->isNotEmpty())
        document.getElementById('check_all_pending').onclick = function () {
            for (let checkbox of document.getElementsByClassName('pending_comments'))
                checkbox.checked = this.checked;
        }
        @endif

        // Check all | Approved
        document.getElementById('check_all_approved').onclick = function () {
            for (let checkbox of document.getElementsByClassName('approved_comments'))
                checkbox.checked = this.checked;
        }
    </script>

@endsection
