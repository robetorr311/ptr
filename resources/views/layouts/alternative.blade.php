<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="HandheldFriendly" content="True">
    <meta name="MobileOptimized" content="320">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.csrfToken = '{{ csrf_token() }}';
        @if(auth()->check())
            @php
                /* @var \App\User $user */
                $user = auth()->user();
            @endphp

            window.user = JSON.parse('{!! addslashes(json_encode($user)) !!}');
            window.user.role = JSON.parse('{!! addslashes(json_encode($user->roles[0]->name)) !!}');
        @else
            window.user = false;
        @endif
    </script>

    <title>{{ config('app.name', 'Pet Travel Hub') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href='https://fonts.googleapis.com/css?family=Baloo Bhaina' rel='stylesheet'>
    {{--<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans" />--}}
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">


    <link href='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css' rel='stylesheet'>

    <link rel="shortcut icon" href="{{{ asset('img/favicon.png') }}}">

    <!-- Styles -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset(mix('css/app.css')) }}" rel="stylesheet">
    @yield('stylesheets')
</head>
<body>
    <div id="preloader" style="background: #333 url('{{ asset('/img/loading.gif') }}')
    no-repeat center center;"></div>

    <div id="app">
        <header>
            <div class="navbar-wrapper">
                <div class="nav-left" style="right: 50%;">
                    <div class="logo">
                        <a href="/"><img src="{{ asset('img/logo.png') }}" alt=""></a>
                    </div>

                    @if(!auth()->check())
                    <div class="nav-right" style="position:absolute; right: 50%;">
                    <div class="nav-links">
                        <a href="{{ route('register.owner') }}" class="control">
                                <span class="control_label">
                                Owner
                            </span>
                        </a>
                        <a href="{{ route('register.driver') }}" class="control">
                                <span class="control_label">
                                Driver
                            </span>
                        </a> 
                    </div>
                    </div>
                    @endif

                    @if(auth()->check())
                        @if($user->hasRole('owner'))
                        <div class="nav-button hide-for-mobile">
                            <a href="{{ route('owner.create.transport') }}" class="btn btn--primary btn--primary--fill">
                                <img src="{{ asset('img/add-offer.png') }}">
                                <span>Get a free quote</span>
                            </a>
                        </div>
                        @endif
                        @if($user->hasRole('driver'))
                            <div class="nav-button hide-for-mobile">
                                <a href="{{ route('driver.search.geo') }}" class="btn btn--primary btn--primary--fill">
                                    <img src="{{ asset('img/search-transport.png') }}">
                                    <span>Find Transports</span>
                                </a>
                            </div>
                        @endif
                    @endif
                </div>
                @if(auth()->check())
                    @if($user->hasRole('admin'))
                    <div class="nav-right">
                    <div class="nav-links">
                        <a href="{{ route('register') }}" class="control">
                                <span class="control_label">
                                Driver
                            </span>
                        </a> 
                        <a href="{{ route('register') }}" class="control">
                            <span class="control_label">
                                Owner
                            </span>
                        </a>
                    </div>
                    @endif
                @endif
                <div class="nav-right">
                    <div class="nav-links">
                        <a href="{{ route('about-us') }}" class="control">
                                <span class="control_label">
                                About Us
                            </span>
                        </a> 
                        <a href="{{ route('faq') }}" class="control">
                            <span class="control_label">
                                Faq
                            </span>
                        </a>
                        <a href="{{ route('contact-us') }}" class="control">
                            {{--<img class="control_img" src="{{ asset('img/contact.png') }}" alt="">--}}
                            <span class="control_label">
                                Contact Us
                            </span>
                        </a>
                    </div>


                    @if(auth()->check())

                        <notifications :data="{{ json_encode($user->unreadNotifications) }}"></notifications>

                        <div class="dropdown header-mobile">
                            <div class="control clickable-raw dropdown-profile" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="control_img nav-avatar" style="background-image:url({{ asset($user->avatar) }})"></div>
                            </div>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <div class="d-menu d-avatar-wrapper">
                                    <div class="d-avatar" style="background-image:url({{ asset($user->avatar) }})"></div>
                                    <span>
                                        {{ $user->name }}
                                    </span>

                                </div>
                                <div class="d-menu-logged-in">
                                    @if($user->hasRole('owner'))
                                        <a class="dropdown-item" href="{{ route('owner.create.transport') }}"><i class="fa fa-tag"></i> Add Offer</a>
                                    @endif

                                    @if($user->hasRole('driver'))
                                        <a href="{{ route('driver.search.geo') }}" class="dropdown-item"><i class="fa fa-search"></i> Find Transports</a>
                                    @endif

                                    <a class="dropdown-item" href="{{ route('profile.index') }}"><i class="fa fa-user"></i> My Account</a>

                                    @if($user->hasRole('driver'))
                                        <a class="dropdown-item" href="{{ route('driver.show.cashout') }}"><i class="fa fa-dollar"></i> Cashout</a>
                                    @endif



                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                                           {{ __('Logout') }}  class="control">
                                            <i class="fa fa-power-off"></i> Logout
                                        </a>
                                </div>
                                <a class="dropdown-item" href="{{ route('about-us') }}">About Us</a>
                                <a class="dropdown-item" href="{{ route('faq') }}">FAQ</a>
                                <a class="dropdown-item" href="{{ route('contact-us') }}">Contact Us</a>
                                <div class="social-media-icons">
                                    <a href="https://www.instagram.com/cute_puppy_time/"><i class="fa fa-instagram white" aria-hidden="true"></i></a>
                                    <a href="https://www.facebook.com/CutePuppyTime/"><i class="fa fa-facebook white" aria-hidden="true"></i></a>
                                    <a href="https://twitter.com/cutepuppytime"><i class="fa fa-twitter white" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>

                        <div class="dropdown dropdown-profile-wrapper hide-for-mobile">
                            <div class="control clickable-raw dropdown-profile" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <div class="control_img nav-avatar" style="background-image:url({{ asset($user->avatar) }})"></div>
                                <div>
                                    <span class="control_label">
                                        {{ $user->name }}
                                    </span>
                                </div>
                            </div>

                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                <a class="dropdown-item" href="{{ route('profile.index') }}">My Account</a>

                                @if($user->hasRole('driver'))
                                    <a class="dropdown-item" href="{{ route('driver.show.cashout') }}">Cashout</a>
                                @endif

                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();"
                                   {{ __('Logout') }}  class="control">
                                    <i class="fa fa-power-off"></i> Logout
                                </a>
                            </div>
                        </div>


                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    @else

                        <div class="dropdown header-mobile">
                            <a class="btn dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuLink">
                                <div class="d-menu">
                                    <a href="/">Sign In</a>
                                    <a href="/register/owner">Owner</a>
                                    <a href="/register/driver">Driver</a>
                                </div>
                                <a class="dropdown-item" href="{{ route('about-us') }}">About Us</a>
                                <a class="dropdown-item" href="{{ route('faq') }}">FAQ</a>
                                <a class="dropdown-item" href="{{ route('contact-us') }}">Contact Us</a>
                                <div class="social-media-icons">
                                    <a href="https://www.instagram.com/cute_puppy_time/"><i class="fa fa-instagram white" aria-hidden="true"></i></a>
                                    <a href="https://www.facebook.com/CutePuppyTime/"><i class="fa fa-facebook white" aria-hidden="true"></i></a>
                                    <a href="https://twitter.com/cutepuppytime"><i class="fa fa-twitter white" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>

                    @endif

                </div>
            </div>
        </header>

        @yield('content')

        <footer-component></footer-component>

    </div>

    <script>
        window.hideLoader = function(){
            document.getElementById('preloader').style.display = "none";
        };

        window.showLoader = function(){
            document.getElementById('preloader').style.display = "block";
        };
    </script>

    <!-- Scripts -->
    @yield('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    {{--<script src='https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js'></script>--}}
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
    {{--<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>--}}
    {{--<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>--}}


</body>
</html>
