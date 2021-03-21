@extends('layouts.base')

@section('content')

    <div class="main-content">
        <div class="container">
            <div class="row">
                <section class="col-12">
                    <div class="hero-banner banner about-us-banner">
                        <div class="banner__background">
                            <img src="{{ asset('/img/about-us.jpg') }}" alt="about us hero banner">
                        </div>
                        <div data-aos="fade-left" class="banner__content">
                            <h1>Who we are?</h1>
                            <p>We are a team of animal lovers. Pet Travel Hub was started in 2012 and is the largest pet transporter platform in the world.  Pet Travel Hub was started by Nenad Jovanovic in Chicago with a couple partners. He started the business when he found no easy way to transport his dog. He called Airlines and local companies but no one could help him.  After 50 phone calls he realized that Pet Travel Hub needed to be created.  He started doing pet transports with his wife and loved it. Then, Nenad and his developer team created a platform that makes pet transport easier. You can have your pet transported 10 miles or 3,000 miles. Nenad did over 500 transports and took the next step. Creating a great platform for other people and creating jobs for pet lovers.

                                Pet Travel Hub was born and is now thriving as the largest pet transport platform in the world. </p>
                        </div>
                    </div>
                </section>
                <section class="col-12">
                    <div class="services">
                        <div class="row">
                            <div class="col-12">
                                <h3 data-aos="fade-right" class="services__title">Services We Offer</h3>
                            </div>
                            <div class="col-lg-4">
                                <div data-aos="fade-left" class="service">
                                    <div class="service__image">
                                        <img src="img/hammer.png" alt="">
                                    </div>
                                    <div class="service__title">
                                        Submit a Pet Transport and Watch Drivers Bid
                                    </div>
                                    <div class="service__description">
                                        Get bids from drivers around the country. Secure payments and safe transport.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div data-aos="fade-up" class="service">
                                    <div class="service__image">
                                        <img src="img/tag.png" alt="">
                                    </div>
                                    <div class="service__title">
                                        Payment is Easy
                                    </div>
                                    <div class="service__description">
                                    Driver gets a secure code after the transport is completed.
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div data-aos="fade-right" class="service">
                                    <div class="service__image">
                                        <img src="img/money-flow.png" alt="">
                                    </div>
                                    <div class="service__title">
                                        Drivers make money on your own schedule.
                                    </div>
                                    <div class="service__description">
                                        Bid for pet transports and make a great living doing what you love.
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>
                <section class="col-12">
                    <div class="small-banner banner">
                        <div class="banner__background">
                            <img src="{{ asset('/img/pet_travel_about_us_2.jpg') }}" alt="about us small banner">
                        </div>
                        <div data-aos="fade-right" class="banner__content">
                            <h1>Why choose us?</h1>
                            <p>We are the most secure and safest option for your pet.</p>
                        </div>
                    </div>
                </section>
            </div>


        </div>
    </div>
@endsection