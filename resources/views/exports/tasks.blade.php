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
            <!-- <div class="row g-3"> -->
                <form method="POST" action="{{ route('export.tasks.excute') }}" class="row g-3">
                    @csrf
                    <div class="col-lg-3 col-md-3 col-sm-12">
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

                    <div class="col-lg-3 col-md-3 col-sm-12">
                        <div class="form-group">
                            {!! Form::select('phase', $phase, null, array('class' => 'form-control','single')) !!}
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-12 txt-right">
                        <input type="hidden" name="export_type" value=""/>
                        <button id="excelBtn" type="submit" class="btn btn-outline-danger"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Excel</span></button>
                        <button id="pdfBtn" type="submit" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">PDF</span></button>
                    </div>
                </form>
            <!-- </div> -->

            <hr>

            <div class="row g-3">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-danger"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Export all of User of Task</span></a>
                    <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">Export all of User of Task</span></a>
                </div>

                <div class="col-lg-7 col-md-7 col-sm-12 d-flex-center">
                    <div class="form-group">
                        {!! Form::select('status', $users, null, array('class' => 'form-control','single')) !!}
                    </div>
                    <div class="ml-1">
                        <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-danger"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Export User</span></a>
                        <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">Export User</span></a>
                    </div>
                </div>
            </div>

            <hr>

            <div class="row g-3">
                <div class="col-lg-5 col-md-5 col-sm-12">
                    <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-danger"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Export all semester</span></a>
                    <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">Export all semester</span></a>
                </div>

                <div class="col-lg-7 col-md-7 col-sm-12 d-flex-center">
                    <div class="form-group">
                        {!! Form::select('status', $phase, null, array('class' => 'form-control','single')) !!}
                    </div>
                    <div class="ml-1">
                        <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-danger"><i class="fa fa-file-excel-o"></i> <span class="d-lg-inline-flex d-none">Export all semester</span></a>
                        <a href="http://jobsheet.com/create-excel-file/486" class="btn btn-outline-success"><i class="fa fa-file-pdf-o"></i> <span class="d-lg-inline-flex d-none">Export all semester</span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
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
</script>
@endpush