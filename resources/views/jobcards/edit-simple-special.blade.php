@extends('layouts.index')

@push('css')
<link rel="stylesheet" href="{{ asset('dist/bundles/flatpickr.min.css') }}">
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('jobcards.index') }}" data-toggle="tooltip" data-bs-original-title="{{ $job_card->title }}">{!! Str::of($job_card->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page" data-toggle="tooltip" data-bs-original-title="{{ $job_card->description }}">{!! Str::of($job_card->title)->limit(40); !!}</li>
        </ol>
        <div>
            <!-- @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('Supervisor'))
            <a href="{{ route('exportExcel', $job_card->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_xls') }}</span></a>
            <a href="{{ route('exportPDF', $job_card->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export') }}</span></a>
            @endif -->
            <a class="btn btn-primary" href="{{ route('jobcards.index')}}"><i class="me-1 fa fa-mail-reply"></i><span class="d-lg-inline-flex d-none">{{ __('global.back') }}</a>
        </div>
    </div>
</div>
@endsection

@section('content')
@php $flag = false; @endphp
@if(Auth::user()->email == $job_card->creator())
    @php $flag = true; @endphp
@else
    @php $flag = false; @endphp
@endif

@php $assigned = false; @endphp
@foreach($job_card->assign() as $key => $assigned_user)
    @if($assigned_user == Auth::user()->email)
        @php $assigned = true; @endphp
    @else
        @php $assigned = false; @endphp
    @endif
@endforeach

