@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    @if($user->hasRole('owner'))
        <owner-profile
            :user-data="{{ $user }}"
            add-pet-endpoint="{{ $addPetEndpoint }}"
        ></owner-profile>
    @elseif($user->hasRole('driver'))
        <driver-profile
            :user-data="{{ $user }}"
        >
        </driver-profile>
    @endif

@endsection