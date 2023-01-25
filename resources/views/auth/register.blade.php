@extends('layouts.auth.index')

@section('content')
<div class="container-fluid">
    <div class="row g-xl-4 g-lg-3 g-2">
        <div class="col-xxl-3 col-xl-2 col-lg-12"></div>
        <div class="col-xxl-9 col-xl-10 col-lg-12">
            <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf
                <ul class="row g-3 list-unstyled li_animate">
                <li class="col-12 mb-5">
                    <h2 class="text-gradient font-heading">{{ __('global.createAccount') }}</h2>
                    <span class="text-muted">{{ __('global.registerDes') }}</span>
                </li>
                <!-- <li class="col-12 mb-4">
                    <a class="btn btn-outline-secondary btn-block" href="#">
                    <i class="fa fa-google me-2"></i>
                    <span>Sign up with Google</span>
                    </a>
                    <a class="btn btn-outline-secondary btn-block" href="#">
                    <i class="fa fa-apple me-2"></i>
                    <span>Sign up with Apple</span>
                    </a>
                </li> -->
                <li class="col-6">
                    <label class="form-label">{{ __('global.fullName') }}</label>
                    <input id="firstname" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="firstname" value="{{ old('firstname') }}" required autocomplete="firstname" autofocus placeholder="John">
                    @error('firstname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-6">
                    <label class="form-label">&nbsp;</label>
                    <input id="lastname" type="text" class="form-control form-control-lg @error('lastname') is-invalid @enderror" name="lastname" value="{{ old('lastname') }}" required autocomplete="lastname" autofocus placeholder="Parker">
                    @error('lastname')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-12">
                    <label class="form-label">{{ __('global.emailAddress') }}</label>
                    <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-12">
                    <label class="form-label">{{ __('global.password') }}</label>
                    <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="8+ characters required">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </li>
                <li class="col-12">
                    <label class="form-label">{{ __('global.confirmPassword') }}</label>
                    <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password" placeholder="8+ characters required">
                </li>
                <li class="col-12">
                    <div class="row">
                        <div class="col-12 div-container">
                            <div class="form-check div-center">
                                <input class="form-check-input checkbox-color" type="checkbox" value="" id="TermsConditions">
                                <label class="form-check-label" for="TermsConditions"> {!! __('global.acceptContent') !!}
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
                                {{ __('global.signUp') }}
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
                        <div class="col-12 mt-4 txt-right">
                            <span class="text-muted">{{ __('global.accountExist') }} <a href="{{ route('login') }}">{{ __('global.signInHere') }}</a></span>
                        </div>
                    </div>
                </li>
                </ul><!--[ ul.row end ]-->
            </form>
        </div>
    </div> <!--[ .row end ]-->
</div>
<!--
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
-->
@endsection

@push('script')
<script src="https://js.hcaptcha.com/1/api.js" async defer></script>
@endpush