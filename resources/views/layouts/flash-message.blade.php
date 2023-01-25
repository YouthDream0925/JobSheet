@if ($message = Session::get('success'))
<div class="alert alert-success alert-block">
    <p class="mb-lg-0">{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger alert-block">
    <p class="mb-lg-0">{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('warning'))
<div class="alert alert-warning alert-block">
    <p class="mb-lg-0">{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('info'))
<div class="alert alert-info alert-block">
    <p class="mb-lg-0">{{ $message }}</p>
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    <p class="mb-lg-0">{{ __('global.anyError') }}</p>
</div>
@endif