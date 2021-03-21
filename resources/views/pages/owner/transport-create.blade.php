@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <post-transport
                    add-pet-endpoint="{{ $addPetEndpoint }}"
                    :user-pets="{{ $pets }}"
                    transport-endpoint="{{ $transportEndpoint }}"
    ></post-transport>

@endsection

@section('scripts')
    @include('partials.google-map')
@endsection