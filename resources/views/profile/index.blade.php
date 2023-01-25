@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title mb-lg-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item active font-size-28">
                {{ __('global.profile') }}
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row g-xl-4 g-lg-3 g-2 justify-content-between">
        <div class="col-xxl-9 col-xl-12 col-lg-12 order-1 order-xxl-0">
            {!! Form::open(array('route' => 'profile.store','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-md-start align-items-center flex-column flex-md-row">
                        <div>
                            @if($user->hasMedia('avatar'))
                            <img id="avatar_container" src="{{ $user->getMedia('avatar')[0]->getUrl() }}" width="160" height="160" title="avatar" class="rounded-4">
                            @else
                            <img id="avatar_container" src="{{ url('storage/sample') }}" width="160" height="160" title="avatar" class="rounded-4">
                            @endif
                        </div>
                        <div class="media-body ms-md-5 m-0 mt-4 mt-md-0 text-md-start text-center">
                            <h4 class="mb-1">{{ $user->firstname }} {{ $user->lastname }}</h4>
                            <p>{!! Str::of($user->email)->limit(40); !!}</p>
                            @if($user->about)
                            <span class="text-muted">{{ $user->about }}</span>
                            @else
                            <br>
                            @endif
                            <div class="my-3">
                                <!-- <button class="btn btn-primary">Follow</button> -->
                                <!-- <button class="btn btn-primary">{{ __('global.message') }}</button> -->
                                <!-- <div> -->
                                    <input type="file" name="user_avatar" id="user_avatar" hidden/>
                                    <label class="btn btn-success" for="user_avatar">{{ __('global.avatar') }}</label>
                                <!-- </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('global.editProfile') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.firstName') }}</label>
                            <input id="firstname" type="text" class="form-control @error('name') is-invalid @enderror" name="firstname" value="{{ $user->firstname }}" required autocomplete="firstname" placeholder="John">
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.lastName') }}</label>
                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ $user->lastname }}" required autocomplete="lastname" placeholder="Parker">
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.emailAddress') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" placeholder="name@example.com">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small text-muted">{{ __('global.address') }}</label>
                            <input id="address" name="address" type="text" class="form-control" placeholder="Home Address" value="{{ $user->address }}">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.phone') }}</label>
                            <input id="phone" name="phone" type="number" class="form-control" placeholder="Phone number" value="{{ $user->phone }}">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-muted">{{ __('global.aboutMe') }}</label>
                            <textarea name="about" rows="5" class="form-control" placeholder="Here can be your description" maxlength="140">{{ $user->about }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('global.update') }}</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div> <!--[ .row end ]-->
</div>
@endsection

@push('script')
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    $('#user_avatar').on("change", function () {
        // the files is a new property from the new File API, if if it is not supported assign an empty array as the value of files
        var files = !! this.files ? this.files : [];

        //if there are no files and FileReader is not supported return
        if (!files.length || !window.FileReader) return;

        var file_name = files[0].name;

        if (/^image/.test(files[0].type)) {
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onloadend = function (event) {
                $('#avatar_container').prop('src', event.target.result);
                $('#avatar_container').prop('title', file_name);
            }
        } else {
            new bs5.Toast({
                body: lang.selectErrorAvatar,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
        }
    });
</script>
@endpush