@extends('main.layouts.app')



@section('title', 'Forgot Password')


@section('style')



@endsection


@section('content')

    <!-- breadcrumb area start -->
    <div class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="breadcrumb-wrap">
                        <nav aria-label="breadcrumb">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('index') }}">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Forgot Password</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- Alert -->
    @if(Session()->exists('alert'))
        <div class="container mb-10">
            <div class="row">
                <div class="col-md-12">
                    <p class="alert alert-{{ Session()->get('alert')['type'] }}">
                        {{ Session()->get('alert')['massage'] }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <div class="login-register-wrapper">
        <div class="container">
            <div class="member-area-from-wrap">
                <div class="row">
                    <div class="col-lg-3" ></div>
                    <div class="col-lg-6">
                        <div class="login-reg-form-wrap mt-md-34 mt-sm-34">
                            <h2>Forgot Password</h2>
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <h7>Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</h7>
                            <form action="{{ route('password.email') }}" method="post">
                                @csrf
                                <div  class="single-input-item">
                                    <input type="email" name="email" placeholder="Enter your Email" value="{{ old('email') }}" required />
                                </div>
                                <div class="single-input-item">
                                    <button class="sqr-btn" type="submit" name="submit">Email Password Reset Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
