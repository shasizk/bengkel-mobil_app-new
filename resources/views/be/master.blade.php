<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>RAXY GARAGE</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="{{asset('be/assets/img/kaiadmin/favicon.ico')}}"
      type="image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="{{asset('be/assets/js/plugin/webfont/webfont.min.js')}}"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["{{asset('be/assets/css/fonts.min.css')}}"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{asset('be/assets/css/bootstrap.min.css')}}" />
    <link rel="stylesheet" href="{{asset('be/assets/css/plugins.min.css')}}" />
    <link rel="stylesheet" href="{{asset('be/assets/css/kaiadmin.min.css')}}" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{asset('be/assets/css/demo.css')}}" />
    <style>
      :root {
        --panel-ink: #0f172a;
        --panel-ink-soft: #64748b;
        --panel-edge: #dbe6f2;
        --panel-shell: radial-gradient(circle at top right, rgba(14, 116, 144, .08), transparent 28%), linear-gradient(180deg, #f6f9ff 0%, #eef3fb 100%);
        --panel-accent: #0b1324;
      }
      body {
        background: var(--panel-shell);
      }
      .main-panel {
        background:
          radial-gradient(circle at top left, rgba(14, 116, 144, .06), transparent 24%),
          linear-gradient(180deg, rgba(247, 250, 252, .92) 0%, rgba(241, 245, 249, .88) 100%);
      }
      .main-panel .content,
      .main-panel main,
      .main-panel .page-inner {
        background: transparent;
      }
      .sidebar[data-background-color="dark"],
      .logo-header[data-background-color="dark"] {
        background: linear-gradient(180deg, #06101f 0%, #0b1a34 100%) !important;
      }
      .sidebar .sidebar-logo {
        border-bottom: 0 !important;
        margin-bottom: 0 !important;
        padding-bottom: 0 !important;
        background: transparent !important;
      }
      .sidebar .logo-header {
        min-height: 74px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.16);
      }
      .sidebar .sidebar-wrapper {
        margin-top: 0 !important;
      }
      .logo-text,
      .sidebar .nav > .nav-item a p,
      .sidebar .nav > .nav-item a i {
        color: #e5edf7 !important;
      }
      .logo-header .logo {
        gap: 10px;
      }
      .logo-header .logo .navbar-brand {
        width: 42px;
        height: 42px;
        object-fit: contain;
        border-radius: 10px;
        background: rgba(255,255,255,.08);
        padding: 4px;
      }
      .sidebar .nav.nav-secondary {
        padding: 6px 10px 24px;
      }
      .sidebar .nav > .nav-item {
        margin-bottom: 6px;
      }
      .sidebar .nav > .nav-item > a {
        border-radius: 14px;
        min-height: 42px;
        transition: all .2s ease;
      }
      .sidebar .nav > .nav-item.active > a,
      .sidebar .nav > .nav-item > a:hover {
        background: rgba(255, 255, 255, 0.14) !important;
        transform: translateX(2px);
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.16);
      }
      .sidebar .nav > .nav-item.active > a p,
      .sidebar .nav > .nav-item.active > a i {
        color: #ffffff !important;
      }
      .sidebar .nav-section .text-section {
        color: #93a5be;
        letter-spacing: .08em;
        text-transform: uppercase;
        font-size: 11px;
      }
      .main-header,
      .footer {
        background: transparent;
      }
      .navbar-header {
        background: rgba(255, 255, 255, 0.78) !important;
        backdrop-filter: blur(16px);
        border-radius: 0 0 20px 20px;
        border: 1px solid rgba(15, 23, 42, 0.05);
        border-top: 0;
      }
      .card {
        border-radius: 18px !important;
        border: 1px solid rgba(15, 23, 42, 0.06) !important;
        box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07) !important;
      }
      .card-header {
        border-bottom: 1px solid rgba(15, 23, 42, 0.06) !important;
        background: linear-gradient(180deg, #ffffff 0%, #f8fbff 100%) !important;
      }
      .card-title,
      .card-header h4,
      .page-title {
        color: var(--panel-ink);
        font-weight: 700;
        letter-spacing: .01em;
      }
      .table {
        --bs-table-bg: transparent;
        --bs-table-striped-bg: rgba(15, 23, 42, 0.02);
        --bs-table-hover-bg: rgba(14, 116, 144, 0.06);
      }
      .table > :not(caption) > * > * {
        padding: .78rem .75rem;
        border-bottom-color: rgba(15, 23, 42, 0.08);
      }
      .table thead th {
        color: #334155;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: .06em;
        border-bottom-width: 1px;
      }
      .form-control,
      .form-select,
      .input-group-text {
        border-radius: 12px !important;
        border-color: rgba(15, 23, 42, 0.12) !important;
        background: #fff;
      }
      .form-control:focus,
      .form-select:focus {
        border-color: rgba(14, 116, 144, .48) !important;
        box-shadow: 0 0 0 3px rgba(14, 116, 144, .12) !important;
      }
      .btn {
        border-radius: 11px;
        font-weight: 600;
      }
      .btn-primary {
        box-shadow: 0 10px 18px rgba(30, 64, 175, .2);
      }
      .badge {
        border-radius: 999px;
      }
      .page-inner {
        padding-top: 20px;
        padding-bottom: 28px;
      }
      .chat-sidebar-list a:hover {
        background: #f8fafc;
      }
      .topbar-user .profile-pic {
        padding: 8px 12px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.06);
      }
      .backend-search-input {
        border-radius: 999px !important;
        border: 1px solid rgba(15, 23, 42, .12);
        background: rgba(255, 255, 255, .82);
      }
      .backend-search-input:focus {
        border-color: rgba(14, 116, 144, .5);
        box-shadow: 0 0 0 3px rgba(14, 116, 144, .12);
      }
      .topbar-nav .nav-link {
        border-radius: 12px;
      }
      .topbar-nav .nav-link:hover {
        background: rgba(15, 23, 42, .06);
      }
      .mobile-hidden-lite {
        display: inline-flex;
      }
      @media (max-width: 768px) {
        .mobile-hidden-lite {
          display: none;
        }
      }
      @media (max-width: 991.98px) {
        .main-header .container-fluid,
        .navbar-header .container-fluid {
          gap: 12px;
        }
        .card-header {
          flex-direction: column;
          align-items: flex-start !important;
          gap: 12px;
        }
        .card-header > .d-flex,
        .card-header > .d-inline-flex {
          width: 100%;
        }
        .card-header .d-flex.flex-wrap {
          width: 100%;
          justify-content: flex-start;
        }
        .table > :not(caption) > * > * {
          padding: .68rem .6rem;
        }
      }
      @media (max-width: 767.98px) {
        .page-inner {
          padding-left: 12px;
          padding-right: 12px;
        }
        .main-header .navbar-header {
          border-radius: 0;
        }
        .card {
          border-radius: 14px !important;
        }
        .card-header,
        .card-body {
          padding: 1rem;
        }
        .card-header .d-flex.flex-wrap {
          flex-direction: column;
          align-items: stretch;
        }
        .card-header .d-flex.flex-wrap > * {
          width: 100%;
        }
        .table thead th {
          font-size: 11px;
        }
      }
    </style>

    <!-- alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  </head>
  <body>
    @php($authUser = $backendAuthUser)
    @php($authRole = $authUser->role ?? null)
    @php($authPhoto = $authUser?->profile_photo_url)
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="index.html" class="logo d-flex align-items-center">
              <img
                src="{{asset('be/assets/img/logo.png')}}"
                alt="navbar brand"
                class="navbar-brand me-2"
                height="50"
              />
              <span class="logo-text fw-bold">RAXY GARAGE</span>
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item {{ request()->routeIs('admin.dashboard.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.dashboard.index') }}">
                  <i class="fas fa-home"></i>
                  <p>Dashboard</p>
                </a>
              </li>
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Menu</h4>
              </li>
              @if(in_array($authRole, ['admin', 'mekanik', 'kasir', 'owner'], true))
              <li class="nav-item {{ request()->routeIs('admin.booking.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.booking.index') }}">
                  <i class="fas fa-calendar-check"></i>
                  <p>Booking</p>
                </a>
              </li>
              @endif
              @if(in_array($authRole, ['admin', 'owner'], true))
              <li class="nav-item {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.services.index') }}">
                  <i class="fas fa-wrench"></i>
                  <p>Services</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.vehicles.index') }}">
                  <i class="fas fa-car"></i>
                  <p>Vehicles</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.spareparts.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.spareparts.index') }}">
                  <i class="fas fa-cogs"></i>
                  <p>Spareparts</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.users.index', $authRole === 'owner' ? ['role' => 'mekanik'] : []) }}">
                  <i class="fas fa-users"></i>
                  <p>{{ $authRole === 'owner' ? 'Mekanik' : 'Users' }}</p>
                </a>
              </li>
              <li class="nav-item {{ request()->routeIs('admin.ledger.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.ledger.index') }}">
                  <i class="fas fa-book-open"></i>
                  <p>Bookeeping</p>
                </a>
              </li>
              @endif
              @if(in_array($authRole, ['mekanik', 'owner'], true))
              <li class="nav-item {{ request()->routeIs('admin.attendance.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.attendance.index') }}">
                  <i class="fas fa-camera"></i>
                  <p>Presensi</p>
                </a>
              </li>
              @endif
              @if(in_array($authRole, ['admin', 'kasir', 'owner'], true))
              <li class="nav-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.transactions.index') }}">
                  <i class="fas fa-receipt"></i>
                  <p>{{ $authRole === 'kasir' ? 'Payment' : 'Transactions' }}</p>
                </a>
              </li>
              @endif
              @if($authRole === 'admin')
              <li class="nav-item {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.chat.index') }}">
                  <i class="fas fa-comments"></i>
                  <p>Chat Client</p>
                </a>
              </li>
              @endif
              @if(in_array($authRole, ['admin', 'owner'], true))
              <li class="nav-item {{ request()->routeIs('admin.ratings.*') ? 'active' : '' }}">
                <a href="{{ backend_route('admin.ratings.index') }}">
                  <i class="fas fa-star"></i>
                  <p>Rating</p>
                </a>
              </li>
              @endif
              @if(false)
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#charts">
                  <i class="far fa-chart-bar"></i>
                  <p>Charts</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="charts">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="charts/charts.html">
                        <span class="sub-item">Chart Js</span>
                      </a>
                    </li>
                    <li>
                      <a href="charts/sparkline.html">
                        <span class="sub-item">Sparkline</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="widgets.html">
                  <i class="fas fa-desktop"></i>
                  <p>Widgets</p>
                  <span class="badge badge-success">4</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="../../documentation/index.html">
                  <i class="fas fa-file"></i>
                  <p>Documentation</p>
                  <span class="badge badge-secondary">1</span>
                </a>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#submenu">
                  <i class="fas fa-bars"></i>
                  <p>Menu Levels</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="submenu">
                  <ul class="nav nav-collapse">
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav1">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav1">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a data-bs-toggle="collapse" href="#subnav2">
                        <span class="sub-item">Level 1</span>
                        <span class="caret"></span>
                      </a>
                      <div class="collapse" id="subnav2">
                        <ul class="nav nav-collapse subnav">
                          <li>
                            <a href="#">
                              <span class="sub-item">Level 2</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </li>
                    <li>
                      <a href="#">
                        <span class="sub-item">Level 1</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              @endif
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="{{asset('be/assets/img/kaiadmin/logo_light.svg')}}"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class ="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
                <div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Cari data pada halaman ini..."
                    class="form-control backend-search-input"
                  />
                </div>
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                >
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                    <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Cari data pada halaman ini..."
                          class="form-control backend-search-input"
                        />
                      </div>
                    </form>
                  </ul>
                </li>
                @if($authRole === 'admin')
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a class="nav-link dropdown-toggle" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-bell"></i>
                    @if(($backendPendingBookingCount ?? 0) > 0)
                      <span class="notification">{{ min($backendPendingBookingCount, 9) }}</span>
                    @endif
                  </a>
                  <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    <li><div class="dropdown-title">{{ $backendPendingBookingCount ?? 0 }} booking baru menunggu ditinjau</div></li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          @forelse($backendBookingNotifications ?? [] as $bookingNotif)
                            <a href="{{ backend_route('admin.booking.show', [$bookingNotif->id]) }}">
                              <div class="notif-icon notif-primary"><i class="fa fa-car"></i></div>
                              <div class="notif-content">
                                <span class="block">{{ $bookingNotif->user->name ?? 'Customer' }} - {{ $bookingNotif->vehicle->license_plate ?? '-' }}</span>
                                <span class="time">{{ $bookingNotif->created_at?->diffForHumans() }}</span>
                              </div>
                            </a>
                          @empty
                            <div class="px-3 py-3 text-muted small">Belum ada booking baru.</div>
                          @endforelse
                        </div>
                      </div>
                    </li>
                    <li><a class="see-all" href="{{ backend_route('admin.booking.index') }}">Lihat semua booking<i class="fa fa-angle-right"></i></a></li>
                  </ul>
                </li>
                <li class="nav-item me-2 mobile-hidden-lite">
                  <a href="{{ backend_route('admin.chat.index') }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                    <i class="fa fa-comments me-1"></i> Chat Client
                  </a>
                </li>
                @endif
                <li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <i class="fas fa-layer-group"></i>
                  </a>
                  <div class="dropdown-menu quick-actions animated fadeIn">
                    <div class="quick-actions-header">
                      <span class="title mb-1">Quick Actions</span>
                      <span class="subtitle op-7">Shortcuts</span>
                    </div>
                    <div class="quick-actions-scroll scrollbar-outer">
                      <div class="quick-actions-items">
                        <div class="row m-0">
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-danger rounded-circle">
                                <i class="far fa-calendar-alt"></i>
                              </div>
                              <span class="text">Calendar</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-warning rounded-circle"
                              >
                                <i class="fas fa-map"></i>
                              </div>
                              <span class="text">Maps</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div class="avatar-item bg-info rounded-circle">
                                <i class="fas fa-file-excel"></i>
                              </div>
                              <span class="text">Reports</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-success rounded-circle"
                              >
                                <i class="fas fa-envelope"></i>
                              </div>
                              <span class="text">Emails</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-primary rounded-circle"
                              >
                                <i class="fas fa-file-invoice-dollar"></i>
                              </div>
                              <span class="text">Invoice</span>
                            </div>
                          </a>
                          <a class="col-6 col-md-4 p-0" href="#">
                            <div class="quick-actions-item">
                              <div
                                class="avatar-item bg-secondary rounded-circle"
                              >
                                <i class="fas fa-credit-card"></i>
                              </div>
                              <span class="text">Payments</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </li>

                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      @if($authPhoto)
                        <img
                          src="{{ $authPhoto }}"
                          alt="{{ $authUser->name ?? 'User' }}"
                          class="avatar-img rounded-circle"
                        />
                      @else
                        <img
                          src="{{asset('be/assets/img/profile.jpg')}}"
                          alt="..."
                          class="avatar-img rounded-circle"
                        />
                      @endif
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">{{ $authUser->name ?? 'User' }}</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            @if($authPhoto)
                              <img
                                src="{{ $authPhoto }}"
                                alt="image profile"
                                class="avatar-img rounded"
                              />
                            @else
                              <img
                                src="{{asset('be/assets/img/profile.jpg')}}"
                                alt="image profile"
                                class="avatar-img rounded"
                              />
                            @endif
                          </div>
                          <div class="u-text">
                            <h4>{{ $authUser->name ?? 'User' }}</h4>
                            <p class="text-muted">{{ $authUser->email ?? '-' }}</p>
                            <span class="btn btn-xs btn-secondary btn-sm text-uppercase">{{ $authRole }}</span>
                          </div>
                        </div>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ backend_route('admin.profile.edit') }}">My Profile</a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ backend_route('admin.logout') }}">
                          @csrf
                          <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
        </div>

        <main style="margin-top: 70px;">
        @if ($title == 'Dashboard')
            @yield('Dashboard')
        @elseif ($title == 'Booking')
            @yield('Booking')
        @elseif ($title == 'Service')
            @yield('Service')
        @elseif ($title == 'Vehicle')
            @yield('Vehicle')
        @elseif ($title == 'Sparepart')
            @yield('Sparepart')
        @elseif ($title == 'Transaction')
            @yield('Transaction')
        @elseif ($title == 'User')
            @yield('User')
        @elseif ($title == 'Ledger')
            @yield('Ledger')
        @elseif ($title == 'Attendance')
            @yield('Attendance')
        @elseif ($title == 'Chat')
            @yield('Chat')
        @elseif ($title == 'Rating')
          @yield('Rating')
        @endif
        </main>

        <footer class="footer">
          <div class="container-fluid d-flex justify-content-between">
            <nav class="pull-left">
              <ul class="nav">
                <li class="nav-item">
                  <a class="nav-link" href="http://www.themekita.com">
                    ThemeKita
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Help </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link" href="#"> Licenses </a>
                </li>
              </ul>
            </nav>
            <div class="copyright">
              2024, made with <i class="fa fa-heart heart text-danger"></i> by
              <a href="http://www.themekita.com">ThemeKita</a>
            </div>
            <div>
              Distributed by
              <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
            </div>
          </div>
        </footer>
      </div>


      <!-- Custom template | don't include it in your project! -->
      <div class="custom-template">
        <div class="title">Settings</div>
        <div class="custom-content">
          <div class="switcher">
            <div class="switch-block">
              <h4>Logo Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="selected changeLogoHeaderColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeLogoHeaderColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Navbar Header</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red"
                ></button>
                <button
                  type="button"
                  class="selected changeTopBarColor"
                  data-color="white"
                ></button>
                <br />
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="dark2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="purple2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="light-blue2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="green2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="orange2"
                ></button>
                <button
                  type="button"
                  class="changeTopBarColor"
                  data-color="red2"
                ></button>
              </div>
            </div>
            <div class="switch-block">
              <h4>Sidebar</h4>
              <div class="btnSwitch">
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="white"
                ></button>
                <button
                  type="button"
                  class="selected changeSideBarColor"
                  data-color="dark"
                ></button>
                <button
                  type="button"
                  class="changeSideBarColor"
                  data-color="dark2"
                ></button>
              </div>
            </div>
          </div>
        </div>
        <div class="custom-toggle">
          <i class="icon-settings"></i>
        </div>
      </div>
      <!-- End Custom template -->
    </div>
    <!--   Core JS Files   -->
    <script src="{{ asset('be/assets/js/core/jquery-3.7.1.min.js')}}"></script>
    <script src="{{ asset('be/assets/js/core/popper.min.js')}}"></script>
    <script src="{{ asset('be/assets/js/core/bootstrap.min.js')}}"></script>

    <!-- jQuery Scrollbar -->
    <script src="{{ asset('be/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js')}}"></script>
    <!-- Chart JS -->
    <script src="{{ asset('be/assets/js/plugin/chart.js/chart.min.js')}}"></script>

    <!-- jQuery Sparkline -->
    <script src="{{ asset('be/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js')}}"></script>

    <!-- Chart Circle -->
    <script src="{{ asset('be/assets/js/plugin/chart-circle/circles.min.js')}}"></script>

    <!-- Datatables -->
    <script src="{{ asset('be/assets/js/plugin/datatables/datatables.min.js')}}"></script>

    <!-- Bootstrap Notify -->
    <script src="{{ asset('be/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js')}}"></script>

    <!-- jQuery Vector Maps -->
    <script src="{{ asset('be/assets/js/plugin/jsvectormap/jsvectormap.min.js')}}"></script>
    <script src="{{ asset('be/assets/js/plugin/jsvectormap/world.js')}}"></script>
    <!-- Sweet Alert -->
    <script src="{{ asset('be/assets/js/plugin/sweetalert/sweetalert.min.js')}}"></script>

    <!-- Kaiadmin JS -->
    <script src="{{ asset('be/assets/js/kaiadmin.min.js')}}"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
    <script src="{{ asset('be/assets/js/setting-demo.js')}}"></script>
    <script src="{{ asset('be/assets/js/demo.js')}}"></script>
    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });
    </script>
    <!-- alert -->
     <script>
    // Alert untuk Success
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Alert untuk Error/Gagal
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
        });
    @endif

    // Alert untuk Validasi Error (Inputan Salah)
    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Ups!',
            text: "Cek kembali inputan Anda.",
            footer: '<ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>'
        });
    @endif
