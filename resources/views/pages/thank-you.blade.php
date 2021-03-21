@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')
    <div class="main-content">
        <div class="container">
            <div class="row">
                <section class="col-12">
                    <div class="hero-banner banner">
                        <div class="banner__background">
                            <img src="/img/home.jpg" alt="about us hero banner">
                        </div>
                        <div class="banner__content banner__content--top">
                            <h1>Get in touch</h1>
                            <p>Have any Questions? If you do, don't hesitate and Contact Us.</p>
                        </div>
                    </div>
                </section>
                <section class="col-12">
                    <div class="contact">
                        <div class="row">
                            <div class="col-lg-8 col-xs-6 thank-you-note-wrapper">
                                <div class="thank-you-note">
                                    <h1>Thank You!</h1>
                                    <p>Your Message has been Sent Successfully.</p>
                                    <p>We will reply shortly!</p>
                                    <div>
                                        <a href="/"><i class="fa fa-chevron-left"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="contact-info">
                                    <h3>Contact Information</h3>
                                    <div class="location">
                                        <span><i class="fa fa-map-marker "></i></span>
                                        <span>1330 N Dearborn St, Chicago, IL</span>
                                        <span>60610, United States</span>
                                    </div>
                                    <div class="phone">
                                        <span><i class="fa fa-phone"></i> </span>
                                        <span>+1 (219) 440-1182</span>
                                    </div>
                                    <div class="mail">
                                        <span><i class="fa fa-envelope-o"></i> </span>
                                        <span>info@pettravelhub.com</span>
                                    </div>
                                    <div class="social-media">
                                        <span class="social-media__title">
                                            Follow Us on Social Media
                                        </span>
                                        <div class="social-media__icons">
                                            <a href="/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            <a href="/"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                            <a href="/"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection