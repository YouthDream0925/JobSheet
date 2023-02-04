@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title mb-lg-4 pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="txt-deco" href="{{ route('jobcards.index') }}">{{ __('global.WPs') }}</a>
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
            <h5 class="card-title fw-normal mb-0">{{ __('global.createWP') }}</h5>
            <a class="btn btn-primary" href="{{ route('jobcards.index') }}">
                <i class="me-1 fa fa-mail-reply"></i>
                {{ __('global.back') }}
            </a>
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

            {!! Form::open(array('route' => 'jobcards.store','method'=>'POST')) !!}
            <div class="row g-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.title') }}:</strong>
                        {!! Form::text('title', null, array('placeholder' => __('global.enterWpTitle'),'class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-12">
                    <strong>{{ __('global.order') }}:</strong>
                    {!! Form::text('order', null, array('placeholder' => __('global.enterOrderNum'),'class' => 'form-control')) !!}
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6">
                    <strong>{{ __('global.special') }}:</strong>
                    <div class="form-check form-switch">
                        <input name="special" class="form-check-input" type="checkbox" role="switch" value="true">
                    </div>
                </div>

                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.assignUsers') }}:</strong>
                        {!! Form::select('assign_users[]', $users,[], array('class' => 'form-control','multiple')) !!}
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-lg-3" style="">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <button type="submit" class="btn btn-primary f-right">{{ __('global.create') }}</button>
                    <a class="btn btn-default f-right mr-1" href="{{ route('jobcards.index') }}"> {{ __('global.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection

@push('script')
@endpush