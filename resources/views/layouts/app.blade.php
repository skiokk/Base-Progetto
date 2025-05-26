<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <!-- END GLOBAL MANDATORY STYLES -->
    
    <!-- BEGIN PLUGINS STYLES -->
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-socials.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <!-- END PLUGINS STYLES -->
    
    <!-- BEGIN DEMO STYLES -->
    <link href="{{ asset('preview/css/demo.min.css') }}" rel="stylesheet" />
    <!-- END DEMO STYLES -->
    
    <!-- BEGIN CUSTOM FONT -->
    <style>
      @import url("https://rsms.me/inter/inter.css");
    </style>
    <!-- END CUSTOM FONT -->
    
    <!-- BEGIN VITE ASSETS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- END VITE ASSETS -->
    
    @stack('styles')
    
    <!-- FIX SIDEBAR VISIBILITY -->
    <style>
      /* Fix navbar vertical structure */
      .navbar-vertical {
        z-index: 1030 !important;
      }
      
      .navbar-vertical .container-fluid {
        padding: 0 !important;
        display: flex;
        flex-direction: column;
        height: 100%;
      }
      
      /* Ensure sidebar menu is always visible on desktop */
      @media (min-width: 992px) {
        .navbar-vertical .navbar-collapse {
          display: block !important;
          flex: 1 1 auto;
          overflow-y: auto;
        }
        
        .navbar-vertical .navbar-brand {
          padding: 1.5rem 1rem;
        }
      }
      
      /* Fix navbar nav visibility */
      .navbar-vertical .navbar-nav {
        display: flex !important;
        flex-direction: column;
        padding: 0 !important;
        margin: 0 !important;
        list-style: none !important;
      }
      
      /* Ensure nav items are visible */
      .navbar-vertical .nav-item {
        display: block !important;
        width: 100%;
        margin: 0 !important;
      }
      
      .navbar-vertical .nav-link {
        display: flex !important;
        align-items: center;
        padding: 0.75rem 1rem !important;
        color: rgba(255, 255, 255, 0.8) !important;
        text-decoration: none !important;
        width: 100%;
      }
      
      .navbar-vertical .nav-link:hover,
      .navbar-vertical .nav-link.active {
        color: #fff !important;
        background-color: rgba(255, 255, 255, 0.1) !important;
      }
      
      .navbar-vertical .nav-link-icon {
        width: 1.25rem;
        height: 1.25rem;
        margin-right: 0.5rem;
      }
      
      .navbar-vertical .nav-link-title {
        font-size: 0.875rem;
        font-weight: 500;
      }
      
      /* Fix mobile navbar */
      @media (max-width: 991.98px) {
        .navbar-vertical {
          position: relative;
          height: auto;
          width: 100%;
        }
        
        .navbar-vertical .container-fluid {
          padding: 0.5rem 1rem !important;
        }
        
        .navbar-vertical .d-flex {
          width: 100%;
          position: relative;
        }
        
        .navbar-vertical .navbar-toggler {
          border: 1px solid rgba(255, 255, 255, 0.1);
          padding: 0.25rem 0.5rem;
          position: relative;
          z-index: 1;
        }
        
        .navbar-vertical .navbar-brand {
          position: absolute;
          left: 50%;
          transform: translateX(-50%);
          margin: 0 !important;
        }
        
        .navbar-vertical .navbar-collapse {
          margin-top: 1rem;
          background-color: rgba(0, 0, 0, 0.1);
          padding: 1rem 0;
        }
      }
      
      /* Ensure visibility */
      #sidebar-menu {
        width: 100% !important;
      }
      
      /* Debug: make sure menu items are visible */
      .navbar-vertical ul {
        background-color: transparent !important;
      }
      
      .navbar-vertical li {
        background-color: transparent !important;
      }
      
      /* Force text color */
      .navbar-vertical .nav-link span {
        color: white !important;
      }
      
      /* Force all text to be white */
      .navbar-vertical .nav-link,
      .navbar-vertical .nav-link * {
        color: white !important;
      }
      
      .navbar-vertical .navbar-brand,
      .navbar-vertical .navbar-brand * {
        color: white !important;
      }
      
      /* Force navbar-collapse to be visible */
      .navbar-vertical .navbar-collapse {
        opacity: 1 !important;
        visibility: visible !important;
        height: auto !important;
      }
      
      /* Remove collapse animation on desktop */
      @media (min-width: 992px) {
        .navbar-vertical .navbar-collapse {
          transition: none !important;
        }
      }
      
      /* Notification dropdown styles */
      .dropdown-menu-card {
        padding: 0;
        margin-top: 0.5rem;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
      }
      
      .dropdown-menu-card .card {
        border: 0;
        margin: 0;
        box-shadow: none;
      }
      
      .dropdown-menu-card .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem;
      }
      
      .icon-lg {
        width: 48px !important;
        height: 48px !important;
      }
    </style>
    
    @stack('styles')
  </head>
  <body>
    <!-- BEGIN DEMO THEME SCRIPT -->
    <script src="{{ asset('preview/js/demo-theme.min.js') }}"></script>
    <!-- END DEMO THEME SCRIPT -->
    
    <!-- BEGIN AUTH USER ID FOR JS -->
    @auth
    <script>
      window.AUTH_USER_ID = {{ auth()->id() }};
    </script>
    @endauth
    <!-- END AUTH USER ID FOR JS -->
    
    <!-- BEGIN TOAST CONTAINER -->
    <div id="toast-container"></div>
    <!-- END TOAST CONTAINER -->
    
    <div class="page">
      <!-- BEGIN SIDEBAR -->
      <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
          <div class="d-flex align-items-center">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon"></span>
            </button>
            <h1 class="navbar-brand navbar-brand-autodark">
              <a href="/dashboard">
                <img src="{{ asset('static/logo-small.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
              </a>
            </h1>
          </div>
          <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3">
              <li class="nav-item">
                <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                  </span>
                  <span class="nav-link-title">Dashboard</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                  </span>
                  <span class="nav-link-title">Utenti</span>
                </a>
              </li>
              <li class="nav-item">
                <a class="nav-link {{ request()->is('notifications/test') ? 'active' : '' }}" href="{{ route('notifications.test') }}">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
                  </span>
                  <span class="nav-link-title">Test Notifiche</span>
                </a>
              </li>
              <li class="nav-item mt-auto">
                <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" /><path d="M7 12h14l-3 -3m0 6l3 -3" /></svg>
                  </span>
                  <span class="nav-link-title">Logout</span>
                </a>
                <form id="logout-form" action="/logout" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        </div>
      </aside>
      <!-- END SIDEBAR -->
      
      <div class="page-wrapper">
        <!-- BEGIN PAGE HEADER -->
        <div class="page-header d-print-none">
          <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h2 class="page-title">
                  @yield('page-title', 'Dashboard')
                </h2>
              </div>
              <!-- Page title actions -->
              <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                  @yield('page-actions')
                  <!-- Dark Mode Toggle -->
                  <div class="nav-item d-none d-md-flex me-2">
                    <a href="#" class="nav-link px-0" id="theme-toggle" tabindex="-1" aria-label="Toggle theme">
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sun" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="12" cy="12" r="4" />
                        <path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" />
                      </svg>
                      <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-moon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round" style="display: none;">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" />
                      </svg>
                    </a>
                  </div>
                  <!-- Notification Bell -->
                  <div class="nav-item dropdown d-none d-md-flex me-3">
                    <button type="button" class="btn btn-icon btn-action" data-bs-toggle="dropdown" aria-label="Show notifications">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/bell -->
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-1">
                        <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
                        <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
                      </svg>
                      <span class="badge bg-red badge-notification" id="notification-badge" style="display: none; position: absolute; top: -4px; right: -4px; padding: 2px 6px; font-size: 11px; line-height: 1;">
                        <span id="notification-count">0</span>
                      </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card" style="width: 380px; max-width: 90vw;">
                      <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                          <h3 class="card-title mb-0">Notifiche</h3>
                          <a href="#" class="link-secondary small" onclick="window.notificationManager.markAllAsRead(); return false;">Segna tutte come lette</a>
                        </div>
                        <div class="list-group list-group-flush list-group-hoverable" id="notification-list" style="max-height: 400px; overflow-y: auto;">
                          <div class="text-center p-4 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-lg mb-2 text-muted" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                              <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                              <path d="M10 5a2 2 0 0 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" />
                              <path d="M9 17v1a3 3 0 0 0 6 0v-1" />
                              <line x1="12" y1="3" x2="12" y2="3.01" />
                            </svg>
                            <p class="mb-0">Nessuna notifica</p>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <span class="d-none d-sm-inline">
                    <a href="#" class="btn">
                      Benvenuto, {{ auth()->user()->name }}
                    </a>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- END PAGE HEADER -->
        
        <!-- BEGIN PAGE BODY -->
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
        <!-- END PAGE BODY -->
        
        <!-- BEGIN FOOTER -->
        <footer class="footer footer-transparent d-print-none">
          <div class="container-xl">
            <div class="row align-items-center">
              <div class="col-12">
                <ul class="list-inline list-inline-dots mb-0">
                  <li class="list-inline-item">
                    Copyright &copy; {{ date('Y') }}
                    <a href="." class="link-secondary">{{ config('app.name', 'Laravel') }}</a>.
                    All rights reserved.
                  </li>
                </ul>
              </div>
            </div>
          </div>
        </footer>
        <!-- END FOOTER -->
      </div>
    </div>
    
    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('dist/js/tabler.min.js') }}"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    
    <!-- BEGIN DEMO SCRIPTS -->
    <script src="{{ asset('preview/js/demo.min.js') }}"></script>
    <!-- END DEMO SCRIPTS -->
    
    @stack('scripts')
    
    <!-- Dark Mode Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const iconSun = themeToggle.querySelector('.icon-sun');
        const iconMoon = themeToggle.querySelector('.icon-moon');
        const themeStorageKey = 'tablerTheme';
        
        // Get current theme from localStorage or default to light
        let currentTheme = localStorage.getItem(themeStorageKey) || 'light';
        
        // Apply theme on load
        applyTheme(currentTheme);
        
        // Toggle theme on click
        themeToggle.addEventListener('click', function(e) {
            e.preventDefault();
            currentTheme = currentTheme === 'light' ? 'dark' : 'light';
            applyTheme(currentTheme);
            localStorage.setItem(themeStorageKey, currentTheme);
        });
        
        function applyTheme(theme) {
            if (theme === 'dark') {
                document.body.setAttribute('data-bs-theme', 'dark');
                iconSun.style.display = 'none';
                iconMoon.style.display = 'block';
            } else {
                document.body.removeAttribute('data-bs-theme');
                iconSun.style.display = 'block';
                iconMoon.style.display = 'none';
            }
        }
    });
    </script>
  </body>
</html>