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
    
    @stack('styles')
  </head>
  <body>
    <!-- BEGIN DEMO THEME SCRIPT -->
    <script src="{{ asset('preview/js/demo-theme.min.js') }}"></script>
    <!-- END DEMO THEME SCRIPT -->
    
    <div class="page">
      <!-- BEGIN SIDEBAR -->
      @include('components.sidebar.main')
      <!-- END SIDEBAR -->
      
      <div class="page-wrapper">
        <!-- BEGIN HEADER -->
        @include('components.header.main')
        <!-- END HEADER -->
        
        <div class="page-body">
          <div class="container-xl">
            @yield('content')
          </div>
        </div>
        
        <!-- BEGIN FOOTER -->
        @include('components.footer.main')
        <!-- END FOOTER -->
      </div>
    </div>
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="{{ asset('dist/js/tabler.min.js') }}" defer></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->
    
    <!-- BEGIN DEMO SCRIPTS -->
    <script src="{{ asset('preview/js/demo.min.js') }}" defer></script>
    <!-- END DEMO SCRIPTS -->
    
    @stack('scripts')
  </body>
</html>