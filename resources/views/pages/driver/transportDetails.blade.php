@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <driver-transport
        :transport="{{ json_encode($transport) }}"
    ></driver-transport>
@endsection

@section('scripts')
    @include('partials.google-map')
@endsection