<!doctype html>
<html lang="it">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>Login - Il tuo progetto Laravel</title>
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
  </head>
  <body>
    <!-- BEGIN DEMO THEME SCRIPT -->
    <script src="{{ asset('preview/js/demo-theme.min.js') }}"></script>
    <!-- END DEMO THEME SCRIPT -->
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <!-- BEGIN NAVBAR LOGO -->
          <a href="." class="navbar-brand navbar-brand-autodark">
            <img src="{{ asset('static/logo-small.svg') }}" width="110" height="32" alt="Tabler" class="navbar-brand-image">
          </a>
          <!-- END NAVBAR LOGO -->
        </div>
        <div class="card card-md">
          <div class="card-body">
            <h2 class="h2 text-center mb-4">Accedi al tuo account</h2>
            <form action="{{ route('login.post') }}" method="POST" autocomplete="off" novalidate>
              @csrf
              @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
              @endif
              <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" placeholder="Il tuo username" value="{{ old('username') }}" autocomplete="off" />
              </div>
              <div class="mb-2">
                <label class="form-label">
                  Password
                  <span class="form-label-description">
                    <a href="#">Password dimenticata?</a>
                  </span>
                </label>
                <div class="input-group input-group-flat">
                  <input type="password" name="password" id="password-input" class="form-control @error('password') is-invalid @enderror" placeholder="La tua password" autocomplete="off" />
                  <span class="input-group-text">
                    <a href="#" class="link-secondary" id="toggle-password" title="Mostra password">
                      <!-- Download SVG icon from http://tabler.io/icons/icon/eye -->
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-eye"
                      >
                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                      </svg>
                      <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        class="icon icon-eye-off"
                        style="display: none;"
                      >
                        <path d="M10.585 10.587a2 2 0 0 0 2.829 2.828" />
                        <path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87" />
                        <path d="M3 3l18 18" />
                      </svg>
                    </a>
                  </span>
                </div>
              </div>
              <div class="mb-2">
                <label class="form-check">
                  <input type="checkbox" name="remember" class="form-check-input" />
                  <span class="form-check-label">Ricordami su questo dispositivo</span>
                </label>
              </div>
              <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">Accedi</button>
              </div>
            </form>
          </div>
        </div>
        <div class="text-center text-secondary mt-3">Non hai ancora un account? <a href="#" tabindex="-1">Registrati</a></div>
      </div>
    </div>
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    <!-- BEGIN DEMO SCRIPTS -->
    <script src="{{ asset('preview/js/demo.min.js') }}" defer></script>
    <!-- END DEMO SCRIPTS -->
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password-input');
        const eyeIcon = togglePassword.querySelector('.icon-eye');
        const eyeOffIcon = togglePassword.querySelector('.icon-eye-off');
        
        // Inizializza il tooltip con configurazione dinamica
        const tooltip = new bootstrap.Tooltip(togglePassword, {
          title: function() {
            return passwordInput.type === 'password' ? 'Mostra password' : 'Nascondi password';
          }
        });
        
        togglePassword.addEventListener('click', function(e) {
          e.preventDefault();
          
          if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.style.display = 'none';
            eyeOffIcon.style.display = 'block';
          } else {
            passwordInput.type = 'password';
            eyeIcon.style.display = 'block';
            eyeOffIcon.style.display = 'none';
          }
          
          // Forza l'aggiornamento del tooltip
          tooltip.hide();
        });
      });
    </script>
  </body>
</html>