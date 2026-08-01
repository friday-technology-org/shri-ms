<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">

    {!! cms_seo_tags($page ?? $post ?? null) !!}

    <!-- Fav Icon -->
    <link rel="icon" href="{{ cms_favicon('ico') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Ubuntu:300,300i,400,400i,500,500i,700,700i&display=swap"
        rel="stylesheet">

    <!-- Stylesheets -->
    <link href="{{ theme_asset('assets/css/font-awesome-all.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/flaticon.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/owl.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/jquery.fancybox.min.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/imagebg.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/style.css') }}" rel="stylesheet">
    <link href="{{ theme_asset('assets/css/responsive.css') }}" rel="stylesheet">

</head>


<!-- page wrapper -->

<body class="boxed_wrapper">

    <!-- preloader -->
    <div class="preloader"></div>
    <!-- preloader -->

    <!-- main header -->
    <header class="main-header home-1">
        <div class="outer-container">
            <div class="container">
                <div class="main-box clearfix">
                    <div class="logo-box pull-left">
                        <figure class="logo"><a href="index.html"><img src="{{ cms_logo('header') }}"
                                    alt=""></a>
                        </figure>
                    </div>
                    <div class="menu-area pull-right">
                        <!--Mobile Navigation Toggler-->
                        <div class="mobile-nav-toggler">
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                            <i class="icon-bar"></i>
                        </div>
                        <nav class="main-menu navbar-expand-md navbar-light">
                            <div class="collapse navbar-collapse show clearfix" id="navbarSupportedContent">
                                <ul class="navigation clearfix">
                                    @foreach (cms_menu_items('primary') as $item)
                                        @if (count($item->children ?? []) > 0)
                                            <li class="dropdown"><a
                                                    href="{{ $item->resolvedUrl() }}">{{ $item->label }}</a>
                                                <ul>
                                                    @foreach ($item->children as $child)
                                                        <li><a
                                                                href="{{ $child->resolvedUrl() }}">{{ $child->label }}</a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </li>
                                        @else
                                            <li><a href="{{ $item->resolvedUrl() }}">{{ $item->label }}</a></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!--sticky Header-->
        <div class="sticky-header">
            <div class="container clearfix">
                <figure class="logo-box"><a href="index.html"><img src="{{ cms_logo('header') }}" alt=""></a>
                </figure>
                <div class="menu-area">
                    <nav class="main-menu clearfix">
                        <!--Keep This Empty / Menu will come through Javascript-->
                    </nav>
                </div>
            </div>
        </div>
    </header>
    <!-- main-header end -->

    <!-- Mobile Menu  -->
    <div class="mobile-menu">
        <div class="menu-backdrop"></div>
        <div class="close-btn"><i class="fas fa-times"></i></div>

        <nav class="menu-box">
            <div class="nav-logo"><a href="index.html"><img src="{{ theme_asset('assets/images/logo.png') }}"
                        alt="" title=""></a></div>
            <div class="menu-outer"><!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
            </div>
            <div class="contact-info">
                <h4>Contact Info</h4>
                <ul>
                    <li>{{ cms_option('customizer_contact_address') ?: 'Chicago 12, Melborne City, USA' }}</li>
                    <li><a
                            href="tel:{{ preg_replace('/[^0-9+]/', '', cms_option('customizer_contact_phone')) }}">{{ cms_option('customizer_contact_phone') ?: '+88 01682648101' }}</a>
                    </li>
                    <li><a
                            href="mailto:{{ cms_option('customizer_contact_email') }}">{{ cms_option('customizer_contact_email') ?: 'info@example.com' }}</a>
                    </li>
                </ul>
            </div>
            <div class="social-links">
                <ul class="clearfix">
                    @if (cms_option('customizer_social_facebook'))
                        <li><a href="{{ cms_option('customizer_social_facebook') }}" target="_blank"><span
                                    class="fab fa-facebook-square"></span></a></li>
                    @endif
                    @if (cms_option('customizer_social_instagram'))
                        <li><a href="{{ cms_option('customizer_social_instagram') }}" target="_blank"><span
                                    class="fab fa-instagram"></span></a></li>
                    @endif
                    @if (cms_option('customizer_social_tiktok'))
                        <li><a href="{{ cms_option('customizer_social_tiktok') }}" target="_blank"><span
                                    class="fab fa-tiktok"></span></a></li>
                    @endif
                    @if (cms_option('customizer_social_linkedin'))
                        <li><a href="{{ cms_option('customizer_social_linkedin') }}" target="_blank"><span
                                    class="fab fa-linkedin-in"></span></a></li>
                    @endif
                    @if (cms_option('customizer_social_youtube'))
                        <li><a href="{{ cms_option('customizer_social_youtube') }}" target="_blank"><span
                                    class="fab fa-youtube"></span></a></li>
                    @endif
                </ul>
            </div>
        </nav>
    </div><!-- End Mobile Menu -->
    @yield('content')


    <!-- main-footer -->
    <footer class="main-footer">
        <div class="image-layer"
            style="background-image: url({{ theme_asset('assets/images/icons/footer-bg.png') }});"></div>
        <div class="container">
            <div class="footer-top">
                <div class="widget-section">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 col-sm-12 footer-column">
                            <div class="about-widget footer-widget">
                                <figure class="footer-logo"><a href="index.html"><img src="{{ cms_logo('footer') }}"
                                            alt=""></a></figure>
                                <div class="text">Lorem ipsum dolor sit consectetur adipisicing elit, sed do eiusmod
                                    tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim lorem sed do
                                    eiusmod.</div>
                                <ul class="social-links">
                                    <li>
                                        <h6>Follow Us :</h6>
                                    </li>
                                    @if (cms_option('customizer_social_facebook'))
                                        <li><a href="{{ cms_option('customizer_social_facebook') }}"
                                                target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                                    @endif
                                    @if (cms_option('customizer_social_instagram'))
                                        <li><a href="{{ cms_option('customizer_social_instagram') }}"
                                                target="_blank"><i class="fab fa-instagram"></i></a></li>
                                    @endif
                                    @if (cms_option('customizer_social_tiktok'))
                                        <li><a href="{{ cms_option('customizer_social_tiktok') }}" target="_blank"><i
                                                    class="fab fa-tiktok"></i></a></li>
                                    @endif
                                    @if (cms_option('customizer_social_linkedin'))
                                        <li><a href="{{ cms_option('customizer_social_linkedin') }}"
                                                target="_blank"><i class="fab fa-linkedin-in"></i></a></li>
                                    @endif
                                    @if (cms_option('customizer_social_youtube'))
                                        <li><a href="{{ cms_option('customizer_social_youtube') }}"
                                                target="_blank"><i class="fab fa-youtube"></i></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">Support</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        <li><a href="#">Contact Us</a></li>
                                        <li><a href="#">Submit a Ticket</a></li>
                                        <li><a href="#">Visit Knowledge Base</a></li>
                                        <li><a href="#">Support System</a></li>
                                        <li><a href="#">Refund Policy</a></li>
                                        <li><a href="#">Professional Services</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-12 footer-column">
                            <div class="links-widget footer-widget">
                                <h4 class="widget-title">Links</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        @foreach (cms_menu_items('primary') as $item)
                                            <li><a href="{{ $item->resolvedUrl() }}">{{ $item->label }}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12 footer-column">
                            <div class="contact-widget footer-widget">
                                <h4 class="widget-title">Contact Info</h4>
                                <div class="widget-content">
                                    <ul class="list clearfix">
                                        @if (cms_option('customizer_contact_address'))
                                            <li><i
                                                    class="fas fa-map-marker-alt"></i>{{ cms_option('customizer_contact_address') }}
                                            </li>
                                        @else
                                            <li><i class="fas fa-map-marker-alt"></i>25 Bedford St.<br />New York City,
                                                N.Y.</li>
                                        @endif

                                        @if (cms_option('customizer_contact_phone'))
                                            <li>
                                                <i class="fas fa-phone-volume"></i>
                                                <a
                                                    href="tel:{{ preg_replace('/[^0-9+]/', '', cms_option('customizer_contact_phone')) }}">{{ cms_option('customizer_contact_phone') }}</a>
                                            </li>
                                        @endif

                                        @if (cms_option('customizer_contact_email'))
                                            <li>
                                                <i class="fas fa-envelope"></i>
                                                <a
                                                    href="mailto:{{ cms_option('customizer_contact_email') }}">{{ cms_option('customizer_contact_email') }}</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="copyright">&copy; 2020 <a href="{{ bloginfo('url') }}">{{ bloginfo('name') }}</a>. All
                    rights reserved</div>
            </div>
        </div>
    </footer>
    <!-- main-footer end -->



    <!--Scroll to top-->
    <button class="scroll-top scroll-to-target" data-target="html">
        <span class="fa fa-arrow-up"></span>
    </button>


    <!-- jequery plugins -->
    <script src="{{ theme_asset('assets/js/jquery.js') }}"></script>
    <script src="{{ theme_asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ theme_asset('assets/js/owl.js') }}"></script>
    <script src="{{ theme_asset('assets/js/wow.js') }}"></script>
    <script src="{{ theme_asset('assets/js/validation.js') }}"></script>
    <script src="{{ theme_asset('assets/js/jquery.fancybox.js') }}"></script>
    <script src="{{ theme_asset('assets/js/appear.js') }}"></script>
    <script src="{{ theme_asset('assets/js/scrollbar.js') }}"></script>
    <script src="{{ theme_asset('assets/js/tilt.jquery.js') }}"></script>

    <!-- main-js -->
    <script src="{{ theme_asset('assets/js/script.js') }}"></script>

</body><!-- End of .page_wrapper -->

</html>
