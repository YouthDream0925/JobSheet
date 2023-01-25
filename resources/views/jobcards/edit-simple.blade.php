@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('jobcards.index') }}" data-toggle="tooltip" data-bs-original-title="{{ $job_card->title }}">{!! Str::of($job_card->title)->limit(20); !!}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page" data-toggle="tooltip" data-bs-original-title="{{ $job_card->description }}">{!! Str::of($job_card->description)->limit(20); !!}</li>
        </ol>
        <div>
            <a class="btn btn-primary" href="{{ route('jobcards.index')}}"><i class="me-1 fa fa-mail-reply"></i><span class="d-lg-inline-flex d-none">{{ __('global.back') }}</span></a>
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
    @if(count($job_card->tasks) == 0)
    <div class="card mb-2">
        <div class="card-body txt-center">
            <p class="mb-0">{{ __('global.noTask') }}</p>
        </div>
    </div>
    @else
        @php $assigned_task_subTask_counter = 0; @endphp
        @foreach ($job_card->tasks as $key => $task)
            @php $task_assign_check = false @endphp
            @foreach($task->assign() as $key => $value)
                @if($value == Auth::user()->email)
                    @php $task_assign_check = true @endphp
                @endif
            @endforeach
            @if($task_assign_check == true)
                @php $assigned_task_subTask_counter++; @endphp
                <div class="card mb-2">
                    <div class="card-body d-flex-center">
                        <div>
                            <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{ $job_card->title }} - {{ $task->title }}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($task->title)->limit(20); !!}</span>
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
                            <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{ $task->description }}">{!! Str::of($task->description)->limit(40); !!}</h6>
                            <strong>Created by: </strong>{{ $task->name() }}
                        </div>
                        <div class="mt-auto ml-auto">
                            <a href="{{ route('tasks.edit', $task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                        </div>
                    </div>
                </div>
            @endif
            @foreach($task->subtasks as $key => $sub_task)
                @php $subTask_assign_check = false @endphp
                @foreach($sub_task->assign() as $key => $value)
                    @if($value == Auth::user()->email)
                        @php $subTask_assign_check = true @endphp
                    @endif
                @endforeach
                @if($subTask_assign_check == true)
                    @php $assigned_task_subTask_counter++; @endphp
                    <div class="card mb-2">
                        <div class="card-body d-flex-center">
                            <div>
                                <h5 class="card-title" data-toggle="tooltip" data-bs-original-title="{{ $job_card->title }} - {{ $task->title }} - {{ $sub_task->title }}"><span class="wp-title-color">{!! Str::of($job_card->title)->limit(20); !!}</span> - <span class="task-title-color">{!! Str::of($task->title)->limit(20); !!}</span> - <span class="sub-title-color">{!! Str::of($sub_task->title)->limit(20); !!}</span>
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
                                <h6 class="card-subtitle mb-2 text-muted" data-toggle="tooltip" data-bs-original-title="{{ $sub_task->description }}">{!! Str::of($sub_task->description)->limit(40); !!}</h6>
                                <strong>Created by: </strong>{{ $sub_task->name() }}
                            </div>

                            <div class="mt-auto ml-auto">
                                <a href="{{ route('subtasks.edit', $sub_task->id) }}" class="btn btn-outline-secondary"><i class="fa fa-pencil"></i> <span class="d-lg-inline-flex d-none">{{ __('global.modify') }}</span></a>
                            </div>
                            
                        </div>
                    </div>
                @endif
            @endforeach
        @endforeach
        @if($assigned_task_subTask_counter == 0)
        <div class="card mb-2">
            <div class="card-body txt-center">
                <p class="mb-0">{{ __('global.noTask') }}</p>
            </div>
        </div>
        @endif
    @endif
</div>
@endsection

@push('script')
@endpush