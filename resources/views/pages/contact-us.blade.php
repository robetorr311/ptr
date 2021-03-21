@extends('layouts.base')

@section('content')
    @include('partials.flash_messages')

    <div class="main-content">
        <div class="container">
            <div class="row">
                <section class="col-12">
                    <div class="hero-banner banner">
                        <div class="banner__background">
                            <img src="{{ asset('/img/contact_us.jpg') }}" alt="about us hero banner">
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
                            <div class="col-lg-8 col-xs-6">
                                <form method="post" action="{{ route('contact-us.submit') }}">
                                    @csrf
                                    <h3 class="section-title">Send Us a Message</h3>
                                    {{--<div class="form-group">--}}
                                        {{--<label for="contact-topic">Select Message Topic</label>--}}
                                        {{--<select class="form-control" id="contact-topic" name="topic">--}}
                                            {{--<option>Topic 1</option>--}}
                                            {{--<option>Topic 2</option>--}}
                                            {{--<option>Topic 3</option>--}}
                                            {{--<option>Topic 4</option>--}}
                                            {{--<option>Topic 5</option>--}}
                                        {{--</select>--}}
                                    {{--</div>--}}
                                    <div class="form-group">
                                        <label for="name">Your name</label>
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter Name...">
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="phone">Phone</label>
                                            <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone...">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter Email...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="message">Message</label>
                                        <textarea class="form-control" id="message" rows="3" name="message"></textarea>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6 recaptcha">
                                            <div class="g-recaptcha" data-sitekey="6Le7hz0UAAAAAPbftDdAnutXed5xZBT260A7sMXy"></div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <button type="submit" class="btn btn--secondary btn--secondary--fill btn-send"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp; SEND</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-4 col-xs-6">
                                <div class="contact-info">
                                    <h3>Contact Information</h3>
                                    <div class="location">
                                        <span><i class="fa fa-map-marker "></i></span>
                                        <span>545 N Dearborn St, Chicago, Illinois 60654</span>
                                        <span>60610, USA</span>
                                    </div>
                                    <div class="phone">
                                        <span><i class="fa fa-phone"></i> </span>
                                        <span>773-888-1060</span>
                                    </div>
                                    <div class="mail">
                                        <span><i class="fa fa-envelope-o"></i> </span>
                                        <span>pets@pettravelhub.com</span>
                                    </div>
                                    <div class="social-media">
                                        <span class="social-media__title">
                                            Follow Us on Social Media
                                        </span>
                                        <div class="social-media__icons">
                                            <a href="https://www.instagram.com/pettravelhub/"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            <a href="https://www.facebook.com/Pet-Travel-Hub-107814334151527/?modal=admin_todo_tour"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                            <a href="https://twitter.com/PetTravelHub2"><i class="fa fa-twitter" aria-hidden="true"></i></a>
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