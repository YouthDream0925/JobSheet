@extends('layouts.index')

@push('css')
<link rel="stylesheet" href="{{ asset('dist/bundles/dataTables.min.css') }}">
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('jobcards.index') }}" data-toggle="tooltip" data-bs-original-title="{{ $task->jobcard->title }}">{!! Str::of($task->jobcard->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item font-size-28">
                <a class="task-title-color txt-deco" href="{{ route('jobcards.edit', $task->job_id) }}" data-toggle="tooltip" data-bs-original-title="{{ $task->title }}">{!! Str::of($task->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page">{{ __('global.edit') }}</li>
        </ol>
        <div>
            <a href="{{ route('exportDoc.task', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_doc') }}</span></a>
            <a href="{{ route('exportExcel.task', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_xls') }}</span></a>
            <a href="{{ route('exportPDF.task', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export_pdf') }}</span></a>
            <a class="btn btn-primary" href="{{ route('jobcards.edit', $task->job_id) }}">
                <i class="me-1 fa fa-mail-reply"></i>
                {{ __('global.back') }}
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
@php $flag = false; @endphp
@if(Auth::user()->id == $task->create_user || !empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin'))
    @php $flag = true; @endphp
@else
    @php $flag = false; @endphp
@endif

@php $assigned = false; @endphp
@foreach($task->Assign() as $key => $assigned_user)
    @if($assigned_user == Auth::user()->email)
        @php $assigned = true; @endphp
    @else
        @php $assigned = false; @endphp
    @endif
@endforeach

<div class="col-12">
    <div class="card">
        <div class="card-header pb-0">
            <h5 class="card-title fw-normal mb-0">{{ __('global.editTask') }}</h5>
            <div>
                <a class="btn btn-info" href="{{ route('tasks.duplicate', $task->id) }}">
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

            {!! Form::model($task, ['method' => 'post','route' => ['tasks.update', $task->id]]) !!}
            <div class="row g-3">
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.title') }}:</strong>
                        @if($flag == true)
                            {!! Form::text('title', $task->title, array('placeholder' => 'Enter task title','class' => 'form-control')) !!}
                        @else
                            {!! Form::text('title', $task->title, array('disabled' => 'true', 'placeholder' => 'Enter task title','class' => 'form-control')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="form-group">
                        <strong>{{ __('global.status') }}:</strong>
                        @if($flag == true)
                        {!! Form::select('status', $status, $task->status, array('class' => 'form-control','single')) !!}
                        @elseif($assigned == true)
                        {!! Form::select('status', $status, $task->status, array('class' => 'form-control','single')) !!}
                        @else
                        {!! Form::select('status', $status, $task->status, array('disabled' => true, 'class' => 'form-control','single')) !!}
                        @endif
                    </div>
                </div>

                <div class="col-lg-3 col-md-3 col-sm-6">
                    <strong>{{ __('global.order') }}:</strong>
                    {!! Form::text('order', null, array('placeholder' => __('global.enterOrderNum'),'class' => 'form-control')) !!}
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="form-group">
                        <strong>{{ __('global.assignUsers') }}:</strong>
                        <div class="@if($flag == true) enable_div @else disable-div @endif">
                            @foreach($users as $key => $user)
                                @if(in_array($key, $assign_users))
                                <div class="form-check">
                                    <label>{{ $user }}</label>
                                    <input class="form-check-input" name="assign_users[]" type="checkbox" value="{{ $key }}" checked>
                                </div>
                                @else
                                <div class="form-check">
                                    <label>{{ $user }}</label>
                                    <input class="form-check-input" name="assign_users[]" type="checkbox" value="{{ $key }}">
                                </div>
                                @endif
                            @endforeach
                        </div>
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
                    <a class="btn btn-default f-right" href="{{ route('jobcards.edit', $task->job_id) }}"> {{ __('global.cancel') }}</a>
                </div>
            </div>
            {!! Form::close() !!}

            <hr>

            @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin'))
            <div class="row g-3 mt-lg-3" style="">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <a class="btn btn-success f-right" href="{{ route('subtasks.create', $task->id) }}">
                        <i class="me-1 fa fa-plus"></i> {{ __('global.newSubTask') }}
                    </a>
                    <a class="btn btn-default f-right mr-1" href="{{ route('subtasks.excel', $task->id) }}">
                        <i class="me-1 fa fa-database"></i> {{ __('global.dataEntry') }}
                    </a>
                </div>
            </div>
            @endif

            @if(count($sub_tasks) == 0)
            <p class="txt-center mb-0">{{ __('global.noSubTask') }}</p>
            @else
            <table class="table table-custom mb-0 mt-lg-4 mydata-table">
                <thead>
                    <tr>
                        <th>{{ __('global.no') }}</th>
                        <th>{{ __('global.title') }}</th>
                        <th>{{ __('global.status') }}</th>
                        <th>{{ __('global.creator') }}</th>
                        <th>{{ __('global.assignUsers') }}</th>
                        <th class="txt-right">{{ __('global.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sub_tasks as $key => $sub_task)
                    <tr>
                        <td id="order_id{{$sub_task->id}}">{{ $sub_task->order }}</td>
                        <td class="sub-title-color" style="word-wrap: break-word !important; word-break: break-all !important; white-space: normal;">{!! $sub_task->title !!}</td>
                        <td>
                            @if($sub_task->status == 0)
                            <span class="badge bg-warning font-size-11">{{ $status[$sub_task->status] }}</span>
                            @elseif($sub_task->status == 1)
                            <span class="badge bg-danger font-size-11">{{ $status[$sub_task->status] }}</span>
                            @elseif($sub_task->status == 2)
                            <span class="badge bg-success font-size-11">{{ $status[$sub_task->status] }}</span>
                            @elseif($sub_task->status == 3)
                            <span class="badge bg-secondary font-size-11">{{ $status[$sub_task->status] }}</span>
                            @else
                            <span class="badge bg-warning font-size-11">{{ $status[$sub_task->status] }}</span>
                            @endif
                        </td>
                        <td>{!! Str::of($sub_task->creator())->limit(30); !!}</td>
                        <td>
                        {!! Form::select('', $sub_task->assign(), $sub_task->assign(), array('class' => 'form-control','single')) !!}
                        </td>
                        <td>
                            <a href="#" class="dropdown-toggle after-none text-primary f-right" data-bs-toggle="dropdown" aria-expanded="false" title="More Action">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-opacity="0.4" d="M2 10H5C5.26522 10 5.51957 10.1054 5.70711 10.2929C5.89464 10.4804 6 10.7348 6 11V14C6 14.2652 5.89464 14.5196 5.70711 14.7071C5.51957 14.8946 5.26522 15 5 15H2C1.73478 15 1.48043 14.8946 1.29289 14.7071C1.10536 14.5196 1 14.2652 1 14V11C1 10.7348 1.10536 10.4804 1.29289 10.2929C1.48043 10.1054 1.73478 10 2 10ZM11 1H14C14.2652 1 14.5196 1.10536 14.7071 1.29289C14.8946 1.48043 15 1.73478 15 2V5C15 5.26522 14.8946 5.51957 14.7071 5.70711C14.5196 5.89464 14.2652 6 14 6H11C10.7348 6 10.4804 5.89464 10.2929 5.70711C10.1054 5.51957 10 5.26522 10 5V2C10 1.73478 10.1054 1.48043 10.2929 1.29289C10.4804 1.10536 10.7348 1 11 1ZM11 10C10.7348 10 10.4804 10.1054 10.2929 10.2929C10.1054 10.4804 10 10.7348 10 11V14C10 14.2652 10.1054 14.5196 10.2929 14.7071C10.4804 14.8946 10.7348 15 11 15H14C14.2652 15 14.5196 14.8946 14.7071 14.7071C14.8946 14.5196 15 14.2652 15 14V11C15 10.7348 14.8946 10.4804 14.7071 10.2929C14.5196 10.1054 14.2652 10 14 10H11ZM11 0C10.4696 0 9.96086 0.210714 9.58579 0.585786C9.21071 0.960859 9 1.46957 9 2V5C9 5.53043 9.21071 6.03914 9.58579 6.41421C9.96086 6.78929 10.4696 7 11 7H14C14.5304 7 15.0391 6.78929 15.4142 6.41421C15.7893 6.03914 16 5.53043 16 5V2C16 1.46957 15.7893 0.960859 15.4142 0.585786C15.0391 0.210714 14.5304 0 14 0L11 0ZM2 9C1.46957 9 0.960859 9.21071 0.585786 9.58579C0.210714 9.96086 0 10.4696 0 11L0 14C0 14.5304 0.210714 15.0391 0.585786 15.4142C0.960859 15.7893 1.46957 16 2 16H5C5.53043 16 6.03914 15.7893 6.41421 15.4142C6.78929 15.0391 7 14.5304 7 14V11C7 10.4696 6.78929 9.96086 6.41421 9.58579C6.03914 9.21071 5.53043 9 5 9H2ZM9 11C9 10.4696 9.21071 9.96086 9.58579 9.58579C9.96086 9.21071 10.4696 9 11 9H14C14.5304 9 15.0391 9.21071 15.4142 9.58579C15.7893 9.96086 16 10.4696 16 11V14C16 14.5304 15.7893 15.0391 15.4142 15.4142C15.0391 15.7893 14.5304 16 14 16H11C10.4696 16 9.96086 15.7893 9.58579 15.4142C9.21071 15.0391 9 14.5304 9 14V11Z"></path>
                                    <path fill-opacity="0.9" d="M0.585786 0.585786C0.210714 0.960859 0 1.46957 0 2V5C0 5.53043 0.210714 6.03914 0.585786 6.41421C0.960859 6.78929 1.46957 7 2 7H5C5.53043 7 6.03914 6.78929 6.41421 6.41421C6.78929 6.03914 7 5.53043 7 5V2C7 1.46957 6.78929 0.960859 6.41421 0.585786C6.03914 0.210714 5.53043 0 5 0H2C1.46957 0 0.960859 0.210714 0.585786 0.585786Z"></path>
                                </svg>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 p-2">
                                <a href="{{ route('subtasks.edit',$sub_task->id) }}" class="dropdown-item"><i class="me-3 fa fa-pencil"></i>{{ __('global.edit') }}</a>
                                <button class="dropdown-item btn-order-change" data-taskKey="{{ $sub_task->id }}" data-taskOrder="{{ $sub_task->order }}" data-taskTitle="{{ $sub_task->title }}"><i class="me-3 fa fa-sort"></i>{{ __('global.order') }}</button>
                                <a href="{{ route('subtasks.duplicate', $sub_task->id) }}" class="dropdown-item"><i class="me-3 fa fa-copy"></i>{{ __('global.duplicate') }}</a>
                                @if(!empty(Auth::user()->getRoleNames()) && Auth::user()->hasExactRoles('SuperAdmin') || Auth::user()->hasExactRoles('Admin'))
                                <div class="dropdown-divider"></div>
                                <button class="dropdown-item btn-delete" data-taskID="{{ $sub_task->id }}"><i class="me-3 fa fa-trash"></i>{{ __('global.delete') }}</button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="paination-container-custom">
                <form method="GET" action="{{ route('tasks.edit', $task->id) }}">
                    @if(request()->input('per_page') != null)
                        {!! Form::select('per_page', $pages, request()->input('per_page'), array('id' => 'per_page', 'class' => 'form-control', 'single', 'onChange' => 'ChangePageNumber()')) !!}
                    @else
                        {!! Form::select('per_page', $pages, $per_page, array('id' => 'per_page', 'class' => 'form-control', 'single', 'onChange' => 'ChangePageNumber()')) !!}
                    @endif
                    <button id="changeBtn" type="submit" class="d-none">{{ __('global.submit') }}</button>
                </form>
                @include('layouts.pagination.index', ['paginator' => $sub_tasks])
            </div>
            @endif
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
                <form method="post" action="{{ route('tasks.comments.header', $task->id) }}">
                    @csrf
                    <div class="row g-3">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.coordinater') }}:</strong>
                                <input placeholder="Coordinater name" class="form-control" name="coordinater" type="text" value="{{ $task->coordinater }}">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group d-flex-center">
                                <strong class="mr-1">{{ __('global.university') }}:</strong>
                                <input placeholder="University" class="form-control" name="university" type="text" value="{{ $task->university }}">
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
                            <button class="delete-comments btn btn-primary f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                            @else
                            <button disabled class="save-comments btn btn-primary f-right" data-commentid="0">
                                {{ __('global.save') }}
                            </button>
                            <button disabled class="delete-comments btn btn-primary f-right mr-1" data-commentid="0">
                                {{ __('global.delete') }}
                            </button>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
            </div>
            <div class="tab-pane fade" id="description_all" role="tabpanel">
                @if(count($task->descriptions) != 0)
                    @foreach($task->descriptions as $key => $description)
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

<button id="modal_btn" type="button" class="btn btn-secondary d-none" data-bs-toggle="modal" data-bs-target="#exampleModalLive"></button>
<!--[ Modal ]-->
<div class="modal fade" id="exampleModalLive" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal_title">{{ __('global.title') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex-center" id="modal_body">
                <p class="mb-0 mr-1">{{ __('global.order') }}</p>
                <input id="current_order_number" placeholder="Enter Order number" class="form-control" name="order" type="text" value="">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                <button id="order_change_save" type="button" class="btn btn-primary">{{ __('global.save') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script src="{{ asset('dist/bundles/dataTables.bundle.js') }}"></script>
<script src="https://cdn.tiny.cloud/1/hfjeq7g8pi85dj8m7yuph7vcfrxud3livtrb5nrkb83678t3/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script src="{{asset('dist/vendor/bs5-toast/bs5-toast.js')}}"></script>
<script>
    function ChangePageNumber()
    {
      $('#changeBtn').click();
    }

    var i = '{{ count($comments) }}';
    var description_number = '{{ count($task->descriptions) }}';
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
            url: '/tasks/save_comments',
            data: {
                'title': title,
                'phase': phase,
                'old_id': old_id,
                'type_id': '{{ $task->id }}',
                'type': 'task',
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
                    'type_id': '{{ $task->id }}',
                    'type': 'task'
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
                'type_id': '{{ $task->id }}',
                'type': 'task',
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
        var subTask_id = $(this).attr('data-taskID');
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
                $.get('/subtasks/destroy/' + subTask_id, function(data, status){
                    if(status == "success") {
                        location.reload();
                    }
                });
            }
        });
    });

    $('.mydata-table').addClass('nowrap').dataTable({
      responsive: true,
      searching: false,
      paging: false,
      ordering: false,
      info: false,
    });

    let title = null, current_order = null, new_order = null, obj_key = null;

    $('.btn-order-change').css('cursor','pointer');
    $('#order_change_save').css('cursor','pointer');
    $(document).on('click', '.btn-order-change',  function(event) {
        event.preventDefault();
        $('#modal_btn').trigger('click');
        title = $(this).attr('data-taskTitle');
        current_order = $(this).attr('data-taskOrder');
        obj_key = $(this).attr('data-taskKey');

        $('#modal_title').html(title);
        $('#current_order_number').val(current_order);
    });

    $('#order_change_save').on('click', function() {
        new_order = $('#current_order_number').val();

        if(title == null || current_order == null || obj_key == null) {
            new bs5.Toast({
                body: lang.unexpectedError,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
            return;
        } else {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: 'POST',
                url: "{{ route('order.change') }}",
                data: {
                    'type' : 'sub_task',
                    'key' : obj_key,
                    'new_order' : new_order
                },
                success: function(res) {
                    if(res['success'] == true) {
                        $('#order_id' + obj_key).html(new_order);
                        new bs5.Toast({
                            body: res['msg'],
                            className: 'border-0 bg-success text-white',
                            btnCloseWhite: true,
                        }).show();
                    } else {
                        new bs5.Toast({
                            body: res['msg'],
                            className: 'border-0 bg-danger text-white',
                            btnCloseWhite: true,
                        }).show();
                    }
                },
                error: function() {
                    new bs5.Toast({
                        body: lang.unexpectedError,
                        className: 'border-0 bg-danger text-white',
                        btnCloseWhite: true,
                    }).show();
                }
            });
        }
    });
</script>
@endpush