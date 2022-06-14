@extends('main.layouts.app')



@section('title', 'Checkout')



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
                                <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Cart</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
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

    <div class="checkout-page-wrapper">
        <div class="container">
            <form action="#">
                <div class="row">
                    <!-- Checkout Billing Details -->
                    <div class="col-lg-6">
                        <div class="checkout-billing-details-wrap">
                            <h2>Billing Details</h2>
                            <div class="billing-form-wrap">
                                <div class="single-input-item">
                                    <label for="email" class="required">Name</label>
                                    <input type="email" id="email" placeholder="{{ \Illuminate\Support\Facades\Auth::user()->name }}" disabled />
                                </div>

                                <div class="single-input-item">
                                    <label for="email" class="required">Email</label>
                                    <input type="email" id="email" placeholder="{{ \Illuminate\Support\Facades\Auth::user()->email }}" disabled />
                                </div>

                                <div class="single-input-item">
                                    <label for="country" class="required">Country</label>
                                    <input type="text" id="com-name" placeholder="Country" required/>
                                </div>

                                <div class="single-input-item">
                                    <label for="country" class="required">City</label>
                                    <input type="text" id="com-name" placeholder="City" required/>
                                </div>

                                <div class="single-input-item">
                                    <label for="country" class="required">Address</label>
                                    <input type="text" id="com-name" placeholder="Address" required/>
                                </div>

                                <div class="single-input-item">
                                    <label for="phone">Phone</label>
                                    <input type="text" id="phone"  placeholder="Phone" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary Details -->
                    <div class="col-lg-6">
                        <div class="order-summary-details mt-md-26 mt-sm-26">
                            <h2>Your Order Summary</h2>
                            <div class="order-summary-content mb-sm-4">
                                <div class="order-summary-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <tbody>
                                        <tr>
                                            <td>Sub Total</td>
                                            <td><strong>${{ number_format($subtotalPrice, 2) }}</strong></td>
                                        </tr>
                                        @if($coupon)
                                            <tr>
                                                <td>Coupon</td>
                                                <td><strong>${{ number_format($coupon, 2) }}</strong></td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td>Shipping</td>
                                            <td><strong>${{ number_format($shipping, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td>Total Amount</td>
                                            <td><strong>${{ number_format($subtotalPrice + $shipping - $coupon, 2) }}</strong></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- Order Payment Method -->
                                <div class="order-payment-method">
                                    <div class="single-payment-method show">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="cashon" name="paymentmethod" value="cash" class="custom-control-input" checked  />
                                                <label class="custom-control-label" for="cashon">Cash On Delivery</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="directbank" name="paymentmethod" value="bank" class="custom-control-input" />
                                                <label class="custom-control-label" for="directbank">Credit Card</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="single-payment-method">
                                        <div class="payment-method-name">
                                            <div class="custom-control custom-radio">
                                                <input type="radio" id="paypalpayment" name="paymentmethod" value="paypal" class="custom-control-input" />
                                                <label class="custom-control-label" for="paypalpayment">Paypal</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="summary-footer-area">
                                        <div class="custom-control custom-checkbox mb-14">
                                            <input type="checkbox" class="custom-control-input" id="terms" required />
                                            <label class="custom-control-label" for="terms">I have read and agree to the website <a
                                                    href="index.html">terms and conditions.</a></label>
                                        </div>
                                        <button type="submit" class="check-btn sqr-btn">Place Order</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection


@section('script')



@endsection
