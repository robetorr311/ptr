@extends('layouts.base')

@section('content')




    @include('partials.flash_messages')
    <div class="main-content home-background">
        <div class="container">
            <div class="row">
                    <section class="col-12">
                        <div class="hero-banner banner">
                            <div class="banner__background">
                            </div>
                            <div class="banner-text">
                                <a href="{{ route('register') }}" class="btn btn--primary btn--primary--fill home-quote-button">
                                    <span>Get A Free Quote</span>
                                </a>
                                <h1>Door to Door Pet Transportation Anywhere in the World</h1>
                            </div>
                        </div>

                    </section>
            </div>
        </div>

    </div>
    <sign-in-area
            owner-registration-endpoint="{{ $ownerRegistrationEndpoint }}"
            login-endpoint="{{ $loginEndpoint }}"
            driver-endpoint="{{ $driverEndpoint }}"
    ></sign-in-area>



    @include('partials.landing-info-content')

@endsection