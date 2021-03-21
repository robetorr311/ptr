@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    @if(isset($user->stripe_id) && $user->stripe_id != '')

        @if (count($bids) == 0)
            <div class="main-content" style="min-height: 465px;">
                <div class="container">
                    <div class="row">
                        <div class="col-12">
                            <div class="breadcrumb">
                                <a href="/"><i class="fa fa-chevron-left"></i></a>
                            </div>
                        </div>
                        <section class="col-12">
                            <h1 class="page-title">Cashout</h1>
                            <span>Transport is not completed yet.. Please contact driver if this is not true.. after the transport is completed you'll be able to enter your code.</span>
                        </section>
                    </div>
                </div>
            </div>
        @else
            <cashout :all-bids="{{ json_encode($bids) }}"></cashout>
        @endif

    @else
        <div class="main-content" style="min-height: 465px;">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="breadcrumb">
                            <a href="/"><i class="fa fa-chevron-left"></i></a>
                        </div>
                    </div>
                    <section class="col-12">
                        <h1 class="page-title">Stripe Connect</h1>
                        <span>You have to connect with Stripe in order to cashout</span>
                    </section>
                    <hr>
                    <div class="col-12">
                        <a href="{{ route('stripe.connect') }}" class="stripe-connect"><span>Connect with Stripe</span></a>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection