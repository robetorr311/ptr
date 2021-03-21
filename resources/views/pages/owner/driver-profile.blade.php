@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')


    <driver-profile-guest
            :user-data="{{ json_encode($driver) }}"
    >
    </driver-profile-guest>

@endsection