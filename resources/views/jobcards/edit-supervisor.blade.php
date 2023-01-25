@extends('layouts.index')

@push('css')
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
    <div class="card mb-4">
        <form method="GET" action="{{ route('jobcards.edit', $job_card->id) }}">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-1">
                        {!! Form::select('assigned', $search_users, request()->input('assigned'), array('id' => 'assigned', 'class' => 'form-control','single')) !!}
                    </div>
                    <div class="col-md-3 mb-1">
                        {!! Form::select('status', $status, request()->input('status'), array('id' => 'status', 'class' => 'form-control','single')) !!}
                    </div>
                    <div class="col-md-3 mb-1">
                        {!! Form::text('title', request()->input('title'), array('id' => 'title', 'placeholder' => __('global.title'),'class' => 'form-control')) !!}
                    </div>
                    <div class="col-md-3 mb-1">
                        {!! Form::select('phase', $phase, request()->input('phase'), array('id' => 'phase', 'class' => 'form-control','single')) !!}
                    </div>
                </div>
            </div>
            <div class="card-footer txt-right">
                <button type="submit" class="btn btn-dark ms-auto">{{ __('global.search') }}</button>
                <button id="resetBtn" type="submit" class="btn btn-dark ms-auto">{{ __('global.reset') }}</button>
            </div>
        </form>
    </div>

    @if(count($tasks) == 0)
    <div class="card mb-2">
        <div class="card-body txt-center">
            <p class="mb-0">{{ __('global.noTask') }}</p>
        </div>
    </div>
    @else
        @foreach ($tasks as $key => $task)
            @if(request()->input('phase') != null)
                <div class="card mb-2">
                    <div class="card-body d-flex-center">
                        <div>
                            <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{$job_card->title}} - {{$task->title}}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($task->title)->limit(20); !!}</span>
                                @if($task->status == 0)
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 1)
                                <span class="badge bg-danger font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 2)
                                <span class="badge bg-success font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 3)
                                <span class="badge bg-secondary font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @else
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @endif
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{$task->description}}">{!! Str::of($task->description)->limit(40); !!}</h6>
                            <strong>Created by: </strong>{{ $task->name() }}
                        </div>
                        @php $task_assign_check = false @endphp
                        @foreach($task->assign() as $key => $value)
                            @if($value == Auth::user()->email)
                                @php $task_assign_check = true @endphp
                            @endif
                        @endforeach
                        <div class="mt-auto ml-auto">
                            <a href="{{ route('exportPDF.task', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export') }}</span></a>
                            <a data-bs-toggle="collapse" href="#task_comment{{$task->id}}" role="button" aria-expanded="false" class="btn btn-outline-secondary"><i class="fa fa-eye"></i> <span class="d-lg-inline-flex d-none">{{ __('global.view') }}</span></a>
                            @if($task_assign_check == true)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(count($task->comments) != 0)
                    @foreach($task->comments as $key => $comment)
                        @if(request()->input('phase') == $comment->phase)
                        <div class="collapse card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif" id="task_comment{{$task->id}}">
                            <div class="card-body">
                                {!! $comment->content !!}
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="collapse card mb-2">
                        <div class="card-body d-flex-center">
                            {{ __('global.noData') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="card mb-2">
                    <div class="card-body d-flex-center">
                        <div>
                            <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{$job_card->title}} - {{$task->title}}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($task->title)->limit(20); !!}</span>
                                @if($task->status == 0)
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 1)
                                <span class="badge bg-danger font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 2)
                                <span class="badge bg-success font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @elseif($task->status == 3)
                                <span class="badge bg-secondary font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @else
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$task->status] }}</span>
                                @endif
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{$task->description}}">{!! Str::of($task->description)->limit(40); !!}</h6>
                            <strong>Created by: </strong>{{ $task->name() }}
                        </div>
                        @php $task_assign_check = false @endphp
                        @foreach($task->assign() as $key => $value)
                            @if($value == Auth::user()->email)
                                @php $task_assign_check = true @endphp
                            @endif
                        @endforeach
                        <div class="mt-auto ml-auto">
                            <a href="{{ route('exportPDF.task', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export') }}</span></a>
                            <a data-bs-toggle="collapse" href="#task_comment{{$task->id}}" role="button" aria-expanded="false" class="btn btn-outline-secondary"><i class="fa fa-eye"></i> <span class="d-lg-inline-flex d-none">{{ __('global.view') }}</span></a>
                            @if($task_assign_check == true)
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                            @endif
                        </div>
                    </div>
                </div>

                @if(count($task->comments) != 0)
                    @foreach($task->comments as $key => $comment)
                    <div class="collapse card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif" id="task_comment{{$task->id}}">
                        <div class="card-body">
                            {!! $comment->content !!}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="collapse card mb-2">
                        <div class="card-body d-flex-center">
                            {{ __('global.noData') }}
                        </div>
                    </div>
                @endif
            @endif            
        @endforeach
        @foreach($subTasks as $key => $sub_task)
            @if(request()->input('phase') != null)
                <div class="card mb-2">
                    <div class="card-body d-flex-center">
                        <div>
                            <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{$job_card->title}} - {{$sub_task->task->title}} - {{$sub_task->title}}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($sub_task->task->title)->limit(20); !!}</span> - <span class="sub-title-color">{!! Str::of($sub_task->title)->limit(20); !!}</span>
                                @if($sub_task->status == 0)
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 1)
                                <span class="badge bg-danger font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 2)
                                <span class="badge bg-success font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 3)
                                <span class="badge bg-secondary font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @else
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @endif
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{$sub_task->description}}">{!! Str::of($sub_task->description)->limit(40); !!}</h6>
                            <strong>Created by: </strong>{{ $sub_task->name() }}
                        </div>
                        @php $subTask_assign_check = false @endphp
                        @foreach($sub_task->assign() as $key => $value)
                            @if($value == Auth::user()->email)
                                @php $subTask_assign_check = true @endphp
                            @endif
                        @endforeach
                        <div class="mt-auto ml-auto">
                            <a href="{{ route('exportPDF.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export') }}</span></a>
                            <a data-bs-toggle="collapse" href="#subTask_comment{{$sub_task->id}}" role="button" aria-expanded="false" class="btn btn-outline-secondary"><i class="fa fa-eye"></i> <span class="d-lg-inline-flex d-none">{{ __('global.view') }}</span></a>
                            @if($subTask_assign_check == true)
                            <a href="{{ route('subtasks.edit', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(count($sub_task->comments) != 0)
                    @foreach($sub_task->comments as $key => $comment)
                        @if(request()->input('phase') == $comment->phase)
                        <div class="collapse card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif" id="subTask_comment{{$sub_task->id}}">
                            <div class="card-body">
                                {!! $comment->content !!}
                            </div>
                        </div>
                        @endif
                    @endforeach
                @else
                    <div class="collapse card mb-2 comment-style">
                        <div class="card-body d-flex-center">
                            {{ __('global.noData') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="card mb-2">
                    <div class="card-body d-flex-center">
                        <div>
                            <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{$job_card->title}} - {{$sub_task->task->title}} - {{$sub_task->title}}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($sub_task->task->title)->limit(20); !!}</span> - <span class="sub-title-color">{!! Str::of($sub_task->title)->limit(20); !!}</span>
                                @if($sub_task->status == 0)
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 1)
                                <span class="badge bg-danger font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 2)
                                <span class="badge bg-success font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @elseif($sub_task->status == 3)
                                <span class="badge bg-secondary font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @else
                                <span class="badge bg-warning font-size-11 status-pos">{{ $status[$sub_task->status] }}</span>
                                @endif
                            </h5>
                            <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{$sub_task->description}}">{!! Str::of($sub_task->description)->limit(40); !!}</h6>
                            <strong>Created by: </strong>{{ $sub_task->name() }}
                        </div>
                        @php $subTask_assign_check = false @endphp
                        @foreach($sub_task->assign() as $key => $value)
                            @if($value == Auth::user()->email)
                                @php $subTask_assign_check = true @endphp
                            @endif
                        @endforeach
                        <div class="mt-auto ml-auto">
                            <a href="{{ route('exportPDF.subTask', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">{{ __('global.export') }}</span></a>
                            <a data-bs-toggle="collapse" href="#subTask_comment{{$sub_task->id}}" role="button" aria-expanded="false" class="btn btn-outline-secondary"><i class="fa fa-eye"></i> <span class="d-lg-inline-flex d-none">{{ __('global.view') }}</span></a>
                            @if($subTask_assign_check == true)
                            <a href="{{ route('subtasks.edit', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                            @endif
                        </div>
                    </div>
                </div>
                @if(count($sub_task->comments) != 0)
                    @foreach($sub_task->comments as $key => $comment)
                    <div class="collapse card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif" id="subTask_comment{{$sub_task->id}}">
                        <div class="card-body">
                            {!! $comment->content !!}
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="collapse card mb-2">
                        <div class="card-body d-flex-center">
                            {{ __('global.noData') }}
                        </div>
                    </div>
                @endif
            @endif
        @endforeach
    @endif
</div>

@csrf
@endsection

@push('script')
<script type="text/javascript">
    $('#resetBtn').click(function(event){
        var form =  $(this).closest("form");
        event.preventDefault();

        $('#assigned').val(null);
        $('#status').val(null);
        $('#title').val(null);
        $('#phase').val(null);

        form.submit();
    });
</script>
@endpush