<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <ul class="nav nav-tabs tab-card px-0 mb-3 align-items-center" role="tablist">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#field_all" role="tab" aria-selected="true">{{ __('global.field') }}</a></li>                
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#question_all" role="tab" aria-selected="false">{{ __('global.question') }}</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#description_all" role="tab" aria-selected="false">{{ __('global.description') }}</a></li>
            </ul>
            @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('Supervisor'))
            <div>
                <a href="{{ route('expoert.wps.special', $job_card->id) }}" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.exportQuestionAndMore') }}</span></a>
            </div>
            @endif
        </div>

        <div class="card-body pt-0">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="field_all" role="tabpanel">
                    <div class="row g-3">
                        {!! Form::model($job_card, ['method' => 'PATCH','route' => ['jobcards.update', $job_card->id]]) !!}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.coordinater') }}:</strong>
                                <p class="mb-0">{{ $job_card->coordinater }}</p>
                            </div>
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.university') }}:</strong>
                                <p class="mb-0">{{ $job_card->university }}</p>
                            </div>
                            <div class="d-flex mb-1">
                                <strong class="mr-1">{{ __('global.status') }}:</strong>
                                {!! Form::select('status', $status, $job_card->status, array('id' => 'task_status', 'class' => '', 'onchange' => 'doSomething()')) !!}
                            </div>
                            <button type="submit" id="submitBtn" style="display: none;"></button>
                        </div>
                        {!! Form::close() !!}
                        @php $i = 0; @endphp
                        @if(count($job_card->comments) != 0)
                            @foreach($job_card->comments as $key => $comment)
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
                                    <textarea id="comment{{$i}}"></textarea>
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
                <div class="tab-pane fade" id="question_all" role="tabpanel">
                    <div id="question_description_container" class="m-b-1">
                        @if($job_card->question_description != null)
                        <textarea id="question_description">{{ $job_card->question_description->content }}</textarea>
                        @endif
                    </div>
                    <div id="all_questions">
                        <div class="row">
                            @foreach($job_card->questions as $question_key => $question)
                            <div class="col-md-4">
                                @if($question->type == 0)
                                <div class="row g-3 mb-1">
                                    <div class="col-12">
                                        <div class="form-group d-flex-center">
                                            <input type="hidden" id="question{{$question_key}}" plasceholer="Enter new question" class="form-control" name="question" type="text" value="{{ $question->content }}" disabled data-type="{{ $question->type }}" data-oldid="{{$question->id}}" data-toggle="tooltip" data-bs-original-title="{{$question->content}}">
                                            <p class="form-control mb-0" data-toggle="tooltip" data-bs-original-title="{{$question->content}}" aria-label="Description"> {{ $question->content }} </p>
                                        </div>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-11">
                                        <div class="form-group" id="answer_all{{$question_key}}">
                                            {!! Form::select('answers', $question->answers_list(), $question->correct_answer(), array('id' => 'answers'.$question_key, 'class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="row g-3 mb-1">
                                    <div class="col-12">
                                        <div class="form-group d-flex-center">
                                            <input type="hidden" id="question{{$question_key}}" plasceholer="Enter new question" class="form-control" name="question" type="text" value="{{ $question->content }}" disabled data-type="{{ $question->type }}" data-oldid="{{$question->id}}">
                                            <p class="form-control mb-0" data-toggle="tooltip" data-bs-original-title="{{$question->content}}" aria-label="Description"> {{ $question->content }} </p>
                                        </div>
                                    </div>
                                    <div class="col-1"></div>
                                    <div class="col-11">
                                        <div class="form-group d-flex">
                                            @if(count($question->answers))
                                                @foreach($question->answers as $answer_key => $answer)
                                                <input type="text" class="date-answer{{$question_key}} form-control flatpickr f-basic" placeholder="Select date" value="{{$answer->answer}}">
                                                @endforeach
                                            @else
                                                <input type="text" class="date-answer{{$question_key}} form-control flatpickr f-basic" placeholder="Select date" value="">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>    
                            @endforeach
                            <div class="btn-group-pos col-12">
                                <button id="all_save_answers" class="btn btn-primary f-right">
                                    {{ __('global.save') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="description_all" role="tapanel">
                    @foreach($job_card->descriptions as $key => $description)
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="mb-1">
                            <textarea id="description{{$key}}">{{ $description->content }}</textarea>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('dist/bundles/flatpickr.bundle.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    // basic picker
    flatpickr(".f-basic", {
        allowInput: true
    });


    function doSomething()
    {
        var status = $('#task_status').val();
        if(status != "")
            $('#submitBtn').click();
    }

    var question_number = '{{ count($job_card->questions) }}';
    var description_number = '{{ count($job_card->descriptions) }}';
    var i = '{{ count($job_card->comments) }}';

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
            readonly: true,
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
            url: '/jobcards/save_comments',
            data: {
                'old_id': old_id,
                'type_id': '{{ $job_card->id }}',
                'type': 'job',
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
            }
        });
    });

    tinymce.init({
        readonly : true,
        init_instance_callback : function(editor) {
            var freeTiny = document.querySelector('.tox .tox-notification');
            freeTiny.style.display = 'none';
        },
        selector: '#question_description',
        plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount checklist mediaembed casechange export formatpainter pageembed linkchecker a11ychecker tinymcespellchecker permanentpen powerpaste advtable advcode editimage tinycomments tableofcontents footnotes mergetags autocorrect',
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Author name',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ]
    });

    $('#all_questions').on('click', '.save-answers', function() {
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        var input_check = false;
        var question_value = $('#all_questions #question' + question_id).val();
        var answers = $('#answers' + question_id).val();

        if(answers.length == 0) {
            new bs5.Toast({
                body: lang.confirmSelectAnswer,
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
            url: '/jobcards/save_questions',
            data: {
                'old_id': question_oldid,
                'job_id': '{{ $job_card->id }}',
                'content': question_value,
                'type': 0,
                'answers': answers,
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-answers[data-questionid="' + question_id + '"]').attr('data-oldid', result['new']);
                        $('.delete-question[data-questionid="' + question_id + '"]').attr('data-oldid', result['new']);
                    }
                    
                    new bs5.Toast({
                        body: lang.answerSelectedSuccess,
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

    $('#all_questions').on('click', '.save-date-answers', function() {
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        var input_check = false;
        var question_value = $('#all_questions #question' + question_id).val();
        var answers = [];
        $('.date-answer' + question_id).each(function (i, elem) {
            answers[i] = elem.value;
            if(elem.value == "") {
                input_check = true;
                return;
            }
        });

        if(input_check == true) {
            new bs5.Toast({
                body: lang.confirmInputAnswer,
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
            url: '/jobcards/save_questions',
            data: {
                'old_id': question_oldid,
                'job_id': '{{ $job_card->id }}',
                'content': question_value,
                'type': 1,
                'answers': answers,
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-date-answers[data-questionid="' + question_id + '"]').attr('data-oldid', result['new']);
                    }
                    
                    new bs5.Toast({
                        body: lang.questionSaveSuccess,
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

    $('#all_save_answers').on('click', function() {
        var save_data = [];

        for(var j=0; j<question_number; j++) {
            var question_value = $('#question' + j).val();
            var question_type = $('#question' + j).attr('data-type');
            var question_oldid = $('#question' + j).attr('data-oldid');

            save_object = {}
            save_object ["question_value"] = question_value;
            save_object ["question_type"] = question_type;
            save_object ["question_oldid"] = question_oldid;

            if(question_type == 0) {
                var answers = $('#answers' + j).val();
                save_object ["answers"] = answers;
            } else {
                var answers = $('.date-answer' + j).val();
                save_object ["answers"] = answers;
            }
            save_data.push(save_object);
        }

        console.log(save_data);
        var answer_check = true;
        for(var k=0; k<save_data.length; k++) {
            if(save_data[k]["answers"] == "")
                answer_check = false;
        }

        if(answer_check == false) {
            new bs5.Toast({
                body: lang.confirmSelectAnswer,
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
            url: '/jobcards/save_answers',
            data: {
                'save_data': save_data,
            },
            success: function(result) {
                if(result['success'] == true) {
                    new bs5.Toast({
                        body: lang.answersSaveSuccess,
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
</script>
@endpush