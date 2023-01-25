<!DOCTYPE html>
<html lang="en">

@include('layouts.auth.head')

<body class="qboat admin auth" data-qboat="theme-DodgerBlue">  
  <div class="main-width order-1">
    <!--[ Start:: page body area ]-->
    <main class="page-body flex-grow-1">
        @yield('content')
    </main>
    <!--[ Start:: page footer link copywrite ]-->
    <footer class="page-footer py-4 mt-4 ">
      <div class="container-fluid">
        <div class="row g-xl-4 g-lg-3 g-2">
          <div class="col-xxl-3 col-xl-2 col-lg-12"></div>
          <div class="col-xxl-9 col-xl-10 col-lg-12">
            <p class="mb-0 text-muted txt-center">© 2022 {!! __('global.footerDescription') !!}</p>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <aside class="sidebar shadow auth2 sidebar-width">
    <div class="container-fluid">
      <!--[ sidebar:: menu list ]-->
      <div class="flex-grow-1">
        <ul class="menu-list">
          <!--[ Start:: brand logo and name ]-->
          <li class="brand-icon border-0">
            <a href="{{ url('/') }}" style="display: contents !important;">
              @svg('logo.svg', 'logo')
            </a>
          </li>
          <li>
            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <h2 class="text-accent fw-bold display-7 mb-3">{!! __('global.welcome') !!}</h2>
                  <p class="lead text-dark">{!! __('global.appDescription') !!}</p>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </aside>

  @include('layouts.auth.background')

  <!--[ Jquery Page Js ]-->
  <script src="{{asset('dist/js/theme.js')}}"></script>
  <!--[ Chart plugin url ]-->
  <!--[ Forms url ]-->
  <!--[ plugin url ]-->
  @stack('script')
</body>

</html>