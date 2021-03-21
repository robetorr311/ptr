@extends('layouts.base')

@section('content')

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger">
            <ul>
                <li>{{ session()->get('error') }}</li>
            </ul>
        </div>
    @endif

    <register-owner
            owner-registration-endpoint="{{ $ownerRegistrationEndpoint }}"
            login-endpoint="{{ $loginEndpoint }}"
    ></register-owner>
@endsection
