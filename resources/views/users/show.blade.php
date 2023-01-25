@extends('layouts.index')

@section('breadcrumb')
<div class="page-title mb-lg-4 pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a href="{{ route('users.index') }}">{{ __('global.users') }}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page">
                {{ __('global.info') }}
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
  <div class="card">
    <div class="card-header">
      <h5 class="card-title fw-normal mb-0">{{ __('global.userInfo') }}</h5>
      <a class="btn btn-primary" href="{{ route('users.index') }}"> {{ __('global.back') }}</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('global.fullName') }}:</strong>
                    {{ $user->firstname }} {{ $user->lastname }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('global.email') }}:</strong>
                    {{ $user->email }}
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('global.role') }}:</strong>
                    @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $v)
                            <label class="">{{ $v }}</label>
                        @endforeach
                    @endif
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>{{ __('global.loginAllowed') }}:</strong>
                    @if($user->allow_login == 1)
                        <label class="">{{ __('global.allow') }}</label>
                    @else
                        <label class="">{{ __('global.notAllow') }}</label>
                    @endif
                </div>
            </div>
        </div>
    </div>
  </div>
</div>
@endsection