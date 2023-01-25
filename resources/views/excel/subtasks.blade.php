@extends('layouts.index')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
                <a class="wp-title-color txt-deco" href="{{ route('tasks.edit', $task_id) }}">{{ __('global.subTasks') }}</a>
            </li>
            <li class="breadcrumb-item active font-size-28" aria-current="page">{{ __('global.dataEntry') }}</li>
        </ol>
        <div>
            <a class="btn btn-primary" href="{{ route('tasks.edit', $task_id) }}">
                <i class="me-1 fa fa-mail-reply"></i>
                <span class="d-lg-inline-flex d-none">{{ __('global.back') }}</span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('content')
    <div class="card bg-light mt-3">
        <div class="card-header">
            {{ __('global.excelHeader') }}
        </div>
        <div class="card-body">
            <form action="{{ route('subtasks.import', $task_id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button id="importBtn" class="btn btn-success">{{ __('global.import') }}</button>
            </form>
  
            <table class="table table-bordered mt-3">
                <tr>
                    <th colspan="7">
                        {{ __('global.wpList') }}
                        <a class="btn btn-warning float-end" href="{{ route('subtasks.export', $task_id) }}">{{ __('global.export') }}</a>
                    </th>
                </tr>
                <tr>
                    <th>{{ __('global.no') }}</th>
                    <th>{{ __('global.task') }}</th>
                    <th>{{ __('global.title') }}</th>
                    <th>{{ __('global.creator') }}</th>
                    <th>{{ __('global.status') }}</th>
                    <th>{{ __('global.coordinater') }}</th>
                    <th>{{ __('global.university') }}</th>
                </tr>
                @foreach($subtasks as $sub_task)
                <tr>
                    <td>{{ $sub_task->id }}</td>
                    <td>{{ $task_id }}</td>
                    <td>{{ $sub_task->title }}</td>
                    <td>{{ $sub_task->creator() }}</td>
                    <td>{{ $status[$sub_task->status] }}</td>
                    @if($sub_task->coordinater)
                    <td>{{ $sub_task->coordinater }}</td>
                    @else
                    <td>NONE</td>
                    @endif
                    @if($sub_task->university)
                    <td>{{ $sub_task->university }}</td>
                    @else
                    <td>NONE</td>
                    @endif
                </tr>
                @endforeach
            </table>
            @include('layouts.pagination.index', ['paginator' => $subtasks])
        </div>
    </div>
@endsection

@push('script')
<script>
    var file_name = "";
    var extension = "";

    $('input[name="file"]').on("change", function () {
        // the files is a new property from the new File API, if if it is not supported assign an empty array as the value of files
        var files = !! this.files ? this.files : [];

        //if there are no files and FileReader is not supported return
        if (!files.length || !window.FileReader) return;

        file_name = files[0].name;
        extension = file_name.split('.').pop();

        if(extension !== 'xlsx')
        {
            new bs5.Toast({
                body: lang.confirmSelectFile,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
        }
    });
    
    $('#importBtn').click(function(event){
        var form =  $(this).closest("form");
        event.preventDefault();

        if(file_name && extension === 'xlsx') {
            form.submit();
        }            
        else {
            new bs5.Toast({
                body: lang.confirmSelectFile,
                className: 'border-0 bg-danger text-white',
                btnCloseWhite: true,
            }).show();
        }
            
    });
</script>
@endpush