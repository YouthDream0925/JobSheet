<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

  @include('layouts.head')

  <body class="qboat admin" data-qboat="theme-DodgerBlue">
    <!--[ Start:: main sidebar menu link ]-->
    @include('layouts.menu')

    <div class="page order-2 flex-grow-1">
      <!--[ Start:: page header link ]-->
      <header class="page-header sticky-top">
        <div class="container-fluid">
          <div class="d-flex justify-content-between align-items-center">
            <a class="me-4 d-lg-inline-flex d-none menu-toggle" href="#" title="Sidebar Toggle">
              <svg width="24" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path fill="var(--accent-color)" d="M14.7071 7.29289C15.0976 7.68342 15.0976 8.31658 14.7071 8.70711L12.4142 11H20C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13H12.4142L14.7071 15.2929C15.0976 15.6834 15.0976 16.3166 14.7071 16.7071C14.3166 17.0976 13.6834 17.0976 13.2929 16.7071L9.29289 12.7071C8.90237 12.3166 8.90237 11.6834 9.29289 11.2929L13.2929 7.29289C13.6834 6.90237 14.3166 6.90237 14.7071 7.29289Z" />
                <path fill="var(--accent-color)" fill-opacity="0.3" d="M4 3C4.55228 3 5 3.44772 5 4V20C5 20.5523 4.55228 21 4 21C3.44772 21 3 20.5523 3 20V4C3 3.44772 3.44772 3 4 3Z" />
              </svg>
            </a>
            <a class="me-4 d-lg-none d-inline-flex text-decoration-none text-accent align-items-center" href="{{ route('home') }}" style="width: 160px; display: flex;">
                @svg('logo.svg', 'logo')
                <span class="fs-5 ms-2">{{ __('global.appTitle') }}</span>
            </a>
            <!-- Example single danger button -->
            <ul class="header-menu flex-grow-1">
              <li class="w-100 d-none d-md-inline-flex">
                <!-- [ Start:: main search ] -->
                <div class="search" style="height: 44px !important;">
                  <!-- <svg width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path class="fill-muted" d="M10.2742 9.051C11.1215 7.89485 11.501 6.46142 11.3368 5.0375C11.1725 3.61358 10.4767 2.30417 9.38854 1.37124C8.30034 0.438302 6.90002 -0.0493505 5.46772 0.0058385C4.03543 0.0610275 2.67678 0.654988 1.6636 1.66889C0.650425 2.68279 0.0574364 4.04186 0.00327226 5.4742C-0.0508918 6.90654 0.437763 8.30651 1.37147 9.39404C2.30519 10.4816 3.61509 11.1764 5.03913 11.3396C6.46317 11.5028 7.89633 11.1223 9.05187 10.2742H9.05099C9.07724 10.3092 9.10524 10.3425 9.13674 10.3749L12.5055 13.7436C12.6696 13.9078 12.8921 14.0001 13.1242 14.0002C13.3564 14.0003 13.579 13.9081 13.7432 13.7441C13.9074 13.58 13.9997 13.3574 13.9997 13.1253C13.9998 12.8932 13.9077 12.6706 13.7436 12.5064L10.3749 9.13762C10.3436 9.10596 10.3099 9.0767 10.2742 9.05012V9.051ZM10.5 5.6875C10.5 6.31948 10.3755 6.94528 10.1337 7.52916C9.89181 8.11304 9.53733 8.64357 9.09044 9.09045C8.64356 9.53733 8.11304 9.89182 7.52916 10.1337C6.94528 10.3755 6.31948 10.5 5.68749 10.5C5.05551 10.5 4.42971 10.3755 3.84583 10.1337C3.26195 9.89182 2.73142 9.53733 2.28454 9.09045C1.83766 8.64357 1.48317 8.11304 1.24132 7.52916C0.999471 6.94528 0.874992 6.31948 0.874992 5.6875C0.874992 4.41114 1.38202 3.18706 2.28454 2.28455C3.18706 1.38203 4.41114 0.874997 5.68749 0.874997C6.96385 0.874997 8.18793 1.38203 9.09044 2.28455C9.99296 3.18706 10.5 4.41114 10.5 5.6875Z" />
                  </svg>
                  <input class="form-control rounded-pill" type="search" placeholder="Search" aria-label="Search"> -->
                </div>
              </li>
              <!--[ Start:: localization ]-->
              <li class="dropdown">
                <a class="dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Locale">
                  @if(Session::get('locale') == 'en')
                  <i class="fs-5 me-1 flag-icon flag-icon-us"></i>
                  @elseif(Session::get('locale') == 'it')
                  <i class="fs-5 me-1 flag-icon flag-icon-it"></i>
                  @else
                  <i class="fs-5 me-1 flag-icon flag-icon-us"></i>
                  @endif
                  <span class="ps-1 fs-6 text-white d-none d-lg-inline-block">
                    @if(Session::get('locale') == 'en')
                      {{ __('global.english') }}
                    @elseif(Session::get('locale') == 'it')
                      {{ __('global.italian') }}
                    @else
                      {{ __('global.english') }}
                    @endif
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2 rounded-4">
                  <li><a class="dropdown-item py-2 rounded" href="{{ url('/greeting', 'en') }}"><i class="fs-5 me-2 flag-icon flag-icon-us"></i>{{ __('global.english') }}</a></li>
                  <li><a class="dropdown-item py-2 rounded" href="{{ url('/greeting', 'it') }}"><i class="fs-5 me-2 flag-icon flag-icon-it"></i>{{ __('global.italian') }}</a></li>
                </ul>
              </li>
              @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin'))
              <li class="dropdown">
                <div class="form-check form-switch">
                  @if($notification_allow == 1)
                  <input id="notificaion" name="special" class="form-check-input" type="checkbox" role="switch" checked>
                  @else
                  <input id="notificaion" name="special" class="form-check-input" type="checkbox" role="switch">
                  @endif
                </div>
              </li>
              @endif
              <!--[ Start:: notification ]-->
              <li class="dropdown">
                <a class="dropdown-toggle text-white" href="#NotificationsDiv" role="button" data-bs-toggle="offcanvas" aria-expanded="false">
                  @foreach(Auth::user()->notifications as $notification)
                    @if($notification->unread())
                    <span class="bullet-dot bg-accent animation-blink"></span>
                    @endif
                  @endforeach
                  <svg width="20" viewBox="0 0 18 18" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <circle opacity="0.3" cx="11" cy="11" r="4" />
                    <path d="M9 18C9.59674 18 10.169 17.7629 10.591 17.341C11.0129 16.919 11.25 16.3467 11.25 15.75H6.75C6.75 16.3467 6.98705 16.919 7.40901 17.341C7.83097 17.7629 8.40326 18 9 18ZM9 2.15775L8.10337 2.33888C7.08633 2.5461 6.17212 3.09837 5.51548 3.9022C4.85884 4.70603 4.50011 5.71206 4.5 6.75C4.5 7.4565 4.34925 9.22162 3.98362 10.9597C3.80362 11.8226 3.56063 12.7215 3.23775 13.5H14.7622C14.4394 12.7215 14.1975 11.8237 14.0164 10.9597C13.6507 9.22162 13.5 7.4565 13.5 6.75C13.4996 5.71225 13.1408 4.70649 12.4842 3.90289C11.8275 3.09929 10.9135 2.54719 9.89662 2.34L9 2.15663V2.15775ZM15.9975 13.5C16.2484 14.0029 16.5386 14.4011 16.875 14.625H1.125C1.46137 14.4011 1.75162 14.0029 2.0025 13.5C3.015 11.475 3.375 7.74 3.375 6.75C3.375 4.0275 5.31 1.755 7.88063 1.23637C7.86492 1.07995 7.88218 0.921967 7.93129 0.77262C7.98039 0.623273 8.06026 0.485876 8.16573 0.36929C8.27119 0.252705 8.39993 0.159519 8.54362 0.0957427C8.68732 0.0319665 8.84279 -0.000984192 9 -0.000984192C9.15721 -0.000984192 9.31268 0.0319665 9.45638 0.0957427C9.60007 0.159519 9.72881 0.252705 9.83428 0.36929C9.93974 0.485876 10.0196 0.623273 10.0687 0.77262C10.1178 0.921967 10.1351 1.07995 10.1194 1.23637C11.3909 1.49501 12.534 2.18516 13.3551 3.18994C14.1762 4.19472 14.6248 5.4524 14.625 6.75C14.625 7.74 14.985 11.475 15.9975 13.5Z" />
                  </svg>
                </a>
              </li>
              <!--[ Start:: user detail ]-->
              <li class="dropdown user">
                <a class="dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="User">
                  @if(Auth::user()->hasMedia('avatar'))
                  <img class="avatar sm rounded-circle shadow border border-2" src="{{ Auth()->user()->getMedia('avatar')[0]->getUrl() }}" alt="avatar">
                  @else
                  <img class="avatar sm rounded-circle shadow border border-2" src="{{ url('storage/sample') }}" alt="avatar">
                  @endif
                  <span class="ps-1 fs-6 text-white d-none d-lg-inline-block">{{ Auth()->user()->firstname }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-4 rounded-4">
                  <li class="mb-3">
                    <a class="h5" href="./crafted-profile.html" title="">{{ Auth()->user()->firstname }} {{ Auth()->user()->lastname }}</a>
                    <p>{{ Auth()->user()->email }}</p>
                    <!-- <a class="btn bg-dark text-white w-100" href="./auth-signin.html" role="button">Logout</a> -->
                    <a class="btn bg-dark text-white w-100" href="{{ route('logout') }}" role="button"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        {{ __('global.logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                  </li>
                  <li class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="{{ route('profile.index', 'id='.Auth()->user()->id) }}">{{ __('global.profile') }}</a></li>
                  <li><a class="dropdown-item" href="{{ route('jobcards.index') }}">{{ __('global.myWP') }}</a></li>
                </ul>
              </li>
              <li class="dropdown d-block d-lg-none">
                <button class="btn btn-sm btn-white sidebar-toggle ms-3" type="button"><i class="fa fa-bars"></i></button>
              </li>
            </ul>
          </div>
        </div>
      </header>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="NotificationsDiv">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title">{{ __('global.message') }}</h5>
          <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body notification custom_scroll">
          <div class="tab-content mt-4">
            <div class="tab-pane fade show active" id="noti_tab_all" role="tabpanel">
              <ul class="list-group list-group-flush list-group-custom mb-0">
                @foreach(Auth::user()->notifications as $key => $notification)
                  <li class="list-group-item">
                    <a href="{{ route('messages.index', $notification->id) }}" class="d-flex">
                      @if(Auth::user()->sender($notification->data['sender'])->hasMedia('avatar'))
                      <img class="avatar sm rounded" src="{{ Auth::user()->sender($notification->data['sender'])->getMedia('avatar')[0]->getUrl() }}" alt="">
                      @else
                      <img class="avatar sm rounded" src="{{ url('storage/sample') }}" alt="">
                      @endif                      
                      <div class="flex-fill ms-3">
                        <p class="d-flex justify-content-between mb-0">
                          <span>
                            {{ $notification->data['title'] }}
                            @if($notification->unread())
                              <span class="bullet-dot bg-accent animation-blink span-alarm-pos"></span>
                            @endif
                          </span>
                        </p>
                        <span>
                          {{ $notification->data['message'] }}
                        </span>
                      </div>
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--[ Start:: page title and breadcrumb ]-->
      @yield('breadcrumb')
      <!--[ Start:: page body area ]-->
      <main class="page-body">
        @yield('content')
      </main>
      <!--[ Start:: page footer link copywrite ]-->
      <footer class="page-footer py-4 mt-4 ">
        <div class="container-fluid txt-center">
          <p class="mb-0 text-muted">© 2022 {!! __('global.footerDescription') !!}</p>
        </div>
      </footer>
    </div>

    <script src="{{asset('dist/vendor/peity/jquery.peity.min.js')}}"></script>
    <script>
      $("span.pie").peity("pie", {
        fill: ["var(--primary-color)", "var(--border-color)"]
      })
    </script>

    <!--[ Start:: main body background and img ]-->
    <div class="body-bg">
      <svg class="img-fluid top-0" viewBox="0 0 1920 1080" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0_121_2)">
          <g opacity="0.14" filter="url(#filter0_f_121_2)">
            <circle cx="1840.5" cy="600.5" r="225.5" fill="var(--accent-color)" />
          </g>
          <g opacity="0.1" filter="url(#filter1_f_121_2)">
            <circle cx="222.5" cy="118.5" r="327.5" fill="var(--primary-color)" />
          </g>
        </g>
        <defs>
          <filter id="filter0_f_121_2" x="1265" y="25" width="1151" height="1151" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix" />
            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
            <feGaussianBlur stdDeviation="175" result="effect1_foregroundBlur_121_2" />
          </filter>
          <filter id="filter1_f_121_2" x="-455" y="-559" width="1355" height="1355" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
            <feFlood flood-opacity="0" result="BackgroundImageFix" />
            <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
            <feGaussianBlur stdDeviation="175" result="effect1_foregroundBlur_121_2" />
          </filter>
          <clipPath id="clip0_121_2">
            <rect width="1920" height="1080" fill="white" />
          </clipPath>
        </defs>
      </svg>
    </div>

    <!--[ Jquery Page Js ]-->
    <script src="{{ asset('dist/js/theme.js') }}"></script>

    <!--[ Chart plugin url ]-->
    <script src="{{asset('dist/bundles/apexcharts.bundle.js')}}"></script>

    <!--[ Forms url ]-->
    <script src="{{asset('dist/bundles/bootstrapdatepicker.bundle.js')}}"></script>

    <!--[ plugin url ]-->
    <script src="{{asset('dist/js/lang.js')}}"></script>
    <script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
    @include('layouts.flash')

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-VCPFN5ZM25"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-VCPFN5ZM25');
    </script>

    <!--[ Jquery Page Js ]-->
    @stack('script')

    <!-- The core Firebase JS SDK is always required and must be listed first -->
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js"></script>

    <script>
        var firebaseConfig = {
            apiKey: "AIzaSyBOBWnDE5CeKh9dpLa0OTVSPqjqGI79Pac",
            authDomain: "reluis.firebaseapp.com",
            projectId: "reluis",
            storageBucket: "reluis.appspot.com",
            messagingSenderId: "216062052432",
            appId: "1:216062052432:web:70736aee4af44150b62955"
        };
        // Initialize Firebase
        firebase.initializeApp(firebaseConfig);

        const messaging = firebase.messaging();

        function initFirebaseMessagingRegistration() {
            messaging.requestPermission().then(function () {
                return messaging.getToken()
            }).then(function(token) {
                
                axios.post("{{ route('fcmToken') }}",{
                    _method:"PATCH",
                    token
                }).then(({data})=>{
                    console.log(data)
                }).catch(({response:{data}})=>{
                    console.error(data)
                })

            }).catch(function (err) {
                console.log(`Token Error :: ${err}`);
            });
        }

        initFirebaseMessagingRegistration();
      
        messaging.onMessage(function({data:{body,title}}){
            new Notification(title, {body});
        });
    </script>

    <script>
      $('#notificaion').on('click', function() {
        var setting = $(this).prop('checked');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
          url: '/notification',
          type: 'post',
          data: {
            'setting': setting,
          },
          success: function(result) {
            if(result['success'] == true && result['config'] == 1) {
              new bs5.Toast({
                body: lang.notificationAllowed,
                className: 'border-0 bg-success text-white',
                btnCloseWhite: true,
              }).show();
            } else if(result['success'] == true && result['config'] == 0) {
              new bs5.Toast({
                body: lang.notificationNotAllowed,
                className: 'border-0 bg-success text-white',
                btnCloseWhite: true,
              }).show();
            } else {
              new bs5.Toast({
                body: lang.unexpectedError,
                className: 'border-0 bg-success text-white',
                btnCloseWhite: true,
              }).show();
            }
          },
          error: function(res) {
            new bs5.Toast({
              body: lang.unexpectedError,
              className: 'border-0 bg-success text-white',
              btnCloseWhite: true,
            }).show();
          }
        });
      });
    </script>
  </body>

</html>