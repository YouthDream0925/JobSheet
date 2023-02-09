<!DOCTYPE html>
<html>
    <head>
        <title>Task - PDF</title>
        <style>
            .grid-container {
                display: grid;
                grid: 150px / auto auto;
                grid-gap: 2px;
                background-color: #2196F3;
                padding: 2px;
            }

            .grid-container > div {
                background-color: rgba(255, 255, 255, 0.8);
                text-align: center;
                padding: 10px 0;
                font-size: 11px;
            }
        </style>
    </head>
    <body style="font-size: 11px !important;">
        <h1 style="font-size: 11px !important;">
            {{ $task->title }}
            @if($task->status == 0)
            <span style="color:#ffc107; font-size: 11px;">{{ $status[$task->status] }}</span>
            @elseif($task->status == 1)
            <span style="color:#dc3545; font-size: 11px;">{{ $status[$task->status] }}</span>
            @elseif($task->status == 2)
            <span style="color:#198754; font-size: 11px;">{{ $status[$task->status] }}</span>
            @elseif($task->status == 3)
            <span style="color:black; font-size: 11px;">{{ $status[$task->status] }}</span>
            @else
            <span style="color:#ffc107; font-size: 11px;">{{ $status[$task->status] }}</span>
            @endif
        </h1>
        
        <div style="border: solid 1px black; padding: 10px; margin-bottom: 20px;">
            <strong>{{ __('global.coordinater') }}</strong>: @if($task->coordinater != null) {{ $task->coordinater }} @else NONE @endif
            <strong>{{ __('global.university') }}</strong>: @if($task->university != null) {{ $task->university }} @else NONE @endif
        </div>

        @foreach($task->comments as $key => $comment)
        <div style="border: solid 1px black; padding: 10px; margin-bottom: 20px;">
            <div style="background-color: rgba(200, 200, 200, 0.8);">
                <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                @if($comment->phase == null)
                @else
                <strong>{{ __('global.phase') }}</strong>: {{ $phase[$comment->phase] }}
                @endif                
            </div>
            {!! $comment->content !!}
        </div>
        @endforeach

        @if(count($task->descriptions) != 0)
            @foreach($task->descriptions as $key => $description)
            <div class="grid-container" style="background-color: yellow; margin-bottom: 0.625rem;">
                <div class="item1" style="background-color: rgba(255, 255, 255, 0.8); text-align: left; padding-right: 10px; padding-left: 10px;">{!! $description->content !!}</div>            
            </div>
            @endforeach
        @endif

        @foreach($task->subtasks as $key => $sub_task)
            <h3 style="font-size: 11px !important;">
                {{ $sub_task->title }}
                @if($sub_task->status == 0)
                <span style="color:#ffc107; font-size: 11px;">{{ $status[$sub_task->status] }}</span>
                @elseif($sub_task->status == 1)
                <span style="color:#dc3545; font-size: 11px;">{{ $status[$sub_task->status] }}</span>
                @elseif($sub_task->status == 2)
                <span style="color:#198754; font-size: 11px;">{{ $status[$sub_task->status] }}</span>
                @elseif($sub_task->status == 3)
                <span style="color:black; font-size: 11px;">{{ $status[$sub_task->status] }}</span>
                @else
                <span style="color:#ffc107; font-size: 11px;">{{ $status[$sub_task->status] }}</span>
                @endif
            </h3>
            
            <div style="border: solid 1px black; padding: 10px; margin-bottom: 20px;">
                <strong>{{ __('global.coordinater') }}</strong>: @if($sub_task->coordinater != null) {{ $sub_task->coordinater }} @else NONE @endif
                <strong>{{ __('global.university') }}</strong>: @if($sub_task->university != null) {{ $sub_task->university }} @else NONE @endif
            </div>

            @foreach($sub_task->comments as $key => $comment)
            <div style="border: solid 1px green; padding: 10px; margin-bottom: 20px;">
                <div style="background-color: rgba(200, 200, 200, 0.8);">
                    <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                    @if($comment->phase == null)
                    @else
                    <strong>{{ __('global.phase') }}</strong>: {{ $phase[$comment->phase] }}
                    @endif                
                </div>
                {!! $comment->content !!}
            </div>
            @endforeach

            @if(count($sub_task->descriptions) != 0)
                @foreach($sub_task->descriptions as $key => $description)
                <div class="grid-container" style="background-color: yellow; margin-bottom: 0.625rem;">
                    <div class="item1" style="background-color: rgba(255, 255, 255, 0.8); text-align: left; padding-right: 10px; padding-left: 10px;">{!! $description->content !!}</div>            
                </div>
                @endforeach
            @endif
        @endforeach
    </body>
</html>