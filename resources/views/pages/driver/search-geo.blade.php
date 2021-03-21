@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <search-geo
        search-geo-endpoint="{{ $geoEndpoint }}"
        all-transports="{{  json_encode($allTransports) }}"
        bid-endpoint="/driver/bid/"
    ></search-geo>

@endsection

@section('scripts')
    @include('partials.google-map')
@endsection