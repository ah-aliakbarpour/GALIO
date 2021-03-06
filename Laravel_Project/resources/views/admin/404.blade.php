@extends('admin.layouts.app')



@section('title', '404 Error')


@section('content-header', '404 Error')


@section('content')

    <div class="error-page">
        <h2 class="headline text-yellow"> 404</h2>
        <div class="error-content">
            <br>
            <h3><i class="fa fa-warning text-yellow"></i> Oops! Page not found.</h3>
            <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="{{ route('admin.dashboard') }}">return to dashboard</a>.
            </p>
        </div>
    </div>

@endsection
