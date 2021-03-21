<div class="main-content">
    <div class="container">



        <!--About section-->
        <div class="row landing-about">
            <div data-aos="fade-up" class="col-12 col-lg-12 landing-subtitle">
                <h3><span style='color: #008800;'>Book trusted pet drivers who'll treat your pets like family.</span></h3>
                <h4><span style='color: #008800;'>You don’t pay until the transport is complete.</span></h4>
            </div>

            <div class="col-12 col-sm-6">
                <div data-aos="fade-right" class="about-text">
                    <h5>Preparation Guidelines</h5>
                    <p>
                        Post your pet transport. Get Free Quotes and Compare Drivers. Our team at Pet Travel Hub will help you through the whole process. We guarantee the secure payment and transport. Accept the bid and transporter you chose.  Make sure you get all necessary documents for transport. Do not make payments off the platform! We are here to help and make this transport east for your loved family member. </p>
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div data-aos="fade-left" class="about-img">
                    <img src="img/Layer-1.png">
                </div>
            </div>
            <div class="col-12 col-sm-6">
                <div data-aos="fade-right" class="about-img">
                    <img src="img/Layer-2.png">
                </div>
            </div>
            <div class="col-12 col-sm-6 about-text-right">
                <div data-aos="fade-left" class="about-text">
                    <h5>Next Steps</h5>
                    <p>
                        Coordinate with pet transporter. Be specific as possible with all information about your loved family member. Transport will be door to door ground transportation, flying in climatized hold or flight nanny transport. Have your pet ready for transport for pick up. When the transport is completed, you give the payment code to the transporter.  Simple, safe and secure. </p>
                </div>
            </div>
        </div>
        <!--END - About section-->

    </div>
    <!--Find Driver section-->
    <div class="find-driver-landing" style="background-image: url('{{ asset("img/Door-to-Door-Pet-Relocation.png")}}')">
        <div class="container">
            <div class="row">
                <div class="col-12 col-sm-6">
                    <div data-aos="zoom-in-up" class="find-driver-content">
                        <h3>Door to Door Pet Transportation Anywhere in The World</h3>
                        <p>
                            There's nothing worse than getting your hopes up, then not being able to find your pet transport. That won't happen on Pet Travel Hub. From big cities to small towns, we have trusted pet transporters all over the world. Never pay until the transport is completed. Our team of specialists work with the best pet transporters in the world. Pet Travel Hub is the most trusted pet transport platform in the world.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--END - Find Driver section-->
    <!--Info Blocks section-->
    <div class="container landing-info-blocks-section">
        <div class="row">           
            <div class="col-12 col-sm-12">
            <h3><span style='color: #008800;'> Love Animals and Want to Become a Pet Transporter?</span></h3>
        </div>
        </div> 
        <div class="row">          
            <div class="col-12 col-sm-4">
                <div data-aos="fade-right" class="landing-info-block">
                    <img src="/img/calendar.png">
                    <h4>Set your own hours</h4>
                    <p>
                        You decide when and how often you drive.
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div data-aos="fade-up" class="landing-info-block">
                    <img src="/img/car.png">
                    <h4>Get paid fast</h4>
                    <p>

                        Choose how and when you want to get paid.
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div data-aos="fade-left" class="landing-info-block">
                    <img src="/img/support.png">
                    <h4>Get support at every turn</h4>
                    <p>

                        If there’s anything that you need, you can reach us anytime.
                    </p>
                </div>
            </div>
            <div class="col-12 col-lg-12" style="text-align:center; padding-top: 20px;">
                <a href="{{ route('register.driver') }}" class="btn btn--primary btn--primary--fill home-quote-button">
                    <span>Apply to Drive</span>
                </a>
            </div>
        </div>
    </div>
    <!--END - Info Blocks section-->
    <div class="container-gray" style="padding-bottom: 20px;">
        <div class="container">
            <div class="row landing-about">
                <div data-aos="fade-up" class="col-12 col-lg-12 landing-subtitle">
                    <h3 style="color: #047f03;">Book pet drivers you can trust. Pay after the transport is completed!</h3>
                </div>
                <div class="col-12 col-sm-6">
                    <div data-aos="fade-right">
                        <ul class="section-bullets">
                            <li>All drivers need to pass a background check.</li>
                            <li>All drivers provide a detailed profile and personal information.</li>
                            <li>All drivers are approved by our team of pet transport specialists.</li>
                        </ul>
                            @if(auth()->user())
                            @if(auth()->user()->hasRole('driver'))
                                <a href="#" class="btn btn--primary btn--primary--fill home-quote-button">
                                    <span>Find Transports</span>
                                </a>  
                            @endif 
                            @else 
                            <a href="#" class="btn btn--primary btn--primary--fill home-quote-button">
                                <span>Get a Free Quote from Drivers</span>
                            </a> 
                            @endif   
                    </div>
                </div>
                <div class="col-12 col-sm-6">
                    <div data-aos="fade-right" class="about-img">
                        <img src="img/ferland.jpg">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container landing-info-other-section">
        <div class="row landing-about">
            <div data-aos="fade-up" class="col-12 col-lg-12 landing-subtitle">
                <h3>Your pet is treated like family</h3>
                <h4>How it works</h4>
            </div>
            <div class="col-12 col-sm-4">
                <div data-aos="fade-right" class="landing-info-other">
                    <img src="/img/search-driver.png">
                    <h4>Post and Get a Free Quote</h4>
                    <p>
                        Read verified reviews and pick the perfect driver.
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div data-aos="fade-up" class="landing-info-other">
                    <img src="/img/phone-driver.png">
                    <h4>Book & pay on Pet Travel Hub</h4>
                    <p>

                    Book and pay securely through the website by Secure Stripe Payments Portal
                    </p>
                </div>
            </div>
            <div class="col-12 col-sm-4">
                <div data-aos="fade-left" class="landing-info-block">
                    <img src="/img/love-driver.png">
                    <h4>Relax</h4>
                    <p>Get the Pet Travel Hub Guarantee, 24/7 support and booking protection.</p>
                </div>
            </div>
        </div>
    </div>
    <!--END - Info Blocks section-->

</div>