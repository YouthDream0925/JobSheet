@extends('layouts.index')

@section('breadcrumb')
<div class="page-title mb-lg-4 pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a href="{{ route('roles.index') }}">{{ __('global.roles') }}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page">{{ __('global.edit') }}</li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="card-title fw-normal mb-0">{{ __('global.editRole') }}</h5>
            <a class="btn btn-primary" href="{{ route('roles.index') }}"> {{ __('global.back') }}</a>
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

            {!! Form::model($role, ['method' => 'PATCH','route' => ['roles.update', $role->id]]) !!}
            <div class="row g-3">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-floating">
                        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control')) !!}
                        <label>{{ __('global.enterRoleName') }}</label>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="form-group">
                        <strong>{{ __('global.permission') }}:</strong>
                        <br/>
                        @foreach($permission as $value)
                            <label>{{ Form::checkbox('permission[]', $value->id, in_array($value->id, $rolePermissions) ? true : false, array('class' => 'name')) }}
                            {{ $value->name }}</label>
                        <br/>
                        @endforeach
                    </div>
                </div>
                <div class="col-12">
                <button type="submit" class="btn btn-primary">{{ __('global.update') }}</button>
                <a class="btn btn-default" href="{{ route('roles.index') }}"> {{ __('global.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection