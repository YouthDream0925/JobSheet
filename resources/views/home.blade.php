@extends('layouts.index')

@push('css')
@endpush

@section('breadcrumb')
<div class="page-title pb-0">
    <div class="container-fluid">
        <ol class="breadcrumb bg-transparent w-100 li_animate mb-3 mb-md-1">
            <li class="breadcrumb-item active font-size-28" aria-current="page">
                {{ __('global.dashboard') }}
            </li>
        </ol>
    </div>
    <div class="container-fluid">
        <p style="margin-top: 1rem !important;">{!! __('global.descriptionTxt') !!}</p>
    </div>
</div>
@endsection

@section('content')
<div class="container-fluid">
        <div class="row g-xl-4 g-lg-3 g-2 justify-content-between">
          <div class="col-xxl-9 col-xl-12 col-lg-12 order-1 order-xxl-0">
            <!--[ Start:: My Analytics Reports ]-->
            <ul class="row g-xl-3 g-2 list-unstyled row-deck">
              <li class="col-xxl-12 col-xl-6 col-lg-6 col-md-7">
                <ul class="row g-xl-3 g-2 list-unstyled row-deck">
                  <li class="col-12">
                    <div class="card">
                      <div class="card-header pb-0">
                        <h5 class="card-title fw-normal mb-0">MY TASK ANALYTICS</h5>
                      </div>
                      <div class="card-body">
                        <ul class="list-inline d-flex">
                          <li class="list-inline-item bg-body p-3 border dashed rounded-4">
                            @if($total != 0)
                            <span class="small text-muted">My WPs<span class="ps-2 fa fa-caret-down text-danger"> {{ number_format(count($my_wps)/$total*100, 2, '.', '') }}%</span></span>
                            @else
                            <span class="small text-muted">My WPs<span class="ps-2 fa fa-caret-down text-danger"> {{ $total }}%</span></span>
                            @endif
                            <h6 class="mb-0 mt-1">count: {{ count($my_wps) }}</h6>
                          </li>
                          <li class="list-inline-item bg-body p-3 border dashed rounded-4">
                            @if($total != 0)
                            <span class="small text-muted">My Tasks<span class="ps-2 fa fa-caret-down text-danger"> {{ number_format(count($my_tasks)/$total*100, 2, '.', '') }}%</span></span>
                            @else
                            <span class="small text-muted">My Tasks<span class="ps-2 fa fa-caret-down text-danger"> {{ $total }}%</span></span>
                            @endif
                            <h6 class="mb-0 mt-1">count: {{ count($my_tasks) }}</h6>
                          </li>
                          <li class="list-inline-item bg-body p-3 border dashed rounded-4">
                            @if($total != 0)
                            <span class="small text-muted">My SubTasks<span class="ps-2 fa fa-caret-down text-danger"> {{ number_format(count($my_subtasks)/$total*100, 2, '.', '') }}%</span></span>
                            @else
                            <span class="small text-muted">My SubTasks<span class="ps-2 fa fa-caret-down text-danger"> {{ $total }}%</span></span>
                            @endif
                            <h6 class="mb-0 mt-1">count: {{ count($my_subtasks) }}</h6>
                          </li>
                        </ul>
                        <div id="apex_revenue_analytics" class="apex-extra-none"></div>
                      </div>
                    </div>
                  </li>
                </ul> <!--[ ul.row end ]-->
              </li>
            </ul> <!--[ ul.row end ]-->
          </div>
          <div class="col-xxl-3 col-xl-12 col-lg-12 order-0 order-xxl-1">
            <div class="card overflow-hidden ms-xxl-2">
              <div class="card-header bg-gradient text-white">
                <!-- <div class="display-4 mb-1">28°C</div>
                <h6 class="fw-lighter"><i class="wi wi-day-cloudy-gusts me-2"></i>New York, NY, USA</h6> -->
              </div>
              <div class="card-body">
                <div class="my-calendar inline-calendar"></div>
              </div>
            </div>
          </div>
        </div> <!--[ .row end ]-->
      </div>
@endsection

@push('script')
<script>
    $('.my-calendar').datepicker({  
      todayHighlight: true
    });
    //** My Project chart js**
    // Revenue Components
    var options = {
      series: [{
        name: 'WPs',
        @if($total != 0)
        data: ['{{ number_format(count($my_wps)/$total*100, 2, '.', '') }}']
        @else
        data: [0]
        @endif
      }, {
        name: 'Tasks',
        @if($total != 0)
        data: ['{{ number_format(count($my_tasks)/$total*100, 2, '.', '') }}']
        @else
        data: [0]
        @endif
      }, {
        name: 'SubTasks',
        @if($total != 0)
        data: ['{{ number_format(count($my_subtasks)/$total*100, 2, '.', '') }}']
        @else
        data: [0]
        @endif
      }],
      chart: {
        type: 'bar',
        height: 200,
        stacked: true,
        stackType: '100%',
        offsetX: -25,
        toolbar: {
          show: false,
        },
      },
      colors: ['var(--theme-color1)', 'var(--theme-color2)', 'var(--theme-color3)'],
      plotOptions: {
        bar: {
          horizontal: true,
        },
      },
      stroke: {
        width: 1,
        colors: ['#fff']
      },
      xaxis: {
        categories: [2021],
        labels: {
          show: false,
        },
      },
      yaxis: {
        show: false,
      },
      dataLabels: {
        //enabled: false,
      },
      tooltip: {
        enabled: false,
        y: {
          formatter: function(val) {
            return val + "K"
          }
        }
      },
      fill: {
        opacity: 1
      },
      legend: {
        position: 'bottom',
        horizontalAlign: 'left',
        offsetX: 0
      }
    };
    var chart = new ApexCharts(document.querySelector("#apex_revenue_analytics"), options);
    chart.render();
    var options = {
      chart: {
        type: 'bar',
        width: 120,
        height: 80,
        sparkline: {
          enabled: true
        }
      },
      stroke: {
        width: 2
      },
      plotOptions: {
        bar: {
          columnWidth: '80%'
        }
      },
      colors: ['var(--theme-color5)'],
      series: [{
        data: [25, 66, 41, 89, 63, 25]
      }],
      labels: [1, 2, 3, 4, 5, 6, ],
      xaxis: {
        crosshairs: {
          width: 1
        },
      },
      tooltip: {
        fixed: {
          enabled: false
        },
        x: {
          show: false
        },
        y: {
          title: {
            formatter: function(seriesName) {
              return ''
            }
          }
        },
        marker: {
          show: false
        }
      }
    }
</script>
@endpush
