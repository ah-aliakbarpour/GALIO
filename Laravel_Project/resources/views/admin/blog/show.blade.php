@extends('admin.layouts.app')



@section('title', 'Blog Details')


@section('content-header', 'Blog Details')

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

    <div class="box box-primary">
        <div class="box-body">
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
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Title</th>
                                <td>{{ $blog->title }}</td>
                            </tr>
                            <tr>
                                <th>Author</th>
                                <td>{{ $blog->author }}</td>
                            </tr>
                            <tr>
                                <th>Context</th>
                                <td style="text-align: justify">{!! $blog->context ?? '&#9866;' !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-4">
                    <table class="table table-hover">
                        <tbody>
                            <tr>
                                <th>Category</th>
                                <td>
                                    @if($blog->category)
                                        <span class="label label-default">{{ $blog->category->title }}</span>
                                    @else
                                        &#9866;
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Tags</th>
                                <td>
                                    @forelse($blog->tags as $tag)
                                        <span class="label label-default">{{ $tag->title }}</span>
                                    @empty
                                        &#9866;
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{ $blog->created_at->format('Y/m/d H:i:s') }}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{ $blog->updated_at->format('Y/m/d H:i:s') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="pull-right">
                        <a href="{{ route('admin.blog.edit', ['blog' => $blog->id]) }}"
                           class="btn btn-success btn-sm">Edit</a>
                        <form action="{{ route('admin.blog.destroy', ['blog' => $blog->id]) }}"
                              method="post" style="display: inline-block;">
                            @csrf
                            @method('delete')
                            <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
                            <td>{{ \Illuminate\Support\Str::limit($comment->context, 50, '...') }}</td>
                            <td>{{ $comment->author_name }}</td>
                            <td>{{ $comment->created_at->format('Y/m/d') }}</td>
                            <td><span class="label label-warning">Pending</span></td>
                            <td style="padding-top: 5px;">
                                <a href="{{ route('admin.blog.comment.show', ['comment' => $comment->id]) }}" target="_blank"
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
                            <td>{{ \Illuminate\Support\Str::limit($comment->context, 50, '...') }}</td>
                            <td>{{ $comment->author_name }}</td>
                            <td>{{ $comment->created_at->format('Y/m/d') }}</td>
                            <td><span class="label label-success">Approved</span></td>
                            <td>
                                <a href="{{ route('admin.blog.comment.show', ['comment' => $comment->id]) }}" target="_blank"
                                   class="btn btn-primary btn-sm">Details</a>
                                <form action="{{ route('admin.blog.comment.destroy', ['comment' => $comment->id]) }}"
                                      method="post" style="display: inline-block;">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" name="submit" value="Delete" class="btn btn-danger btn-sm">
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
