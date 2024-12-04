

@include('layouts.header_links')

 <!-- header-area-start -->
 <header class="header header-2 sticky-active">

    <div class="primary-header">
        <div class="container">
            <div class="primary-header-inner">
                <div class="header-logo d-lg-block">
                    <a href="{{ route('home') }}">
                        <img src="{{ url('/assets/img/logo/logo20.png') }}" alt="Logo">
                    </a>
                </div>
                <div class="header-right-wrap">
                    <div class="header-menu-wrap">
                        <div class="mobile-menu-items">
                            <ul class="sub-menu">
                                <li class="active">
                                    <a href="{{ route('home') }}">Home</a>
                                </li>
                                <li >
                                    <a href="{{ route('about') }}">About</a>
                                </li>
                                <li class="menu-item-has-children">
                                    <a href="#">Services</a>
                                    <ul>
                                        <li><a href="{{ route('thesis') }}">Thesis writing & Editing</a></li>
                                        <li> <a  href="{{ route('research') }}">Research Assistance for
                                            Extraordinary Visa</a></li>
                                    <li> <a  href="{{ route('junior') }}">Junior Researcher Program</a></li>
                                    <li><a  href="{{ route('t&c') }}">Technology & Consulting</a></li>
                                    </ul>
                                </li>
                                <li>
                                    <a href="{{ route('howItWork') }}">How It Works</a>
                                </li>

                                <li>
                                    <a href="https://www.youtube.com/nextmind" target="_blank">Podcast</a>
                                </li>
                                <li><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- /.header-menu-wrap -->
                    <div class="header-right">

                        <a href="https://metaxur.paperform.co/" target="_blank" class="ed-primary-btn header-btn">SCHEDULE A FREE CALL <i class="fa-sharp fa-regular fa-arrow-right"></i></a>
                        <div class="header-logo d-none d-lg-none">
                            <a href="index-2.html">
                                <img src="{{ url('/assets/img/logo/logo20.png') }}" alt="Logo">
                            </a>
                        </div>
                        <div class="header-right-item d-lg-none d-md-block">
                            <a href="javascript:void(0)" class="mobile-side-menu-toggle"
                                ><i class="fa-sharp fa-solid fa-bars"></i
                            ></a>
                        </div>
                    </div>
                    <!-- /.header-right -->
                </div>
            </div>
            <!-- /.primary-header-inner -->
        </div>
    </div>
</header>
<!-- /.Main Header -->



<div class="mobile-side-menu">
    <div class="side-menu-content">
        <div class="side-menu-head">
            <a href="{{ route('home') }}"><img src="{{ url('/assets/img/logo/logo20.png') }}" alt="logo"></a>
            <button class="mobile-side-menu-close"><i class="fa-regular fa-xmark"></i></button>
        </div>
        <div class="side-menu-wrap"></div>

    </div>
</div>
<!-- /.mobile-side-menu -->
<div class="mobile-side-menu-overlay"></div>



