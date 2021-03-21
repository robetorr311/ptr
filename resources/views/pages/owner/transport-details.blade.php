@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <owner-transport
        :transport="{{ json_encode($transport) }}"
        stripe-key="{{ $stripe_pk_key }}"
    ></owner-transport>
@endsection

@section('scripts')
    <script src="https://checkout.stripe.com/checkout.js"></script>
    @include('partials.google-map')
@endsection