@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <owner-landing add-pet-endpoint="{{ $addPetEndpoint }}"
        :pets="{{ $pets }}"
        transport-endpoint="{{ $transportEndpoint }}"
        my-transports-endpoint="{{ $myTransportsEndpoint }}"

    ></owner-landing>
    {{--@include('partials.landing-info-content')--}}
@endsection

@section('scripts')
    @include('partials.google-map')
@endsection