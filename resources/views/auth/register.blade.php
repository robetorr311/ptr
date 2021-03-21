@extends('layouts.base')

@section('content')

    <register
            owner-registration-endpoint="{{ $ownerRegistrationEndpoint }}"
            login-endpoint="{{ $loginEndpoint }}"
            driver-endpoint="{{ $driverEndpoint }}"
    ></register>
@endsection
