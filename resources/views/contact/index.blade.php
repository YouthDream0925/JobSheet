@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title mb-lg-0 pb-0">
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
    <div class="row g-xl-4 g-lg-3 g-2">
        <div class="col-xxl-12 col-xl-12 col-lg-12 order-1 order-xxl-0">
            {!! Form::open(array('route' => 'contact.send','method'=>'POST', 'enctype' => 'multipart/form-data')) !!}
            <div class="card mt-3">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('global.contactUs') }}</h4>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.firstName') }}</label>
                            <input id="firstname" type="text" class="form-control @error('name') is-invalid @enderror" name="firstname" value="{{ $user->firstname }}" required autocomplete="firstname" placeholder="John" disabled>
                            @error('firstname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.lastName') }}</label>
                            <input id="lastname" type="text" class="form-control @error('lastname') is-invalid @enderror" name="lastname" value="{{ $user->lastname }}" required autocomplete="lastname" placeholder="Parker" disabled>
                            @error('lastname')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <label class="form-label small text-muted">{{ __('global.emailAddress') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $user->email }}" required autocomplete="email" placeholder="name@example.com" disabled>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12">
                            <label class="form-label small text-muted">{{ __('global.problem') }}</label>
                            <textarea id="problem" name="problem" rows="5" class="form-control @error('problem') is-invalid @enderror" placeholder="Please enter your problem" maxlength="255"></textarea>
                            @error('problem')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-12 d-flex-center" style="justify-content: center;">
                            <div class="my-3 mr-4">
                                <input type="file" name="problem_screen" id="problem_screen" hidden/>
                                <label class="btn btn-success" for="problem_screen">{{ __('global.screen') }}</label>
                            </div>
                            <img id="screen_container" src="{{ url('storage/error') }}" width="250" height="250" title="Screenshot" class="rounded-4 @error('problem_screen') border-red @enderror">
                            <!-- @error('problem_screen')
                                <span class="" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror -->
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">{{ __('global.send') }}</button>
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
    $('#problem_screen').on("change", function () {
        // the files is a new property from the new File API, if if it is not supported assign an empty array as the value of files
        var files = !! this.files ? this.files : [];

        //if there are no files and FileReader is not supported return
        if (!files.length || !window.FileReader) return;

        var file_name = files[0].name;

        if (/^image/.test(files[0].type)) {
            var reader = new FileReader();
            reader.readAsDataURL(files[0]);
            reader.onloadend = function (event) {
                $('#screen_container').prop('src', event.target.result);
                $('#screen_container').prop('title', file_name);
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