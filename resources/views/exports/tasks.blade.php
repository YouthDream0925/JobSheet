@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title">
    <div class="container-fluid breadcrumb-style pr-0">
        <ol class="breadcrumb bg-transparent li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item font-size-28">
              {{ __('global.exportTasks') }}
            </li>
        </ol>
    </div>
</div>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="row mb-4-custom">
                <form method="POST" action="{{ route('export.tasks.excute') }}" class="row g-3">
                    @csrf
                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex-center">
                        <div class="form-group">
                            {!! Form::select('task', $tasks, null, array('class' => 'form-control','single')) !!}
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex-center" style="justify-content: space-around;">
                        <div class="form-group">
                            <div class="form-check">
                                <label>{{ __('global.user_field') }}</label>
                                <input class="form-check-input" type="checkbox" name="field" value="true">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 d-flex-center">
                        <div class="form-group">
                            {!! Form::select('phase', $phase, null, array('class' => 'form-control','single')) !!}
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 txt-right">
                        <input type="hidden" name="export_type" value=""/>
                        <button id="docBtn" type="submit" class="btn btn-outline-danger mr-1 mb-2"><i class="fa fa-file-word-o"></i> <span class="d-lg-inline-flex d-none">Word</span></button>
                        <button id="excelBtn" type="submit" class="btn btn-outline-danger mr-1 mb-2"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Excel</span></button>
                        <button id="pdfBtn" type="submit" class="btn btn-outline-success mr-1 mb-2"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">PDF</span></button>
                    </div>
                </form>
            </div>

            <div class="row mb-4-custom">
                <form method="POST" action="{{ route('export.tasks.user') }}" class="row g-3">
                    @csrf
                    <div class="col-12 d-flex-center" style="justify-content: space-between;">
                        <div class="form-group">
                            {!! Form::select('user_id', $users, null, array('class' => 'form-control mb-1','single')) !!}
                        </div>
                        <div class="ml-1">
                            <input type="hidden" name="user_export_type" value=""/>
                            <button id="userDocBtn" type="submit" class="btn btn-outline-danger mr-1 mb-2"><i class="fa fa-file-word-o"></i> <span class="d-lg-inline-flex d-none">Word by Selected User</span></a>
                            <button id="userExcelBtn" type="submit" class="btn btn-outline-danger mr-1 mb-2"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Excel by Selected User</span></a>
                            <button id="userPdfBtn" type="submit" class="btn btn-outline-success mr-1 mb-2"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">PDF by Seleted User</span></a>
                        </div>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $('#docBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="export_type"]').val('doc');

        form.submit();
    });


    $('#excelBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="export_type"]').val('excel');

        form.submit();
    });

    $('#pdfBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="export_type"]').val('pdf');
        
        form.submit();
    });

    $('#userDocBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="user_export_type"]').val('doc');

        form.submit();
    });

    $('#userExcelBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="user_export_type"]').val('excel');

        form.submit();
    });

    $('#userPdfBtn').on('click', function() {
        var form =  $(this).closest("form");
        event.preventDefault();

        $('input[name="user_export_type"]').val('pdf');
        
        form.submit();
    });
</script>
@endpush