<!doctype html>
<html lang="en">

  <head>
    <title>Bengkel Mobil &mdash; by Raysha </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://fonts.googleapis.com/css?family=DM+Sans:300,400,700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">

    <link rel="stylesheet" href="{{asset('fe/assets/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/css/bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/css/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/css/owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/css/owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/fonts/flaticon/font/flaticon.css')}}">
    <link rel="stylesheet" href="{{asset('fe/assets/css/aos.css')}}">

    <!-- MAIN CSS -->
    <link rel="stylesheet" href="{{asset('fe/assets/css/style.css')}}">
    <style>
      :root {
        --fe-ink: #0f172a;
        --fe-accent: #0f766e;
        --fe-accent-soft: #155e75;
        --fe-shell: #f8fafc;
      }
      body {
        background:
          radial-gradient(circle at top left, rgba(13, 148, 136, 0.16), transparent 22%),
          radial-gradient(circle at bottom right, rgba(14, 116, 144, 0.14), transparent 24%),
          linear-gradient(180deg, #eff6ff 0%, #ffffff 22%, #f8fafc 100%);
      }
      .client-account-menu { position: relative; display: inline-block; max-width: 220px; }
      .client-account-trigger { display: inline-flex; align-items: center; gap: 8px; }
      .client-account-name {
        max-width: 150px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
        vertical-align: bottom;
      }
      .client-nav-cta {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 8px 12px !important;
        border-radius: 999px;
        background: transparent;
        color: rgba(241, 245, 249, .92) !important;
        box-shadow: none;
        font-size: 13px !important;
      }
      .client-nav-cta::after { display: none !important; }
      .client-nav-cta:hover { color: #fff !important; opacity: 1; }
      .client-nav-link-badge {
        display: inline-flex;
        align-items: center;
        padding: 8px 12px;
        border-radius: 999px;
        color: rgba(255,255,255,.92) !important;
        background: rgba(255,255,255,.06);
      }
      .site-navbar {
        padding-top: 12px;
      }
      .site-navbar .brand-cluster {
        display: inline-flex;
        align-items: center;
        gap: 10px;
      }
      .site-navbar .brand-logo {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        object-fit: contain;
        background: rgba(255,255,255,.12);
        padding: 3px;
      }
      .site-navbar .container {
        background: rgba(7, 15, 30, 0.94);
        backdrop-filter: blur(18px);
        border-radius: 22px;
        padding: 10px 16px;
        box-shadow: 0 18px 38px rgba(2, 8, 23, 0.28);
        border: 1px solid rgba(148, 163, 184, 0.2);
      }
      .site-logo a {
        font-family: "DM Sans", sans-serif;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        font-size: 20px;
        color: #f8fafc !important;
      }
      .site-navbar .site-navigation .site-menu {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        flex-wrap: nowrap;
      }
      .site-mobile-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 10px;
        background: rgba(255,255,255,.1);
      }
      .site-navbar .site-navigation .site-menu > li {
        margin: 0;
      }
      .site-navbar .site-navigation .site-menu > li > a.nav-link {
        position: relative;
        display: inline-block;
        padding: 8px 10px !important;
        border-radius: 0;
        background: transparent;
        backdrop-filter: none;
        transition: color .2s ease, opacity .2s ease;
        white-space: nowrap;
        font-size: 14px;
        color: rgba(241, 245, 249, .92) !important;
      }
      .site-navbar .site-navigation .site-menu > li > a.nav-link.nav-link-pill {
        padding: 8px 14px !important;
        border-radius: 999px;
        background: transparent;
      }
      .site-navbar .site-navigation .site-menu > li > a.nav-link::after {
        content: "";
        position: absolute;
        left: 8px;
        right: 8px;
        bottom: 4px;
        height: 2px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.85);
        transform: scaleX(0);
        transform-origin: center;
        transition: transform .2s ease, background-color .2s ease;
      }
      .site-navbar .site-navigation .site-menu > li.active > a.nav-link,
      .site-navbar .site-navigation .site-menu > li > a.nav-link:hover {
        background: transparent;
        opacity: 1;
        color: #ffffff !important;
      }
      .site-navbar .site-navigation .site-menu > li.active > a.nav-link.nav-link-pill,
      .site-navbar .site-navigation .site-menu > li > a.nav-link.nav-link-pill:hover {
        background: rgba(255,255,255,.14);
      }
      .site-navbar .site-navigation .site-menu > li.active > a.client-nav-cta,
      .site-navbar .site-navigation .site-menu > li > a.client-nav-cta:hover {
        background: rgba(20, 184, 166, .22);
        box-shadow: inset 0 0 0 1px rgba(45, 212, 191, .28);
      }
      .site-navbar .site-navigation .site-menu > li.active > a.nav-link::after,
      .site-navbar .site-navigation .site-menu > li > a.nav-link:hover::after {
        transform: scaleX(1);
      }
      .client-account-dropdown {
        position: absolute;
        top: calc(100% + 10px);
        right: 0;
        min-width: 180px;
        padding: 10px 0;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 18px 45px rgba(15, 23, 42, 0.16);
        opacity: 0;
        visibility: hidden;
        transform: translateY(8px);
        transition: all .2s ease;
        z-index: 30;
      }
      .site-navbar .site-navigation {
        overflow: visible;
      }
      .client-account-menu:hover .client-account-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
      }
      .client-account-dropdown a,
      .client-account-dropdown button {
        width: 100%;
        display: block;
        padding: 10px 16px;
        border: 0;
        background: transparent;
        text-align: left;
        color: #0f172a;
        font-weight: 600;
      }
      .client-account-dropdown a:hover,
      .client-account-dropdown button:hover {
        background: #f8fafc;
      }
      .site-footer {
        background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
      }
      @media (max-width: 991.98px) {
        .site-navbar .container {
          border-radius: 16px;
          padding: 10px 12px;
        }
        .site-logo a {
          font-size: 14px;
          letter-spacing: .02em;
        }
        .site-navbar .brand-logo {
          width: 32px;
          height: 32px;
        }
      }
    </style>
  </head>

  <body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    @php($frontendUser = auth('client')->user())

    
    <div class="site-wrap" id="home-section">

      <div class="site-mobile-menu site-navbar-target">
        <div class="site-mobile-menu-header">
          <div class="site-mobile-menu-close mt-3">
            <span class="icon-close2 js-menu-toggle"></span>
          </div>
        </div>
        <div class="site-mobile-menu-body"></div>
      </div>



      <header class="site-navbar site-navbar-target" role="banner">

        <div class="container">
          <div class="row align-items-center position-relative">

            <div class="col-lg-3 col-8 d-flex align-items-center">
              <div class="site-logo">
                <a href="{{ route('home') }}" class="brand-cluster">
                  <img src="{{ asset('be/assets/img/logo.png') }}" alt="Raxy Garage" class="brand-logo">
                  <span>Raxy Garage</span>
                </a>
              </div>
            </div>

            <div class="col-lg-9 col-4 text-right">
              

              <span class="d-inline-block d-lg-none"><a href="#" class="text-white site-menu-toggle js-menu-toggle site-mobile-toggle"><span class="icon-menu text-white"></span></a></span>

              

              <nav class="site-navigation text-right ml-auto d-none d-lg-block" role="navigation">
                <ul class="site-menu main-menu js-clone-nav ml-auto ">
                  <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-pill' : '' }}">Home</a></li>
                  <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}" class="nav-link">About</a></li>
                  <li class="{{ request()->routeIs('client.booking.index') ? 'active' : '' }}"><a href="{{ route('client.booking.index') }}" class="nav-link {{ request()->routeIs('client.booking.index') ? 'nav-link-pill' : '' }}">Booking</a></li>
                  <li><a href="{{ route('home') }}#layanan-section" class="nav-link">Layanan</a></li>
                  <li class="{{ request()->routeIs('client.rating.*') ? 'active' : '' }}"><a href="{{ route('client.rating.index') }}" class="nav-link {{ request()->routeIs('client.rating.*') ? 'nav-link-pill' : '' }}">Rating Bengkel</a></li>
                  @if($frontendUser?->role === 'customer')
                    <li class="{{ request()->routeIs('client.chat.*') ? 'active' : '' }}"><a href="{{ route('client.chat.index') }}" class="nav-link {{ request()->routeIs('client.chat.*') ? 'nav-link-pill' : '' }}">Chat Admin</a></li>
                  @endif
                  @guest('client')
                    <li><a href="{{ route('client.login') }}" class="nav-link">Login</a></li>
                    <li><a href="{{ route('client.register') }}" class="nav-link">Register</a></li>
                  @else
                    @if($frontendUser?->role === 'customer')
                      <li class="client-account-menu">
                        <a href="{{ route('client.profile') }}" class="nav-link {{ request()->routeIs('client.profile') ? 'nav-link-pill' : '' }} client-account-trigger">
                          <span class="client-account-name">{{ $frontendUser->name }}</span>
                          <span style="font-size: 11px;">▼</span>
                        </a>
                        <div class="client-account-dropdown">
                          <a href="{{ route('client.profile') }}">My Profile</a>
                          <a href="{{ route('client.history') }}">Riwayat</a>
                          <form method="POST" action="{{ route('client.logout') }}">
                            @csrf
                            <button type="submit">Logout</button>
                          </form>
                        </div>
                      </li>
                    @endif
                  @endguest
                </ul>
              </nav>
            </div>

            
          </div>
        </div>

      </header>
    @hasSection('content')
    @yield('content')
    @else
    @yield('hero')
    @endif

    <!-- <div class="ftco-blocks-cover-1">
      <div class="ftco-cover-1 overlay" style="background-image: url('images/hero_1.jpg')">
        <div class="container">
          <div class="row align-items-center">
            <div class="col-lg-5">
              <div class="feature-car-rent-box-1">
                <h3>Range Rover S7</h3>
                <ul class="list-unstyled">
                  <li>
                    <span>Doors</span>
                    <span class="spec">4</span>
                  </li>
                  <li>
                    <span>Seats</span>
                    <span class="spec">6</span>
                  </li>
                  <li>
                    <span>Lugage</span>
                    <span class="spec">2 Suitcase/2 Bags</span>
                  </li>
                  <li>
                    <span>Transmission</span>
                    <span class="spec">Automatic</span>
                  </li>
                  <li>
                    <span>Minium age</span>
                    <span class="spec">Automatic</span>
                  </li>
                </ul>
                <div class="d-flex align-items-center bg-light p-3">
                  <span>$150/day</span>
                  <a href="contact.html" class="ml-auto btn btn-primary">Rent Now</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="site-section pt-0 pb-0 bg-light">
      <div class="container">
        <div class="row">
          <div class="col-12">
            
              <form class="trip-form">
                <div class="row align-items-center mb-4">
                  <div class="col-md-6">
                    <h3 class="m-0">Begin your trip here</h3>
                  </div>
                  <div class="col-md-6 text-md-right">
                    <span class="text-primary">32</span> <span>cars available</span></span>
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-12 col-sm-6 col-md-3">
                    <label for="cf-1">Where you from</label>
                    <input type="text" id="cf-1" placeholder="Your pickup address" class="form-control">
                  </div>
                  <div class="form-group col-12 col-sm-6 col-md-3">
                    <label for="cf-2">Where you go</label>
                    <input type="text" id="cf-2" placeholder="Your drop-off address" class="form-control">
                  </div>
                  <div class="form-group col-12 col-sm-6 col-md-3">
                    <label for="cf-3">Journey date</label>
                    <input type="text" id="cf-3" placeholder="Your pickup address" class="form-control datepicker px-3">
                  </div>
                  <div class="form-group col-12 col-sm-6 col-md-3">
                    <label for="cf-4">Return date</label>
                    <input type="text" id="cf-4" placeholder="Your pickup address" class="form-control datepicker px-3">
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6">
                    <input type="submit" value="Submit" class="btn btn-primary">
                  </div>
                </div>
              </form>
            </div>
        </div>
      </div>
    </div>

    

    <div class="site-section bg-light">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <h3>Our Offer</h3>
            <p class="mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure nesciunt nemo vel earum maxime neque!</p>
            <p>
              <a href="#" class="btn btn-primary custom-prev">Previous</a>
              <span class="mx-2">/</span>
              <a href="#" class="btn btn-primary custom-next">Next</a>
            </p>
          </div>
          <div class="col-lg-9">




            <div class="nonloop-block-13 owl-carousel">
              <div class="item-1">
                <a href="#"><img src="images/img_1.jpg" alt="Image" class="img-fluid"></a>
                <div class="item-1-contents">
                  <div class="text-center">
                  <h3><a href="#">Range Rover S64 Coupe</a></h3>
                  <div class="rating">
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                  </div>
                  <div class="rent-price"><span>$250/</span>day</div>
                  </div>
                  <ul class="specs">
                    <li>
                      <span>Doors</span>
                      <span class="spec">4</span>
                    </li>
                    <li>
                      <span>Seats</span>
                      <span class="spec">5</span>
                    </li>
                    <li>
                      <span>Transmission</span>
                      <span class="spec">Automatic</span>
                    </li>
                    <li>
                      <span>Minium age</span>
                      <span class="spec">18 years</span>
                    </li>
                  </ul>
                  <div class="d-flex action">
                    <a href="contact.html" class="btn btn-primary">Rent Now</a>
                  </div>
                </div>
              </div>


              <div class="item-1">
                <a href="#"><img src="images/img_2.jpg" alt="Image" class="img-fluid"></a>
                <div class="item-1-contents">
                  <div class="text-center">
                  <h3><a href="#">Range Rover S64 Coupe</a></h3>
                  <div class="rating">
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                    <span class="icon-star text-warning"></span>
                  </div>
                  <div class="rent-price"><span>$250/</span>day</div>
                  </div>
                  <ul class="specs">
                    <li>
                      <span>Doors</span>
                      <span class="spec">4</span>
                    </li>
                    <li>
                      <span>Seats</span>
                      <span class="spec">5</span>
                    </li>
                    <li>
                      <span>Transmission</span>
                      <span class="spec">Automatic</span>
                    </li>
                    <li>
                      <span>Minium age</span>
                      <span class="spec">18 years</span>
                    </li>
                  </ul>
                  <div class="d-flex action">
                    <a href="contact.html" class="btn btn-primary">Rent Now</a>
                  </div>
                </div>
              </div>

            </div>
            
          </div>
        </div>
      </div>
    </div>

    <div class="site-section section-3" style="background-image: url('images/hero_2.jpg');">
      <div class="container">
        <div class="row">
          <div class="col-12 text-center mb-5">
            <h2 class="text-white">Our services</h2>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4">
            <div class="service-1">
              <span class="service-1-icon">
                <span class="flaticon-car-1"></span>
              </span>
              <div class="service-1-contents">
                <h3>Repair</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati, laboriosam.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="service-1">
              <span class="service-1-icon">
                <span class="flaticon-traffic"></span>
              </span>
              <div class="service-1-contents">
                <h3>Car Accessories</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati, laboriosam.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4">
            <div class="service-1">
              <span class="service-1-icon">
                <span class="flaticon-valet"></span>
              </span>
              <div class="service-1-contents">
                <h3>Own a Car</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati, laboriosam.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="container site-section mb-5">
      <div class="row justify-content-center text-center">
        <div class="col-7 text-center mb-5">
          <h2>How it works</h2>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo assumenda, dolorum necessitatibus eius earum voluptates sed!</p>
        </div>
      </div>
      <div class="how-it-works d-flex">
        <div class="step">
          <span class="number"><span>01</span></span>
          <span class="caption">Time &amp; Place</span>
        </div>
        <div class="step">
          <span class="number"><span>02</span></span>
          <span class="caption">Car</span>
        </div>
        <div class="step">
          <span class="number"><span>03</span></span>
          <span class="caption">Details</span>
        </div>
        <div class="step">
          <span class="number"><span>04</span></span>
          <span class="caption">Checkout</span>
        </div>
        <div class="step">
          <span class="number"><span>05</span></span>
          <span class="caption">Done</span>
        </div>

      </div>
    </div>
    
    
    <div class="site-section bg-light">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-7 text-center mb-5">
            <h2>Customer Testimony</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo assumenda, dolorum necessitatibus eius earum voluptates sed!</p>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="testimonial-2">
              <blockquote class="mb-4">
                <p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, deserunt eveniet veniam. Ipsam, nam, voluptatum"</p>
              </blockquote>
              <div class="d-flex v-card align-items-center">
                <img src="images/person_1.jpg" alt="Image" class="img-fluid mr-3">
                <span>Mike Fisher</span>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="testimonial-2">
              <blockquote class="mb-4">
                <p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, deserunt eveniet veniam. Ipsam, nam, voluptatum"</p>
              </blockquote>
              <div class="d-flex v-card align-items-center">
                <img src="images/person_2.jpg" alt="Image" class="img-fluid mr-3">
                <span>Jean Stanley</span>
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="testimonial-2">
              <blockquote class="mb-4">
                <p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptatem, deserunt eveniet veniam. Ipsam, nam, voluptatum"</p>
              </blockquote>
              <div class="d-flex v-card align-items-center">
                <img src="images/person_3.jpg" alt="Image" class="img-fluid mr-3">
                <span>Katie Rose</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <div class="site-section bg-white">
      <div class="container">
        <div class="row justify-content-center text-center mb-5">
          <div class="col-7 text-center mb-5">
            <h2>Our Blog</h2>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo assumenda, dolorum necessitatibus eius earum voluptates sed!</p>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="post-entry-1 h-100">
              <a href="single.html">
                <img src="images/post_1.jpg" alt="Image"
                 class="img-fluid">
              </a>
              <div class="post-entry-1-contents">
                
                <h2><a href="single.html">The best car rent in the entire planet</a></h2>
                <span class="meta d-inline-block mb-3">July 17, 2019 <span class="mx-2">by</span> <a href="#">Admin</a></span>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores eos soluta, dolore harum molestias consectetur.</p>
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-6 mb-4">
            <div class="post-entry-1 h-100">
              <a href="single.html">
                <img src="images/img_2.jpg" alt="Image"
                 class="img-fluid">
              </a>
              <div class="post-entry-1-contents">
                
                <h2><a href="single.html">The best car rent in the entire planet</a></h2>
                <span class="meta d-inline-block mb-3">July 17, 2019 <span class="mx-2">by</span> <a href="#">Admin</a></span>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores eos soluta, dolore harum molestias consectetur.</p>
              </div>
            </div>
          </div>

          <div class="col-lg-4 col-md-6 mb-4">
            <div class="post-entry-1 h-100">
              <a href="single.html">
                <img src="images/img_3.jpg" alt="Image"
                 class="img-fluid">
              </a>
              <div class="post-entry-1-contents">
                
                <h2><a href="single.html">The best car rent in the entire planet</a></h2>
                <span class="meta d-inline-block mb-3">July 17, 2019 <span class="mx-2">by</span> <a href="#">Admin</a></span>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolores eos soluta, dolore harum molestias consectetur.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

     -->

    <footer class="site-footer">
      <div class="container">
        <div class="row">
          <div class="col-lg-3">
            <h2 class="footer-heading mb-4">About Us</h2>
                <p>Far far away, behind the word mountains, far from the countries Vokalia and Consonantia, there live the blind texts. </p>
          </div>
          <div class="col-lg-8 ml-auto">
            <div class="row">
              <div class="col-lg-3">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Testimonials</a></li>
                  <li><a href="#">Terms of Service</a></li>
                  <li><a href="#">Privacy</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </div>
              <div class="col-lg-3">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Testimonials</a></li>
                  <li><a href="#">Terms of Service</a></li>
                  <li><a href="#">Privacy</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </div>
              <div class="col-lg-3">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Testimonials</a></li>
                  <li><a href="#">Terms of Service</a></li>
                  <li><a href="#">Privacy</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </div>
              <div class="col-lg-3">
                <h2 class="footer-heading mb-4">Quick Links</h2>
                <ul class="list-unstyled">
                  <li><a href="#">About Us</a></li>
                  <li><a href="#">Testimonials</a></li>
                  <li><a href="#">Terms of Service</a></li>
                  <li><a href="#">Privacy</a></li>
                  <li><a href="#">Contact Us</a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
          <div class="col-md-12">
            <div class="border-top pt-5">
              <p>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="icon-heart text-danger" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank" >Colorlib</a>
            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
            </p>
            </div>
          </div>

        </div>
      </div>
    </footer>

    </div>

    <script src="{{asset('fe/assets/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/popper.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/jquery.sticky.js')}}"></script>
    <script src="{{asset('fe/assets/js/jquery.waypoints.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/jquery.animateNumber.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/jquery.fancybox.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/jquery.easing.1.3.js')}}"></script>
    <script src="{{asset('fe/assets/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('fe/assets/js/aos.js')}}"></script>

    <script src="{{asset('fe/assets/js/main.js')}}"></script>
    @auth
    <script src="{{asset('fe/assets/js/session-timeout.js')}}"></script>
    @endauth

  </body>

</html>

