@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <search-base
        :transports="{{ json_encode($transports) }}"
        data-endpoint="{{ $dataEndpoint }}"
        geo-endpoint="{{ $geoEndpoint }}"
        bid-endpoint="/driver/bid/"
    ></search-base>

@endsection

@section('scripts')
    {{--@include('partials.google-map')--}}
@endsection