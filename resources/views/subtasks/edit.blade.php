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
            <a href="{{ route('exportExcel.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_xls') }}</span></a>
            <a href="{{ route('exportPDF.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_pdf') }}</span></a>
            <a class="btn btn-primary" href="{{ route('tasks.edit', $sub_task->task_id) }}">
                <i class="me-1 fa fa-mail-reply"></i>
                {{ __('global.back') }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
@php $flag = false; @endphp
@if(Auth::user()->id == $sub_task->create_user || !empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin'))
    @php $flag = true; @endphp
@else
    @php $flag = false; @endphp
@endif

@php $assigned = false; @endphp
@foreach($sub_task->Assign() as $key => $assigned_user)
    @if($assigned_user == Auth::user()->email)
        @php $assigned = true; @endphp
    @else
        @php $assigned = false; @endphp
    @endif
@endforeach

<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="card-title fw-normal mb-0">{{ __('global.editSubTask') }}</h5>
            <div>
                <a class="btn btn-info" href="{{ route('subtasks.duplicate', $sub_task->id) }}">
                    <i class="me-1 fa fa-copy"></i><span class="d-lg-inline-flex d-none">{{ __('global.duplicate') }}
                </a>
                <a class="btn btn-primary" href="#settings" data-bs-toggle="offcanvas" role="button">
                    <i class="me-1 fa fa-cog"></i><span class="d-lg-inline-flex d-none">{{ __('global.set') }}
                </a>
            </div>
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

            {!! Form::model($sub_task, ['method' => 'post','route' => ['subtasks.update', $sub_task->id]]) !!}
            <div class="row g-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.title') }}:</strong>
                        @if($flag == true)
                        {!! Form::text('title', $sub_task->title, array('placeholder' => __('global.enterSubTaskTitle'),'class' => 'form-control')) !!}
                        @else
                        {!! Form::text('title', $sub_task->title, array('disabled' => true, 'placeholder' => __('global.enterSubTaskTitle'),'class' => 'form-control')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-4 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.status') }}:</strong>
                        @if($flag == true)
                        {!! Form::select('status', $status, $sub_task->status, array('class' => 'form-control','single')) !!}
                        @elseif($assigned == true)
                        {!! Form::select('status', $status, $sub_task->status, array('class' => 'form-control','single')) !!}
                        @else
                        {!! Form::select('status', $status, $sub_task->status, array('disabled' => true, 'class' => 'form-control','single')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.assignUsers') }}:</strong>
                        @if($flag == true)
                        {!! Form::select('assign_users[]', $users, $assign_users, array('class' => 'form-control','multiple')) !!}
                        @else
                        {!! Form::select('assign_users[]', $users, $assign_users, array('disabled' => true, 'class' => 'form-control','multiple')) !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="row g-3 mt-lg-3" style="">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    @if($flag == true)
                    <button type="submit" class="btn btn-primary f-right">{{ __('global.update') }}</button>
                    @elseif($assigned == true)
                    <button type="submit" class="btn btn-primary f-right">{{ __('global.update') }}</button>
                    @else
                    <button type="submit" class="btn btn-primary f-right" disabled>{{ __('global.update') }}</button>
                    @endif
                    <a class="btn btn-default f-right" href="{{ route('tasks.edit', $sub_task->task_id) }}"> {{ __('global.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>

<!--[ Start offcanvas:: Template Settings ]-->
<div class="offcanvas sm offcanvas-end w-660" tabindex="-1" id="settings">
    <div class="offcanvas-header">
        <!-- <h5 class="offcanvas-title">{{ __('global.field') }}</h5> -->
        <ul class="nav nav-tabs tab-card px-0 align-items-center" role="tablist">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#field_all" role="tab" aria-selected="true">{{ __('global.field') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#description_all" role="tab" aria-selected="false">{{ __('global.description') }}</a></li>
            <!-- <li class="nav-item ms-auto"><a data-bs-toggle="offcanvas" href="#create_task" role="button">New Task</a></li> -->
        </ul>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div id="full-container" class="offcanvas-body custom_scroll">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="field_all" role="tabpanel">
                <form method="post" action="{{ route('subtasks.comments.header', $sub_task->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.coordinater') }}:</strong>
                                <input placeholder="Coordinater name" class="form-control" name="coordinater" type="text" value="{{ $sub_task->coordinater }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.university') }}:</strong>
                                <input placeholder="University" class="form-control" name="university" type="text" value="{{ $sub_task->university }}">
                            </div>
                        </div>
                        <div class="btn-group-pos col-12">
                            <button class="btn btn-primary f-right" type="submit">
                                {{ __('global.save') }}
                            </button>
                        </div>
                    </div>
                </form>
                @php $i = 0; @endphp
                @if(count($comments) != 0)
                    @foreach($comments as $key => $comment)
                    <div id="comment_container{{$i}}">
                        <div class="row g-3 mb-2">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group d-flex-center">
                                    <strong class="mr-1">{{ __('global.title') }}:</strong>
                                    <input id="comment{{$i}}_title" type="text" class="form-control" placeholder="Field Title" value="{{$comment->title}}">
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group d-flex-center">
                                    <strong class="mr-1">{{ __('global.phase') }}:</strong>
                                    {!! Form::select('phase', $phase, $comment->phase, array('id' => 'comment'.$i.'_phase', 'class' => 'form-control', 'single')) !!}
                                </div>
                            </div>
                        </div>
                        <textarea id="comment{{$i}}">{{ $comment->content }}</textarea>
                        <div class="btn-group-pos col-12">
                            @if($flag == true)
                                <button class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button class="delete-comments btn btn-primary f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.delete') }}
                                </button>
                            @else
                                @if($assigned == true)
                                <button class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button class="delete-comments btn btn-primary f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.delete') }}
                                </button>
                                @else
                                <button disabled class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button disabled class="delete-comments btn btn-primary f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.delete') }}
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @php ++$i; @endphp
                    @endforeach
                @else
                <div id="comment_container0">
                    <div class="row g-3 mb-2">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.title') }}:</strong>
                                <input id="comment0_title" type="text" class="form-control" placeholder="Enter WP title">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.phase') }}:</strong>
                                {!! Form::select('phase', $phase, null, array('id' => 'comment0_phase', 'class' => 'form-control', 'single')) !!}
                            </div>
                        </div>
                    </div>
                    <textarea id="comment0"></textarea>
                    <div class="btn-group-pos col-12">
                        @if($flag == true)
                            <button class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            <button class="delete-comments btn btn-primary f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                        @else
                            @if($assigned == true)
                            <button class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            @else
                            <button disabled class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane fade" id="description_all" role="tabpanel">
                @if(count($sub_task->descriptions) != 0)
                    @foreach($sub_task->descriptions as $key => $description)
                    <div id="description_container{{$key}}">
                        <textarea id="description{{$key}}">{{ $description->content }}</textarea>
                        <div class="btn-group-pos col-12">
                            @if($flag == true)
                                <button class="save-descriptions btn btn-primary f-right" data-descriptionid="{{$key}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button class="delete-descriptions btn btn-default f-right mr-1" data-descriptionid="{{$key}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.delete') }}
                                </button>
                            @else
                                @if($assigned == true)
                                <button class="save-descriptions btn btn-primary f-right" data-descriptionid="{{$i}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button class="delete-descriptions btn btn-default f-right mr-1" data-descriptionid="{{$key}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.delete') }}
                                </button>
                                @else
                                <button disabled class="save-descriptions btn btn-primary f-right" data-descriptionid="{{$i}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button disabled class="delete-descriptions btn btn-default f-right mr-1" data-descriptionid="{{$key}}" data-oldid="{{ $description->id }}">
                                    {{ __('global.delete') }}
                                </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
    <div class="offcanvas-footer-btn txt-right">
        @if($flag == true)
            <button id="new_comment" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.field') }}</span>
            </button>
            <button id="new_description" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.description') }}</span>
            </button>
        @else
            @if($assigned == true)
            <button id="new_comment" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.field') }}</span>
            </button>
            <button id="new_description" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.description') }}</span>
            </button>
            @else
            <button id="new_comment" class="btn btn-success" disabled>
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.field') }}</span>
            </button>
            <button id="new_description" class="btn btn-success" disabled>
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.description') }}</span>
            </button>
            @endif
        @endif
    </div>
</div>
@endsection

@push('script')
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    var i = '{{ count($comments) }}';
    var description_number = '{{ count($sub_task->descriptions) }}';
    var readOnly = false;

    @if($flag == true)
        readOnly = false;
    @else
        @if($assigned == true)
            readOnly = false;
        @else
            readOnly = true;
        @endif
    @endif

    if(i == '0') {
        tinymce.init({
            readonly : readOnly,
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
                readonly : readOnly,
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
            readonly : readOnly,
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

    $('#new_comment').on('click', function() {
        var tmp = '';

        var j = 0, select_option = '<select id="comment' + i + '_phase" class="form-control" single="" name="phase"><option value="" selected="selected">Project of Stage</option>';
        for(j = 0; j < lang.phaseList.length; ++j) {
            select_option += '<option value="' + j + '">' + lang.phaseList[j] + '</option>';
        }
        select_option += '</select>'

        tmp = '<div id="comment_container' + i + '">' +
                '<div class="row g-3 mb-2">' + 
                    '<div class="col-lg-6 col-md-6 col-sm-12">' + 
                        '<div class="form-group d-flex-center">' + 
                            '<strong class="mr-1">' + lang.title + ':</strong>' +
                            '<input id="comment' + i + '_title" type="text" class="form-control" placeholder="Field Title">' +
                        '</div>' + 
                    '</div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-12">' + 
                        '<div class="form-group d-flex-center">' + 
                            '<strong class="mr-1">' + lang.phase + ':</strong>' +
                            select_option +
                        '</div>' + 
                    '</div>' +
                '</div>' + 
                '<textarea id="comment' + i + '" data-oldid="null"></textarea>' + 
                '<div class="btn-group-pos col-12">' + 
                  '<button class="save-comments btn btn-primary f-right" data-commentid="' + i + '">' + lang.save + '</button>' +
                  '<button class="delete-comments btn btn-primary f-right mr-1" data-commentid="' + i + '">' + lang.delete + '</button>' +
                '</div>' +
              '</div>';
        $('#field_all').append(tmp);

        tinymce.init({
            selector: '#comment' + i,
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification');
                freeTiny.style.display = 'none';
            },
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
    });

    $('#field_all').on('click', '.save-comments', function() {
        var selected_id = $(this).attr('data-commentid');
        var old_id = $(this).attr('data-oldid');

        var title = $('#comment' + selected_id + '_title').val();
        var phase = $('#comment' + selected_id + '_phase').val();
        
        tinymce.triggerSave();
        var content = tinymce.get('comment' + selected_id).getContent();

        if(title == "" || content == "") {
            new bs5.Toast({
                body: lang.checkInputContent,
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
                'title': title,
                'phase': phase,
                'old_id': old_id,
                'type_id': '{{ $sub_task->id }}',
                'type': 'subTask',
                'content': content
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-comments[data-commentid="' + selected_id + '"]').attr('data-oldid', result['new']);
                        $('.delete-comments[data-commentid="' + selected_id + '"]').attr('data-oldid', result['new']);
                    }
                    
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
            }
        });
    });

    $('#field_all').on('click', '.delete-comments', function() {
        var old_id = $(this).attr('data-oldid');
        $(this).parent().parent().remove();

        if(old_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '/jobcards/delete_comments',
                data: {
                    'old_id': old_id,
                    'type_id': '{{ $sub_task->id }}',
                    'type': 'subTask'
                },
                success: function(result) {
                    if(result['success'] == true) {
                        new bs5.Toast({
                            body: lang.deleteSuccess,
                            className: 'border-0 bg-success text-white',
                            btnCloseWhite: true,
                        }).show();
                    } else {
                        new bs5.Toast({
                            body: lang.unexpectedError,
                            className: 'border-0 bg-danger text-white',
                            btnCloseWhite: true,
                        }).show();
                    }
                }, error: function(res) {
                    new bs5.Toast({
                        body: lang.unexpectedError,
                        className: 'border-0 bg-danger text-white',
                        btnCloseWhite: true,
                    }).show();
                }
            });
        } else {
            new bs5.Toast({
                body: lang.deleteSuccess,
                className: 'border-0 bg-success text-white',
                btnCloseWhite: true,
            }).show();
        }
    });

    $('#new_description').on('click', function() {
        var tmp = '';
        tmp = '<div id="description_container' + description_number + '">' +
                '<textarea id="description' + description_number + '" data-oldid="null"></textarea>' + 
                '<div class="btn-group-pos col-12">' + 
                  '<a class="save-descriptions btn btn-primary f-right" data-descriptionid="' + description_number + '">' + lang.save + '</a>' +
                  '<a class="delete-descriptions btn btn-default f-right mr-1" data-descriptionid="' + description_number + '">' + lang.delete + '</a>' +
                '</div>' +
              '</div>';
        $('#description_all').append(tmp);

        tinymce.init({
            selector: '#description' + description_number,
            init_instance_callback : function(editor) {
                var freeTiny = document.querySelector('.tox .tox-notification');
                freeTiny.style.display = 'none';
            },
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
            tinycomments_mode: 'embedded',
            tinycomments_author: 'Author name',
            mergetags_list: [
                { value: 'First.Name', title: 'First Name' },
                { value: 'Email', title: 'Email' },
            ]
        });        
        ++description_number;
    });

    $('#description_all').on('click', '.save-descriptions', function() {
        var selected_id = $(this).attr('data-descriptionid');
        var old_id = $(this).attr('data-oldid');

        tinymce.triggerSave();
        var content = tinymce.get('description' + selected_id).getContent();

        if(content == "") {
            new bs5.Toast({
                body: lang.checkInputContent,
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
            url: '/jobcards/save_descriptions',
            data: {
                'old_id': old_id,
                'type_id': '{{ $sub_task->id }}',
                'type': 'subTask',
                'content': content
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-descriptions[data-descriptionid="' + selected_id + '"]').attr('data-oldid', result['new']);
                        $('.delete-descriptions[data-descriptionid="' + selected_id + '"]').attr('data-oldid', result['new']);
                    }
                    
                    new bs5.Toast({
                        body: lang.descriptionSaveSuccess,
                        className: 'border-0 bg-success text-white',
                        btnCloseWhite: true,
                    }).show();
                } else {
                    new bs5.Toast({
                        body: lang.unexpectedError,
                        className: 'border-0 bg-danger text-white',
                        btnCloseWhite: true,
                    }).show();
                }
            }, error: function(res) {
                new bs5.Toast({
                    body: lang.unexpectedError,
                    className: 'border-0 bg-danger text-white',
                    btnCloseWhite: true,
                }).show();
            }
        });
    });

    $('#description_all').on('click', '.delete-descriptions', function() {
        var old_id = $(this).attr('data-oldid');
        $(this).parent().parent().remove();

        if(old_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '/jobcards/delete_descriptions',
                data: {
                    'delete_id': old_id,
                },
                success: function(result) {
                    if(result['success'] == true) {
                        new bs5.Toast({
                            body: lang.deleteSuccess,
                            className: 'border-0 bg-success text-white',
                            btnCloseWhite: true,
                        }).show();
                    } else {
                        new bs5.Toast({
                            body: lang.unexpectedError,
                            className: 'border-0 bg-danger text-white',
                            btnCloseWhite: true,
                        }).show();
                    }
                }, error: function(res) {
                    new bs5.Toast({
                        body: lang.unexpectedError,
                        className: 'border-0 bg-danger text-white',
                        btnCloseWhite: true,
                    }).show();
                }
            });
        } else {
            new bs5.Toast({
                body: lang.deleteSuccess,
                className: 'border-0 bg-success text-white',
                btnCloseWhite: true,
            }).show();
        }
    });
</script>
@endpush