</script>
    @stack('page-scripts')
    <script>
      (() => {
        const searchInputs = document.querySelectorAll('.backend-search-input');
        const filterControls = document.querySelectorAll('[data-table-filter]');
        if (!searchInputs.length && !filterControls.length) return;

        const searchTargets = () => document.querySelectorAll('main [data-search-row], main [data-search-item]');

        const toDatasetKey = (filterName) => filterName.replace(/-([a-z])/g, (_, char) => char.toUpperCase());

        function runSearch() {
          const query = (document.querySelector('.backend-search-input')?.value ?? '').trim().toLowerCase();
          const activeFilters = [...filterControls].reduce((carry, control) => {
            if (control.value) {
              carry[control.dataset.tableFilter] = control.value;
            }

            return carry;
          }, {});

          searchTargets().forEach((target) => {
            const text = (target.dataset.searchText || target.innerText || '').toLowerCase();
            const matchesQuery = !query || text.includes(query);
            const matchesFilters = Object.entries(activeFilters).every(([filterName, filterValue]) => {
              const dataKey = toDatasetKey(filterName);
              const rowValue = (target.dataset[dataKey] || '').toString();

              return !filterValue || rowValue === filterValue;
            });

            target.style.display = matchesQuery && matchesFilters ? '' : 'none';
          });
        }

        searchInputs.forEach(input => {
          input.addEventListener('input', () => {
            searchInputs.forEach(other => {
              if (other !== input) other.value = input.value;
            });
            runSearch();
          });
        });

        filterControls.forEach(control => {
          control.addEventListener('change', runSearch);
        });

        runSearch();
      })();
    </script>
  </body>
</html>
