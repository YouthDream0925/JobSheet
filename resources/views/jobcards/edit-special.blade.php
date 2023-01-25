@extends('layouts.index')

@push('css')
<link rel="stylesheet" href="{{ asset('dist/bundles/dataTables.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/bundles/flatpickr.min.css') }}">
<link rel="stylesheet" href="{{ asset('dist/bundles/daterangepicker.min.css') }}">
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('jobcards.index') }}" data-toggle="tooltip" data-bs-original-title="{{$job_card->title}}">{!! Str::of($job_card->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page" data-toggle="tooltip" data-bs-original-title="{{$job_card->description}}">{!! Str::of($job_card->description)->limit(20); !!}</li>
        </ol>
        <div>
            <a href="{{ route('exportExcel', $job_card->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_xls') }}</span></a>
            <a href="{{ route('exportPDF', $job_card->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_pdf') }}</span></a>
            <a class="btn btn-primary" href="{{ route('jobcards.index') }}">
                <i class="me-1 fa fa-mail-reply"></i><span class="d-lg-inline-flex d-none">{{ __('global.back') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
@php $flag = false; @endphp
@if(Auth::user()->email == $job_card->creator() || !empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin'))
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
            <h5 class="card-title fw-normal mb-0">{{ __('global.editWp') }}</h5>
            <div>
                <a class="btn btn-info" href="{{ route('jobcards.duplicate', $job_card->id) }}">
                    <i class="me-1 fa fa-copy "></i><span class="d-lg-inline-flex d-none">{{ __('global.duplicate') }}</span>
                </a>
                <a class="btn btn-primary" href="#settings" data-bs-toggle="offcanvas" role="button">
                    <i class="me-1 fa fa-cog"></i><span class="d-lg-inline-flex d-none">{{ __('global.set') }}</span>
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

            {!! Form::model($job_card, ['method' => 'PATCH','route' => ['jobcards.update', $job_card->id]]) !!}
            <div class="row g-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.title') }}:</strong>
                        @if($flag == true)
                            {!! Form::text('title', $job_card->title, array('placeholder' => 'Enter WP title','class' => 'form-control')) !!}
                        @else
                            {!! Form::text('title', $job_card->title, array('disabled' => true, 'placeholder' => 'Enter WP title','class' => 'form-control')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.status') }}:</strong>
                        @if($flag == true)
                            {!! Form::select('status', $status, $job_card->status, array('class' => 'form-control','single')) !!}
                        @elseif($assigned == true)
                            {!! Form::select('status', $status, $job_card->status, array('class' => 'form-control','single')) !!}
                        @else
                            {!! Form::select('status', $status, $job_card->status, array('disabled' => true, 'class' => 'form-control','single')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.assignUsers') }}:</strong>
                        @if($flag == true)
                            {!! Form::select('assign_users[]', $list_users, $assign_users, array('class' => 'form-control','multiple')) !!}
                        @else
                            {!! Form::select('assign_users[]', $list_users, $assign_users, array('disabled' => true, 'class' => 'form-control','multiple')) !!}
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
                    <a class="btn btn-default f-right" href="{{ route('jobcards.index') }}"> {{ __('global.cancel') }}</a>
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
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#question_all" role="tab" aria-selected="false">{{ __('global.question') }}</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#description_all" role="tab" aria-selected="false">{{ __('global.description') }}</a></li>
            <!-- <li class="nav-item ms-auto"><a data-bs-toggle="offcanvas" href="#create_task" role="button">New Task</a></li> -->
        </ul>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div id="full-container" class="offcanvas-body custom_scroll pt-0 pb-0">
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="field_all" role="tabpanel">
                <form method="post" action="{{ route('jobcards.comments.header', $job_card->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.coordinater') }}:</strong>
                                <input placeholder="Coordinater name" class="form-control" name="coordinater" type="text" value="{{ $job_card->coordinater }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.university') }}:</strong>
                                <input placeholder="University" class="form-control" name="university" type="text" value="{{ $job_card->university }}">
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
                @if(count($job_card->comments) != 0)
                    @foreach($job_card->comments as $key => $comment)
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
                                <button class="delete-comments btn btn-default f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.delete') }}
                                </button>
                            @else
                                @if($assigned == true)
                                <button class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button class="delete-comments btn btn-default f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.delete') }}
                                </button>
                                @else
                                <button disabled class="save-comments btn btn-primary f-right" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
                                    {{ __('global.save') }}
                                </button>
                                <button disabled class="delete-comments btn btn-default f-right mr-1" data-commentid="{{$i}}" data-oldid="{{ $comment->id }}">
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
                            <button class="delete-comments btn btn-default f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                        @else
                            @if($assigned == true)
                            <button class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            <button class="delete-comments btn btn-default f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                            @else
                            <button disabled class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            <button disabled class="delete-comments btn btn-default f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane fade" id="question_all" role="tabpanel">
                <div id="question_description_container" class="txt-center">
                    @if($job_card->question_description != null)
                    <textarea id="question_description">{{ $job_card->question_description->content }}</textarea>
                    <div class="btn-group-pos col-12">
                        <button class="save-question-description btn btn-primary f-right" data-oldid="{{$job_card->question_description->id}}">
                            {{ __('global.save') }}
                        </button>
                        <button class="delete-question-description btn btn-default f-right mr-1" data-oldid="{{$job_card->question_description->id}}">
                            {{ __('global.delete') }}
                        </button>
                    </div>
                    @else
                    <button id="add_question_description" class="btn btn-default">
                        <svg width="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">
                            <path opacity="0.6" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                            <path opacity="0.2" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                        </svg>
                        <span class="mx-3">{{ __('global.addDescription') }}</span>
                    </button>
                    @endif
                </div>
                <div id="all_questions">
                    @foreach($job_card->questions as $question_key => $question)
                        @if($question->type == 0)
                        <div class="row g-3 mb-1">
                            <div class="col-12">
                                <div class="form-group d-flex-center">
                                    <strong class="mr-1">{{ __('global.question')}}</strong>
                                    <input id="question{{$question_key}}" plasceholer="Enter new question" class="form-control" name="question" type="text" value="{{ $question->content }}">
                                </div>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-11">
                                <div class="form-group" id="answer_all{{$question_key}}">
                                    @foreach($question->answers as $answer_key => $answer)
                                    <div class="row">
                                        <div class="col-3">
                                            <button class="btn @if($answer_key == 0) answer btn-success @else delete-answer btn-danger @endif f-right mr-1" data-answerid="{{$question_key}}">
                                                <i class="me-1 fa @if($answer_key == 0) fa-plus @else fa-trash @endif"></i>
                                            </button>
                                        </div>
                                        <div class="col-9">
                                            <input plasceholer="Enter new question" class="form-control mb-1 answers{{$question_key}}" name="question" type="text" value="{{ $answer->answer }}">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="btn-group-pos col-12 order-style">
                                <strong class="mr-1">{{ __('global.order') }}</strong>
                                <input id="order{{$question_key}}" plasceholer="Enter order" class="form-control" name="order" type="number" value="{{$question->order}}" style="width: 100px; margin-right: 10px;">
                                <button class="delete-question btn btn-default f-right mr-1" data-questionid="{{$question_key}}" data-oldid="{{$question->id}}">
                                    {{ __('global.delete') }}
                                </button>
                                <button class="save-answers btn btn-primary f-right" data-questionid="{{$question_key}}" data-oldid="{{$question->id}}">
                                    {{ __('global.save') }}
                                </button>
                            </div>
                        </div>
                        @else
                        <div class="row g-3 mb-3">
                            <div class="col-12">
                                <div class="form-group d-flex-center">
                                    <strong class="mr-1">{{ __('global.question') }}</strong>
                                    <input id="date_question{{$question_key}}" plasceholer="Enter new question" class="form-control" name="question" type="text" value="{{ $question->content }}">
                                </div>
                            </div>
                            <div class="btn-group-pos col-12 order-style">
                                <strong class="mr-1">{{ __('global.order') }}</strong>
                                <input id="order{{$question_key}}" plasceholer="Enter order" class="form-control" name="order" type="number" value="{{$question->order}}" style="width: 100px; margin-right: 10px;">
                                <button class="delete-date-question btn btn-default f-right mr-1" data-questionid="{{$question_key}}" data-oldid="{{$question->id}}">
                                    {{ __('global.delete') }}
                                </button>
                                <button class="save-date-answers btn btn-primary f-right" data-questionid="{{$question_key}}" data-oldid="{{$question->id}}">
                                    {{ __('global.save') }}
                                </button>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="tab-pane fade" id="description_all" role="tabpanel">
                @if(count($job_card->descriptions) != 0)
                    @foreach($job_card->descriptions as $key => $description)
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
            <button id="new_question" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.selector') }}</span>
            </button>
            <button id="new_picker" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.calender') }}</span>
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
            <button id="new_question" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.selector') }}</span>
            </button>
            <button id="new_picker" class="btn btn-success">
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.calender') }}</span>
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
            <button id="new_question" class="btn btn-success" disabled>
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.selector') }}</span>
            </button>
            <button id="new_picker" class="btn btn-success" disabled>
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.calender') }}</span>
            </button>
            <button id="new_description" class="btn btn-success" disabled>
                <i class="me-1 fa fa-plus"></i> 
                <span class="d-lg-inline-flex d-none">{{ __('global.description') }}</span>
            </button>
            @endif
        @endif
    </div>
</div>

@csrf
@endsection

@push('script')
<script src="{{ asset('dist/bundles/flatpickr.bundle.js') }}"></script>
<script src="{{ asset('dist/bundles/daterangepicker.bundle.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    // basic picker
    flatpickr(".f-basic", {
        allowInput: true
    });

    var question_number = '{{count($job_card->questions)}}';
    var description_number = '{{ count($job_card->descriptions) }}';

    $('#new_question').on('click', function() {
        var tmp = '';
        tmp = '<div class="row g-3 mb-1">' + 
                '<div class="col-12">' + 
                    '<div class="form-group d-flex-center">' + 
                        '<strong class="mr-1">Question</strong>' +
                        '<input id="question' + question_number + '" plasceholer="Enter new question" class="form-control" name="question" type="text">' +
                    '</div>' + 
                '</div>' + 
                '<div class="col-1"></div>' + 
                '<div class="col-11">' + 
                    '<div class="form-group" id="answer_all' + question_number + '">' + 
                        '<div class="row">' +
                            '<div class="col-3">' + 
                                '<button class="answer btn btn-success f-right mr-1" data-answerid="' + question_number + '">' +
                                    '<i class="me-1 fa fa-plus"></i>' +
                                '</button>' +
                            '</div>' +
                            '<div class="col-9">' + 
                                '<input plasceholer="Enter new question" class="form-control mb-1 answers' + question_number + '" name="question" type="text">' +
                            '</div>' +
                        '</div>' +
                    '</div>' + 
                '</div>' + 
                '<div class="btn-group-pos col-12 order-style">' + 
                    '<strong class="mr-1">' + lang.order + '</strong>' +
                    '<input id="order' + question_number + '" plasceholer="Enter order" class="form-control" name="order" type="number" value="" style="width: 100px; margin-right: 10px;">' +
                    '<button class="delete-question btn btn-default f-right mr-1" data-questionid="' + question_number + '">' +
                        lang.delete +
                    '</button>' +
                    '<button class="save-answers btn btn-primary f-right" data-questionid="' + question_number + '">' +
                        lang.save +
                    '</button>' +
                '</div>'+
              '</div>';
        $('#all_questions').append(tmp);
        question_number++;
    });

    $('#all_questions').on('click', '.answer', function() {
        var answer_id = $(this).attr('data-answerid');

        var tmp = '';
        tmp = '<div class="row">' +
                '<div class="col-3">' + 
                    '<button class="delete-answer btn btn-danger f-right mr-1" data-answerid="' + question_number + '">' +
                        '<i class="me-1 fa fa-trash"></i>' +
                    '</button>' +
                '</div>' +
                '<div class="col-9">' + 
                    '<input plasceholer="Enter new question" class="form-control mb-1 answers' + answer_id + '" name="question" type="text">' +
                '</div>' +
            '</div>';
        $('#answer_all' + answer_id).append(tmp);
    });

    $('#all_questions').on('click', '.save-answers', function() {
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        var input_check = false;
        var question_value = $('#all_questions #question' + question_id).val();
        var order = $('#order' + question_id).val();
        var answers = [];
        $('.answers' + question_id).each(function (i, elem) {
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
                'order': order,
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

    $('#all_questions').on('click', '.delete-answer', function() {
        $(this).parent().parent().remove();
    });

    $('#all_questions').on('click', '.delete-question', function() {
        $(this).parent().parent().remove();
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        if(question_oldid) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '/jobcards/delete_questions',
                data: {
                    'delete_id': question_oldid,
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

    $('#new_picker').on('click', function() {
        var tmp = '';
        tmp = '<div class="row g-3 mb-3">' + 
                '<div class="col-12">' + 
                    '<div class="form-group d-flex-center">' +
                        '<strong class="mr-1">Question</strong>' +
                        '<input id="date_question' + question_number + '" plasceholer="Enter new question" class="form-control" name="question" type="text" value="">' +
                    '</div>' +
                '</div>' +
                '<div class="btn-group-pos col-12 order-style">' +
                    '<strong class="mr-1">' + lang.order + '</strong>' +
                    '<input id="order' + question_number + '" plasceholer="Enter order" class="form-control" name="order" type="number" value="" style="width: 100px; margin-right: 10px;">' +
                    '<button class="delete-date-question btn btn-default f-right mr-1" data-questionid="' + question_number + '">' +
                        lang.delete +
                    '</button>' +
                    '<button class="save-date-answers btn btn-primary f-right" data-questionid="' + question_number + '">' + 
                        lang.save +
                    '</button>' +
                '</div>' +
            '</div>';

        $('#all_questions').append(tmp);
        question_number++;

        flatpickr(".f-basic", {
            allowInput: true
        });
    });

    $('#all_questions').on('click', '.save-date-answers', function() {
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        var input_check = false;
        var question_value = $('#all_questions #date_question' + question_id).val();
        var order = $('#order' + question_id).val();
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
                'order': order,
                'content': question_value,
                'type': 1,
                'answers': answers,
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-date-answers[data-questionid="' + question_id + '"]').attr('data-oldid', result['new']);
                        $('.delete-date-question[data-questionid="' + question_id + '"]').attr('data-oldid', result['new']);
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

    $('#all_questions').on('click', '.delete-date-question', function() {
        $(this).parent().parent().remove();
        var question_id = $(this).attr('data-questionid');
        var question_oldid = $(this).attr('data-oldid');

        if(question_oldid) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'post',
                url: '/jobcards/delete_questions',
                data: {
                    'delete_id': question_oldid,
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

    var i = '{{ count($job_card->comments) }}';
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
                var freeTiny = document.querySelector('.tox .tox-notification');
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
                    var freeTiny = document.querySelector('.tox .tox-notification');
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
                  '<button class="delete-comments btn btn-default f-right mr-1" data-commentid="' + i + '">' + lang.delete + '</button>' +
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
            url: '/jobcards/save_comments',
            data: {
                'title': title,
                'phase': phase,
                'old_id': old_id,
                'type_id': '{{ $job_card->id }}',
                'type': 'job',
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
                    'type_id': '{{ $job_card->id }}',
                    'type': 'job'
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

    tinymce.init({
        readonly : readOnly,
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

    $('#question_description_container').on('click', '#add_question_description', function() {
        $('#question_description_container').html('');
        var tmp = '<textarea id="question_description"></textarea>' +
                    '<div class="btn-group-pos col-12">' +
                        '<button class="save-question-description btn btn-primary f-right">' +
                            lang.save + 
                        '</button>' +
                        '<button class="delete-question-description btn btn-default f-right mr-1">' +
                            lang.delete +
                        '</button>' +
                    '</div>';
        $('#question_description_container').html(tmp);

        tinymce.init({
            readonly : readOnly,
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
    });

    $('#question_description_container').on('click', '.save-question-description', function() {
        tinymce.triggerSave();
        var content = tinymce.get('question_description').getContent();
        var old_id = $(this).attr('data-oldid');

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
                'type_id': '{{ $job_card->id }}',
                'type': 'question',
                'content': content
            },
            success: function(result) {
                if(result['success'] == true) {
                    if(result['new'] != null) {
                        $('.save-question-description').attr('data-oldid', result['new']);
                        $('.delete-question-description').attr('data-oldid', result['new']);
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

    $('#question_description_container').on('click', '.delete-question-description', function() {
        var old_id = $(this).attr('data-oldid');
        $('#question_description_container').html('');

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

        var tmp = '<button id="add_question_description" class="btn btn-default">' +
                    '<svg width="20" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg" fill="currentColor">' +
                        '<path opacity="0.6" d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>' +
                        '<path opacity="0.2" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>' +
                    '</svg>' +
                    '<span class="mx-3">' + lang.addDescription + '</span>' +
                  '</button>';
        $('#question_description_container').html(tmp);
    });

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
                'type_id': '{{ $job_card->id }}',
                'type': 'job',
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

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script type="text/javascript">
    $('.btn-delete').click(function(event) {
        var task_id = $(this).attr('data-taskID');
        swal({
            title: lang.deleteConfirmTitle,
            text: lang.deleteConfirmText,
            icon: lang.deleteConfirmIcon,
            type: lang.deleteConfirmType,
            buttons: lang.deleteConfirmButton,
            confirmButtonColor: lang.deleteConfirmButtonColor,
            cancelButtonColor: lang.cancelButtonColor,
            confirmButtonText: lang.confirmButtonText
        }).then((willDelete) => {
            if (willDelete) {
                $.get('/tasks/destroy/' + task_id, function(data, status){
                    if(status == "success") {
                        location.reload();
                    }
                });
            }
        });
    });
</script>
@endpush