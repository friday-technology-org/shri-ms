@extends('theme::layouts.app')
@section('content')
    @php
        $home = get_field("home_page_content");
        $banner = $home['banner_content'];
        $features = $home['features_content'];
    @endphp
    <!-- banner-section -->
    <section class="banner-section">
        <div class="bg-layer" style="background-image: url({{ theme_asset('assets/images/icons/banner-1.png') }});"></div>
        <div class="pattern-bg" style="background-image: url({{ theme_asset('assets/images/icons/vactor-1.png') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div class="content-box">
                        <h1>{{ $banner['heading'] }}</h1>
                        <div class="text">{{ $banner['description'] }}</div>
                        <div class="btn-box"><a href="{{ $banner['button_link'] }}">{{ $banner['button_text'] }}</a></div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div class="image-box float-bob-y clearfix">
                        <figure class="image image-1 wow fadeInUp" data-wow-delay="900ms" data-wow-duration="1500ms"><img
                                src="{{ get_media_url($banner['image_1']) }}" alt=""></figure>
                        <figure class="image image-2 wow fadeInUp" data-wow-delay="1500ms" data-wow-duration="1500ms"><img
                                src="{{ get_media_url($banner['image_2']) }}" alt=""></figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-section end -->

    <!-- feature-style-two -->
    <section class="feature-style-two centred">
        <div class="container">
            <div class="row">
                @foreach($features as $feature)
                    <div class="col-lg-4 col-md-6 col-sm-12 feature-block">
                        <div class="feature-block-one wow flipInY animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="inner-box js-tilt">
                                <div class="hover-content"></div>
                                <div class="icon-box">
                                    <div class="bg-layer"
                                        style="background-image: url({{ theme_asset('assets/images/icons/feature-icon-1.png') }});">
                                    </div>
                                    <i class="{{$feature['icon']}}"></i>
                                </div>
                                <h5><a href="#">{{$feature['heading']}}</a></h5>
                                <div class="text">{{$feature['description']}}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                <div class="col-lg-4 col-md-6 col-sm-12 feature-block">
                    <div class="feature-block-one wow flipInY animated" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <div class="inner-box js-tilt">
                            <div class="hover-content"></div>
                            <div class="icon-box">
                                <div class="bg-layer"
                                    style="background-image: url({{ theme_asset('assets/images/icons/feature-icon-2.png') }});">
                                </div>
                                <i class="flaticon-seo-and-web"></i>
                            </div>
                            <h5><a href="#">Fully Responsive</a></h5>
                            <div class="text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 feature-block">
                    <div class="feature-block-one wow flipInY animated" data-wow-delay="600ms"
                        data-wow-duration="1500ms">
                        <div class="inner-box js-tilt">
                            <div class="hover-content"></div>
                            <div class="icon-box">
                                <div class="bg-layer"
                                    style="background-image: url({{ theme_asset('assets/images/icons/feature-icon-3.png') }});">
                                </div>
                                <i class="flaticon-app"></i>
                            </div>
                            <h5><a href="#">Easy to Customize</a></h5>
                            <div class="text">Duis aute irure dolor in reprehenderit in voluptate velit esse cillum.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- feature-style-two end -->


    <!-- feature-style-three -->
    <section class="feature-style-three">
        <div class="container">
            <div class="inner-container">
                <div class="inner-box">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                            <div id="content_block_02">
                                <div class="content-box wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
                                    <div class="sec-title">
                                        <h2>Mobile Applications Redefined</h2>
                                    </div>
                                    <div class="text">
                                        <p>On the other hand we denounce with righteous indignation and dislike men who are
                                            so beguiled and demoralized.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                                        </p>
                                    </div>
                                    <div class="btn-box"><a href="#" class="theme-btn">Learn More<i
                                                class="fas fa-angle-right"></i></a></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                            <div id="iamge_block_02">
                                <div class="image-box">
                                    <div class="bg-layer"
                                        style="background-image: url({{ theme_asset('assets/images/icons/image-shap-1.png') }});">
                                    </div>
                                    <figure class="image image-1 wow slideInRight" data-wow-delay="300ms"
                                        data-wow-duration="1500ms"><img
                                            src="{{ theme_asset('assets/images/resource/dashbord-1.jpg') }}"
                                            alt=""></figure>
                                    <figure class="image image-2 wow slideInRight" data-wow-delay="00ms"
                                        data-wow-duration="1500ms"><img
                                            src="{{ theme_asset('assets/images/resource/dashbord-2.jpg') }}"
                                            alt=""></figure>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="inner-box">
                    <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                            <div id="iamge_block_03">
                                <div class="image-box">
                                    <div class="bg-layer"
                                        style="background-image: url({{ theme_asset('assets/images/icons/image-shap-2.png') }});">
                                    </div>
                                    <figure class="image image-1 wow slideInLeft" data-wow-delay="00ms"
                                        data-wow-duration="1500ms"><img
                                            src="{{ theme_asset('assets/images/resource/dashbord-3.jpg') }}"
                                            alt=""></figure>
                                    <figure class="image image-2 wow slideInLeft" data-wow-delay="300ms"
                                        data-wow-duration="1500ms"><img
                                            src="{{ theme_asset('assets/images/resource/dashbord-4.jpg') }}"
                                            alt=""></figure>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                            <div id="content_block_03">
                                <div class="content-box wow fadeInUp" data-wow-delay="300ms" data-wow-duration="1500ms">
                                    <div class="sec-title">
                                        <h2>Easy access to business information</h2>
                                    </div>
                                    <div class="text">
                                        <p>On the other hand we denounce with righteous indignation and dislike men who are
                                            so beguiled and demoralized.</p>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
                                        </p>
                                    </div>
                                    <div class="btn-box"><a href="#" class="theme-btn">Learn More<i
                                                class="fas fa-angle-right"></i></a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- feature-style-three -->


    <!-- video-section -->
    <section class="video-section">
        <div class="bg-column"
            style="background-image: url({{ theme_asset('assets/images/background/video-bg.jpg') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 video-column">
                    <div class="video-inner">
                        <a href="https://www.youtube.com/watch?v=nfP5N9Yc72A&amp;t=28s" class="lightbox-image"
                            data-caption="">
                            <i class="flaticon-play-button"></i>
                            <span class="ripple"></span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div id="content_block_04">
                        <div class="content-box">
                            <div class="sec-title">
                                <h2>Video Demo App</h2>
                            </div>
                            <div class="text">Retarget past customers with second-chance offers and reach new audiences
                                with geo-targeted campaigns during peak dining times using Forks’ push notifications.</div>
                            <div class="btn-box"><a href="#" class="theme-btn-two">Play Video Now</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- video-section end -->


    <!-- pricing-section -->
    <section class="pricing-section centred">
        <div class="container">
            <div class="sec-title center">
                <h2>Our Best Price Plan</h2>
                <p>We provide best price plan for our customer check the list now<br />and slect now plan.</p>
            </div>
            <div class="tabs-box">
                <div class="tabs-content">
                    <div class="tab active-tab" id="tab-1">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-1.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">Basic</h3>
                                            <h2 class="price">05.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-2.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">Premium</h3>
                                            <h2 class="price">25.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                                <li>Phone Support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-3.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">PROFESSIONAL</h3>
                                            <h2 class="price">50.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                                <li>Phone Support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab" id="tab-2">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-1.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">Basic</h3>
                                            <h2 class="price">30.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-2.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">Premium</h3>
                                            <h2 class="price">60.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                                <li>Phone Support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12 pricing-column">
                                <div class="pricing-block-one">
                                    <div class="pricing-table">
                                        <figure class="image"><img
                                                src="{{ theme_asset('assets/images/icons/price-icon-3.png') }}"
                                                alt=""></figure>
                                        <div class="table-header">
                                            <h3 class="title">PROFESSIONAL</h3>
                                            <h2 class="price">99.00<span>/Mo</span></h2>
                                        </div>
                                        <div class="table-content">
                                            <ul>
                                                <li>One User</li>
                                                <li>Ui elements 1000</li>
                                                <li>E-mail support</li>
                                                <li>Phone Support</li>
                                            </ul>
                                        </div>
                                        <div class="table-footer">
                                            <a href="#" class="theme-btn-two">Purchase</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-btn-box">
                    <ul class="tab-btns tab-buttons clearfix">
                        <li class="tab-btn active-btn" data-tab="#tab-1">Monthly</li>
                        <li class="tab-btn" data-tab="#tab-2">Yearly</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <!-- pricing-section end -->


    <!-- testimonial-section -->
    <section class="testimonial-section centred">
        <div class="image-layer"
            style="background-image: url({{ theme_asset('assets/images/icons/testimonial-bg.png') }});"></div>
        <div class="container">
            <div class="sec-title center">
                <h2>Our Users Review</h2>
                <p>Trusted by more than 9,000 businesses in 140 countries.<br />all of our resources are free</p>
            </div>
            <div class="testimonial-carousel owl-carousel owl-theme">
                <div class="testimonial-inner">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-1.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">TeamSnap</h5>
                                        <span class="designation">VP of Customer Experience</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-2.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">Steven smith</h5>
                                        <span class="designation">Programmer Doritibe</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-inner">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-2.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">Steven smith</h5>
                                        <span class="designation">Programmer Doritibe</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-1.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">TeamSnap</h5>
                                        <span class="designation">VP of Customer Experience</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="testimonial-inner">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-1.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">TeamSnap</h5>
                                        <span class="designation">VP of Customer Experience</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12 testimonial-block">
                            <div class="testimonial-block-one">
                                <div class="inner-box">
                                    <figure class="image-box"><img
                                            src="{{ theme_asset('assets/images/resource/testimonial-2.png') }}"
                                            alt=""></figure>
                                    <div class="text">“We don't take ourselves too seriously, but seriously enough to
                                        ensure we're creating the best product and experience for our customers. I feel like
                                        Help Scout does the same.”</div>
                                    <div class="author-info">
                                        <h5 class="name">Steven smith</h5>
                                        <span class="designation">Programmer Doritibe</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- testimonial-section end -->


    <!-- download-section -->
    <section class="download-section">
        <div class="bg-layer wow slideInLeft animated"
            style="background-image: url({{ theme_asset('assets/images/icons/shap-3.png') }});"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div id="content_block_05">
                        <div class="content-box wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <div class="sec-title">
                                <h2>Get The App Now!</h2>
                            </div>
                            <div class="text">Have you ever heard the expression,<br />“Do not count your chickens before
                                they hatch?” Maybe an older, wiser individual</div>
                            <div class="download-btn">
                                <a href="#" class="app-store-btn">
                                    <i class="fab fa-apple"></i>
                                    <span>Download on the</span>
                                    App Store
                                </a>
                                <a href="#" class="google-play-btn">
                                    <i class="fab fa-android"></i>
                                    <span>Get on it</span>
                                    Google Play
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div id="iamge_block_04">
                        <div class="image-box">
                            <figure class="image image-1 wow slideInUp" data-wow-delay="300ms"
                                data-wow-duration="1500ms"><img
                                    src="{{ theme_asset('assets/images/resource/phone-4.png') }}" alt="">
                            </figure>
                            <figure class="image image-2 wow slideInUp" data-wow-delay="600ms"
                                data-wow-duration="1500ms"><img
                                    src="{{ theme_asset('assets/images/resource/phone-5.png') }}" alt="">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- download-section end -->


    <!-- news-section -->
    <section class="news-section">
        <div class="container">
            <div class="sec-title center">
                <h2>News & Events</h2>
                <p>Trusted by more than 9,000 businesses in 140 countries.<br />all of our resources are free</p>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 news-column">
                    <div class="news-block-one wow flipInY animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                        <div class="inner-box">
                            <figure class="image-box"><a href="blog-single.html"><img
                                        src="{{ theme_asset('assets/images/resource/news-1.jpg') }}" alt=""></a>
                            </figure>
                            <div class="lower-content">
                                <div class="post-date"><i class="fas fa-calendar-alt"></i>January 11, 2019</div>
                                <h3><a href="blog-single.html">Design your apps in your own way Business Startegies</a>
                                </h3>
                                <div class="link-btn"><a href="blog-single.html">Read More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 news-column">
                    <div class="news-block-one wow flipInY animated" data-wow-delay="300ms" data-wow-duration="1500ms">
                        <div class="inner-box">
                            <figure class="image-box"><a href="blog-single.html"><img
                                        src="{{ theme_asset('assets/images/resource/news-2.jpg') }}" alt=""></a>
                            </figure>
                            <div class="lower-content">
                                <div class="post-date"><i class="fas fa-calendar-alt"></i>January 10, 2019</div>
                                <h3><a href="blog-single.html">We support our user every time and upgarad our app.</a></h3>
                                <div class="link-btn"><a href="blog-single.html">Read More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 news-column">
                    <div class="news-block-one wow flipInY animated" data-wow-delay="600ms" data-wow-duration="1500ms">
                        <div class="inner-box">
                            <figure class="image-box"><a href="blog-single.html"><img
                                        src="{{ theme_asset('assets/images/resource/news-3.jpg') }}" alt=""></a>
                            </figure>
                            <div class="lower-content">
                                <div class="post-date"><i class="fas fa-calendar-alt"></i>January 09, 2019</div>
                                <h3><a href="blog-single.html">We developed the app for our customer to easy to use it.</a>
                                </h3>
                                <div class="link-btn"><a href="blog-single.html">Read More</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- news-section end -->


    <!-- subscribe-section -->
    <section class="subscribe-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 image-column">
                    <div id="iamge_block_05">
                        <div class="image-box wow slideInLeft animated" data-wow-delay="00ms" data-wow-duration="1500ms">
                            <figure class="image float-bob-y"><img
                                    src="{{ theme_asset('assets/images/resource/subscribe-1.png') }}" alt="">
                            </figure>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 content-column">
                    <div id="content_block_06">
                        <div class="content-box">
                            <div class="sec-title">
                                <h2>Subscribe our Newsletter</h2>
                            </div>
                            <div class="text">Lorem ipsum dolor sit amet consectetur adipiscing elit donec tempus
                                pellentesque dui vel tristique purus justo</div>
                            <form action="#" method="post" class="subscribe-form">
                                <div class="form-group">
                                    <input type="email" name="email" placeholder="Enter Your Email" required="">
                                    <button type="submit" class="theme-btn-two">Subscribe Now</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- subscribe-section end -->
@endsection
