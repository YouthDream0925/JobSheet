@extends('layouts.auth.index')

@push('css')
    
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row g-xl-4 g-lg-3 g-2">
            <div class="col-xxl-3 col-xl-2 col-lg-12"></div>
            <div class="col-xxl-9 col-xl-10 col-lg-12">
                <form class="pt-3" method="POST" action="{{ route('login') }}">
                    @csrf
                    <ul class="row g-3 list-unstyled li_animate">
                    <li class="col-12 mb-5">
                        <h2 class="text-gradient font-heading">{{ __('global.signTitle') }}</h2>
                        <span class="text-muted">{{ __('global.signDes') }}</span>
                    </li>
                    <li class="col-12">
                        <div class="mb-2">
                            <label class="form-label">{{ __('global.emailAddress') }}</label>
                            <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="name@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </li>
                    <li class="col-12">
                        <div class="mb-2">
                            <div class="form-label">
                                <span class="d-flex justify-content-between align-items-center">
                                    {{ __('global.password') }}
                                    @if (Route::has('password.request'))
                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                            {{ __('global.forgetPassword') }}
                                        </a>
                                    @endif
                                </span>
                            </div>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="***************">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </li>
                    <li class="col-12">
                        <div class="row">
                            <div class="col-12 div-container">
                                <div class="form-check div-center">
                                    <input class="form-check-input checkbox-color" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        {{ __('global.rememberMe') }}
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                    </li>
                    <li class="col-12">
                        <div class="row">
                            <div class="col-12 mt-4">
                                <div class="h-captcha f-right @error('h-captcha-response') is-invalid @enderror" data-sitekey="c7b2565c-e6ba-4dda-a50d-68fb1546dfbe"></div>
                                @error('h-captcha-response')
                                    <span class="invalid-span-feedback span-pos" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>    
                    </li>
                    <li class="col-12 mt-4">
                        <div class="row" style="align-items: center;">
                            <div class="col-12 d-flex-center">
                                <button type="submit" class="btn btn-lg btn-block btn-dark lift text-uppercase px-5">
                                    {{ __('global.signIn') }}
                                </button>
                                <a class="dropdown-toggle text-decoration-none ml-auto" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" title="Locale">
                                    @if(Session::get('locale') == 'en')
                                    <i class="fs-5 me-1 flag-icon flag-icon-us"></i>
                                    @elseif(Session::get('locale') == 'it')
                                    <i class="fs-5 me-1 flag-icon flag-icon-it"></i>
                                    @else
                                    <i class="fs-5 me-1 flag-icon flag-icon-us"></i>
                                    @endif
                                    <span class="ps-1 fs-6 text-white d-none d-lg-inline-block" style="color: black !important;">
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
                            </div>
                            <div class="col-12 mt-4">
                                <span class="text-muted" style="float: right;">{{ __('global.notAccountYet') }} <a href="register">{{ __('global.signUpHere') }}</a></span>
                            </div>
                        </div>
                    </li>
                    </ul><!--[ ul.row end ]-->
                </form>
            </div>
        </div> <!--[ .row end ]-->
    </div>
@endsection

@push('script')
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
@include('layouts.flash')
@endpush