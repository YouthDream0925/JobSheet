@extends('layouts.index')

@section('breadcrumb')
<div class="page-title mb-lg-4 pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a href="{{ route('users.index') }}">{{ __('global.users') }}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page">{{ __('global.create') }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="card-title fw-normal mb-0">{{ __('global.createUser') }}</h5>
            <a class="btn btn-primary" href="{{ route('users.index') }}"> {{ __('global.back') }}</a>
        </div>

        <div class="card-body">
            @if (count($errors) > 0)
            <div class="alert alert-danger">
                <strong>{{ __('global.wow') }}</strong> {{ __('global.inputError') }}<br><br>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
                </ul>
            </div>
            @endif

            {!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
            <div class="row g-3">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating">
                        {!! Form::text('firstname', null, array('placeholder' => __('global.enterFirstName'),'class' => 'form-control')) !!}
                        <label>{{ __('global.enterFirstName') }}</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating">
                        {!! Form::text('lastname', null, array('placeholder' => __('global.enterLastName'),'class' => 'form-control')) !!}
                        <label>{{ __('global.enterLastName') }}</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating">
                        {!! Form::text('email', null, array('placeholder' => __('global.enterEmail'),'class' => 'form-control')) !!}
                        <label>{{ __('global.enterEmail') }}</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating">
                        {!! Form::password('password', array('placeholder' => __('global.enterPassword'),'class' => 'form-control')) !!}
                        <label>{{ __('global.enterPassword') }}</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating" style="justify-content-middle">
                        {!! Form::password('confirm-password', array('placeholder' => __('global.confirmPassword'),'class' => 'form-control')) !!}
                        <label>{{ __('global.confirmPassword') }}</label>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <label style="position: relative; top: 20px;">{{ Form::checkbox('allow_login', null, false, array('class' => '')) }} {{ __('global.allowLogin') }}</label>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px;">
                    <div class="form-group">
                        <strong>{{ __('global.role') }}:</strong>
                        {!! Form::select('roles[]', $roles,[], array('class' => 'form-control','single')) !!}
                    </div>
                </div>
                <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('global.create') }}</button>
                <a class="btn btn-default" href="{{ route('users.index') }}"> {{ __('global.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection