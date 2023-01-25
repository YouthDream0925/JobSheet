@extends('layouts.index')

@section('breadcrumb')
<div class="page-title mb-lg-4">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item active">
                {{ __('global.message') }}
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid" style="padding: 0 !important;">
    <div class="row g-xl-4 g-lg-3 g-2 justify-content-between">
        <div class="col-xxl-12 col-xl-12 col-lg-12">
            <div class="page-inner-layout">
            <!--[ Start:: user list ]-->
            <div class="order-1">
                <div class="c-list p-3">
                <form method="GET" action="{{ route('messages.index') }}">
                    <div class="input-group mb-1">
                        <input name="search" type="text" class="form-control" value="{{ request()->input('search') }}" placeholder="Search for...">
                        <button class="btn btn-dark" type="submit" data-bs-toggle="offcanvas" data-bs-target="#Compose">{{ __('global.search') }}</button>
                    </div>
                </form>
                </div>
                <div class="custom_scroll" style="height: calc(100vh - 305px);">
                    <ul class="list-group list-group-custom list-group-flush user-list mb-0" role="tablist">
                        @foreach($notifications as $key => $notification)
                        <li class="list-group-item">
                            <a href="{{ route('messages.index', $notification->id) }}" class="d-flex">
                                @if(Auth::user()->sender($notification->data['sender'])->hasMedia('avatar'))
                                    <img class="avatar rounded-circle" src="{{ Auth::user()->sender($notification->data['sender'])->getMedia('avatar')[0]->getUrl() }}" alt="">
                                    @if($notification->unread())
                                    <span class="bullet-dot bg-accent animation-blink span-alarm-pos-1"></span>
                                    @endif
                                @else
                                    <img class="avatar rounded-circle" src="{{ url('storage/sample') }}" alt="">
                                    @if($notification->unread())
                                    <span class="bullet-dot bg-accent animation-blink span-alarm-pos-1"></span>
                                    @endif
                                @endif
                                <div class="flex-fill ms-3">
                                    <h6 class="d-flex justify-content-between mb-0"><span>{{ Auth::user()->sender($notification->data['sender'])->firstname }} {{ Auth::user()->sender($notification->data['sender'])->lastname }}</span> <small class="msg-time">{{ $notification->created_at }}</small></h6>
                                    <span class="text-muted small">{{ $notification->data['message'] }}</span>
                                </div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!--[ Start:: email details ]-->
            <div class="order-2 flex-grow-1 custom_scroll card">
                <div class="action-header px-xl-4">
                    <a class="d-flex text-decoration-none" href="javascript:void(0);" title="">
                        <div class="avatar rounded-circle no-thumbnail">
                            <img class="avatar rounded-circle" src="{{ $user->getMedia('avatar')[0]->getUrl() }}" alt="">
                        </div>
                        <div class="ms-3">
                        <h6 class="mb-0">{{ Auth::user()->firstname }} {{ Auth::user()->lastname }}</h6>
                        <small class="text-muted">{{ __('global.online') }}</small>
                        </div>
                    </a>
                </div>
                <div class="action-body custom_scroll px-xl-4">
                    <ul class="list-unstyled mb-0">
                        @if($selected_notification != null)
                        <li class="mb-4 d-flex flex-row align-items-end">
                            <div class="max-width-70">
                                <div class="user-info mb-1">
                                @if(Auth::user()->sender($selected_notification->data['sender'])->hasMedia('avatar'))
                                    <img class="avatar xs rounded-circle me-1" src="{{ Auth::user()->sender($selected_notification->data['sender'])->getMedia('avatar')[0]->getUrl() }}" alt="">
                                @else
                                    <img class="avatar xs rounded-circle me-1" src="{{ url('storage/sample') }}" alt="">
                                @endif
                                <span class="text-muted small">{{ $selected_notification->created_at }}</span>
                                </div>
                                <div class="bg-info bg-opacity-10 p-2 rounded">
                                <div class="message">{{ $selected_notification->data['message'] }}</div>
                                </div>
                            </div>
                            <a href="#" class="nav-link py-2 px-3 text-muted" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                                <a class="dropdown-item" href="{{ route('messages.delete', $selected_notification->id) }}"><i class="me-3 fa fa-trash"></i>Delete</a>
                            </div>
                        </li>
                        @endif
                    </ul>
                </div>
            </div>
            </div>
        </div>
    </div> <!--[ .row end ]-->
</div>
@endsection

@push('script')
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    tinymce.init({
        init_instance_callback : function(editor) {
            var freeTiny = document.querySelector('.tox .tox-notification--in');
            freeTiny.style.display = 'none';
        },
        selector: '#newMessage',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ]
    });

    $('#send_new_message').click(function(event){
        var form =  $(this).closest("form");
        event.preventDefault();

        tinymce.triggerSave();
        var message = tinymce.get('newMessage').getContent();

        if(message == "")
        {
            new bs5.Toast({
                body: lang.confirmInputValue,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
        }
        else
        {
            form.submit();
        }
    });
</script>
@endpush