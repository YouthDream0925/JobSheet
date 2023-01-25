@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('jobcards.index') }}" data-toggle="tooltip" data-bs-original-title="{{ $sub_task->task->jobcard->title }}">{!! Str::of($sub_task->task->jobcard->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item font-size-28">
                <a class="task-title-color txt-deco" href="{{ route('jobcards.edit', $sub_task->job()) }}" data-toggle="tooltip" data-bs-original-title="{{ $sub_task->task->title }}">{!! Str::of($sub_task->task->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item font-size-28">
                <a class="sub-title-color txt-deco" href="{{ route('tasks.edit', $sub_task->task_id) }}" data-toggle="tooltip" data-bs-original-title="{{ $sub_task->title }}">{!! Str::of($sub_task->title)->limit(20); !!}</a>
            </li>
        </ol>
        <div>
            @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('Supervisor'))
            <a href="{{ route('exportExcel.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_xls') }}</span></a>
            <a href="{{ route('exportPDF.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_pdf') }}</span></a>
            @endif
            <a class="btn btn-primary" href="{{ route('jobcards.edit', $sub_task->job()) }}">
                <i class="me-1 fa fa-mail-reply"></i>
                {{ __('global.back') }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs tab-card px-0 mb-3 align-items-center" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#field_all" role="tab" aria-selected="true">{{ __('global.field') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#description_all" role="tab" aria-selected="false">{{ __('global.description') }}</a></li>
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="field_all" role="tabpanel">
                    <div class="row g-3">
                        {!! Form::model($sub_task, ['method' => 'post','route' => ['subtasks.update', $sub_task->id]]) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.coordinater') }}:</strong>
                                <p class="mb-0">{{ $sub_task->coordinater }}</p>
                            </div>
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.university') }}:</strong>
                                <p class="mb-0">{{ $sub_task->university }}</p>
                            </div>
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.status') }}:</strong>
                                {!! Form::select('status', $status, $sub_task->status, array('id' => 'task_status', 'class' => '', 'onchange' => 'doSomething()')) !!}
                            </div>
                            <button type="submit" id="submitBtn" style="display: none;"></button>
                        </div>
                        {!! Form::close() !!}
                        @php $i = 0; @endphp
                        @if(count($comments) != 0)
                            @foreach($comments as $key => $comment)
                            <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="mb-1">
                                    <div>
                                        <strong class="mr-1 w-100-txt-right">{{ __('global.title') }}:</strong> <label data-toggle="tooltip" data-bs-original-title="{{ $comment->title }}">{!! Str::of($comment->title)->limit(20); !!}</label>
                                        @if($comment->phase) <strong class="mr-1 w-100-txt-right ml-1">{{ __('global.phase') }}:</strong> {{ $phase[$comment->phase] }} @endif
                                    </div>
                                    <textarea id="comment{{$i}}">{{ $comment->content }}</textarea>
                                </div>
                                <div class="btn-group-pos">
                                    <button class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                        {{ __('global.save') }}
                                    </button>
                                </div>
                            </div>
                            @php ++$i; @endphp
                            @endforeach
                        @else
                            <p class="txt-center">{{ __('global.noTextField') }}</p>
                            <!-- <div class="col-lg-12 col-md-12 col-sm-12">
                                <div class="d-flex mb-1">
                                    <strong class="mr-1 w-100-txt-right">{{ __('global.comment') }}:</strong>
                                    <textarea id="comment0"></textarea>
                                </div>
                                <div class="btn-group-pos">
                                    <button class="save-comments btn btn-primary f-right" data-commentid="0" data-oldid="null">
                                        {{ __('global.save') }}
                                    </button>
                                </div>
                            </div> -->
                        @endif
                    </div>
                </div>
                <div class="tab-pane fade" id="description_all" role="tabpanel">
                    @if(count($sub_task->descriptions) != 0)
                        @foreach($sub_task->descriptions as $key => $description)
                        <div id="description_container{{$key}}" class="mb-2">
                            <textarea id="description{{$key}}">{{ $description->content }}</textarea>
                        </div>
                        @endforeach
                    @else
                        <p class="txt-center">{{__('global.noData')}}</p>
                    @endif
                </div>
            </div>            
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    function doSomething()
    {
        var status = $('#task_status').val();
        if(status != "")
            $('#submitBtn').click();
    }

    var i = '{{ count($comments) }}';
    var description_number = '{{ count($sub_task->descriptions) }}';

    if(i == '0') {
        tinymce.init({
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification--in');
                freeTiny.style.display = 'none';
            },
            selector: '#comment0',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ]
        });
        ++i;
    } else {
        for(var k=0; k<i; k++) {
            tinymce.init({
                init_instance_callback : function(editor) {
                    var freeTiny = document.querySelector('.tox .tox-notification--in');
                    freeTiny.style.display = 'none';
                },
                selector: '#comment' + k,
                plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [
                    { value: 'First.Name', title: 'First Name' },
                    { value: 'Email', title: 'Email' },
                ]
            });
        }
    }

    for(var k=0; k<description_number; k++) {
        tinymce.init({
            readonly : true,
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification');
                freeTiny.style.display = 'none';
            },
            selector: '#description' + k,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ]
        });
    }

    $('.save-comments').on('click', function() {
        var selected_id = $(this).attr('data-commentid');
        var old_id = $(this).attr('data-oldid');

        tinymce.triggerSave();
        var content = tinymce.get('comment' + selected_id).getContent();

        if(content == "") {
            new bs5.Toast({
                body: lang.confirmInputValue,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
            return;
        }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            type: 'post',
            url: '/subtasks/save_comments',
            data: {
                'old_id': old_id,
                'type_id': '{{ $sub_task->id }}',
                'type': 'subTask',
                'content': content
            },
            success: function(result) {
                if(result['success'] == true) {
                    new bs5.Toast({
                        body: lang.commentSaveSuccess,
                        className: 'border-0 bg-success text-white',
                        btnCloseWhite: true,
                    }).show();
                } else {
                    new bs5.Toast({
                        body: lang.commentSaveError,
                        className: 'border-0 bg-danger text-white',
                        btnCloseWhite: true,
                    }).show();
                }
            }, error: function(res) {
                new bs5.Toast({
                    body: lang.commentSaveError,
                    className: 'border-0 bg-danger text-white',
                    btnCloseWhite: true,
                }).show();
            }});
        });
        
</script>
@endpush