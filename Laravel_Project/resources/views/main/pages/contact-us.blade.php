@extends('main.layouts.app')



@section('title', 'Contact Us')


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
                                <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb area end -->

    <!-- contact area start -->
    <div class="contact-area pb-34 pb-md-18 pb-sm-0">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact-info mt-md-28 mt-sm-28">
                        <h2>contact us</h2>
                        <p>We collaborate with ambitious brands and people; we’d love to build something great together.</p>
                        <ul>
                            <li><i class="fa fa-map-marker"></i> Address : Iran, Tehran</li>
                            <li><i class="fa fa-phone"></i> 09188284088</li>
                            <li><i class="fa fa-envelope-o"></i> ah.aliakbarpour@gmail.com</li>
                        </ul>
                        <div class="working-time">
                            <h3>Working hours</h3>
                            <p><span>Saturday – Wednesday:</span>08AM – 18PM</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- contact area end -->

@endsection
