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
                        <div class="banner-text"><h1>Door to Door Pet Transportation <br />Anywhere in the world</h1></div>
                    </div>
                </section>
                {{--<section class="col-lg-4">--}}
                    {{--<a href="#" class="btn btn--no-border btn-full btn--secondary btn--secondary--fill">See how Pet Travel Hub Works <i class="fa fa-play-circle-o"></i></a>--}}
                {{--</section>--}}
            </div>
        </div>
    </div>
    @include('partials.landing-info-content')

@endsection

@section('scripts')
    @include('partials.google-map')
@endsection