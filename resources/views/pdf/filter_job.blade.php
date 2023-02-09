<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel 8 HTML to PDF Example</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}">
    <style>
        .item1 { grid-area: header; }
        .item2 { grid-area: menu; }
        .item3 { grid-area: main; }
        .item4 { grid-area: right; }
        .item5 { grid-area: footer; }

        .grid-container {
            display: grid;
            grid-template-areas: 'header';
        }

        .grid-container > div {
            background-color: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 20px 0;
            font-size: 11px;
        }

        .border-style {
            border: solid 1px black;
            padding: 10px;
            margin-bottom: 1rem;
        }

        .fc_filter_label {
            clear: none;
            width: 200px;
            padding: 6px 1%;
            text-align: left;
            background-color: #0d0091;
            color: #ffffff;

            float: left;
            border-width: 0px;
            font-weight: normal;
            font-size: 11px;
            font-family: georgia, arial;
            border-radius: 3px;
        }

        .fc_filter_label_1 {
            clear: none;
            max-width: 200px;
            padding: 6px 1%;
            text-align: left;
            background-color: #ffffff;
            color: #000000;

            border-width: 0px;
            font-weight: normal;
            font-size: 11px;
            font-family: georgia, arial;
            border-radius: 3px;
        }

        .question-container {
            grid-gap: 2px;
            background-color: #2196F3;
            padding: 2px;
        }

        .question-container > div {
            background-color: rgba(255, 255, 255, 0.8);
            text-align: center;
            padding: 10px 0;
            font-size: 11px;
        }
    </style>
</head>

<body class="antialiased container mt-1" style="max-width: 100% !important; font-size: 11px;">
    <div class="grid-container">
        <div class="item1">
            <img id="avatar_container" src="{{ public_path('logo.png') }}" width="184" height="44" title="avatar" class="rounded-4">
        </div>
    </div>

    <h3 style="font-size: 11px !important;">{{ $job_card->title }}</h3>
   
    @if($field == true)
    <div class="border-style" style="display: block;">
        <p>
            <strong class="fc_filter_label">{{ __('global.coordinater') }}</strong>
            <span class="fc_filter_label_1">
                @if($job_card->coordinater != null)
                    {{ $job_card->coordinater }}
                @else
                    NONE
                @endif
            </span>
        </p>
        <p>
            <strong class="fc_filter_label">{{ __('global.university') }}</strong>
            <span class="fc_filter_label_1">
                @if($job_card->university != null)
                    {{ $job_card->university }}
                @else
                    NONE
                @endif
            </span>
        </p>
        <p>
            <strong class="fc_filter_label">{{ __('global.creator') }}</strong>
            <span class="fc_filter_label_1">
                @if($job_card->name() != null)
                    {{ $job_card->name() }}
                @else
                    NONE
                @endif
            </span>
        </p>
        <p>
            <strong class="fc_filter_label">{{ __('global.status') }}</strong>
            <span class="fc_filter_label_1">
                {{ $status[$job_card->status] }}
            </span>
        </p>
    </div>
    @endif

    @foreach($job_card->comments as $key => $comment)
        @if($phase != null)
            @if($phase == $comment->phase)
            <div style="background-color: rgba(200, 200, 200, 0.8);">
                @if($comment->title == null)
                <strong>{{ __('global.title') }}</strong>: NONE
                @else
                <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                @endif
                <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
            </div>
            <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
                <div class="card-body">
                    {!! $comment->content !!}
                </div>
            </div>
            @endif
        @else
        <div style="background-color: rgba(200, 200, 200, 0.8);">
            @if($comment->title == null)
            <strong>{{ __('global.title') }}</strong>: NONE
            @else
            <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
            @endif
            <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
        </div>
        <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
            <div class="card-body">
                {!! $comment->content !!}
            </div>
        </div>
        @endif
    @endforeach

    @if($question == true)
        @if($job_card->question_description != null)
        <div class="collapse show card mb-2" style="background-color: #d3eafd;">
            <div class="card-body">
                {!! $job_card->question_description->content !!}
            </div>
        </div>
        @endif
        @foreach($job_card->questions as $key => $question)
        <div class="question-container" style="margin-bottom: 0.625rem;">
            <div class="item1">{!! $question->content !!}</div>
            <div class="item2">
                @if($question->type == 0)
                    @foreach($question->answers as $key => $answer)
                        @if($answer->correct == 1)
                            {{ $answer->answer }}
                        @endif
                    @endforeach
                @else
                    @foreach($question->answers as $key => $answer)
                        {{ $answer->answer }},
                    @endforeach
                @endif
            </div>
        </div>
        @endforeach
    @endif

    @foreach($job_card->descriptions as $key => $description)
    <div class="collapse show card mb-2">
        <div class="card-body">
            {!! $description->content !!}
        </div>
    </div>
    @endforeach

    @foreach($job_card->tasks as $key => $task)
        <h5 class="mt-1" style="font-size: 11px !important;">{{ $task->title }}</h5>
        
        @if($field == true)
        <div class="border-style" style="display: block;">
            <p>
                <strong class="fc_filter_label">{{ __('global.coordinater') }}</strong>
                <span class="fc_filter_label_1">
                    @if($task->coordinater != null)
                        {{ $task->coordinater }}
                    @else
                        NONE
                    @endif
                </span>
            </p>
            <p>
                <strong class="fc_filter_label">{{ __('global.university') }}</strong>
                <span class="fc_filter_label_1">
                    @if($task->university != null)
                        {{ $task->university }}
                    @else
                        NONE
                    @endif
                </span>
            </p>
            <p>
                <strong class="fc_filter_label">{{ __('global.creator') }}</strong>
                <span class="fc_filter_label_1">
                    @if($task->name() != null)
                        {{ $task->name() }}
                    @else
                        NONE
                    @endif
                </span>
            </p>
            <p>
                <strong class="fc_filter_label">{{ __('global.status') }}</strong>
                <span class="fc_filter_label_1">
                    {{ $status[$task->status] }}
                </span>
            </p>
        </div>
        @endif

        @foreach($task->comments as $key => $comment)
            @if($phase != null)
                @if($phase == $comment->phase)
                <div style="background-color: rgba(200, 200, 200, 0.8);">
                    @if($comment->title == null)
                    <strong>{{ __('global.title') }}</strong>: NONE
                    @else
                    <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                    @endif
                    <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
                </div>
                <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
                    <div class="card-body">
                        {!! $comment->content !!}
                    </div>
                </div>
                @endif
            @else
            <div style="background-color: rgba(200, 200, 200, 0.8);">
                @if($comment->title == null)
                <strong>{{ __('global.title') }}</strong>: NONE
                @else
                <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                @endif
                <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
            </div>
            <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
                <div class="card-body">
                    {!! $comment->content !!}
                </div>
            </div>
            @endif
        @endforeach

        @foreach($task->descriptions as $key => $description)
        <div class="collapse show card mb-2">
            <div class="card-body">
                {!! $description->content !!}
            </div>
        </div>
        @endforeach

        @foreach($task->subtasks as $key => $sub_task)
            <h5 class="mt-1" style="font-size: 11px !important;">{{ $sub_task->title }}</h5>
            
            @if($field == true)
            <div class="border-style" style="display: block;">
                <p>
                    <strong class="fc_filter_label">{{ __('global.coordinater') }}</strong>
                    <span class="fc_filter_label_1">
                        @if($sub_task->coordinater != null)
                            {{ $sub_task->coordinater }}
                        @else
                            NONE
                        @endif
                    </span>
                </p>
                <p>
                    <strong class="fc_filter_label">{{ __('global.university') }}</strong>
                    <span class="fc_filter_label_1">
                        @if($sub_task->university != null)
                            {{ $sub_task->university }}
                        @else
                            NONE
                        @endif
                    </span>
                </p>
                <p>
                    <strong class="fc_filter_label">{{ __('global.creator') }}</strong>
                    <span class="fc_filter_label_1">
                        @if($sub_task->name() != null)
                            {{ $sub_task->name() }}
                        @else
                            NONE
                        @endif
                    </span>
                </p>
                <p>
                    <strong class="fc_filter_label">{{ __('global.status') }}</strong>
                    <span class="fc_filter_label_1">
                        {{ $status[$sub_task->status] }}
                    </span>
                </p>
            </div>
            @endif

            @foreach($sub_task->comments as $key => $comment)
                @if($phase != null)
                    @if($phase == $comment->phase)
                    <div style="background-color: rgba(200, 200, 200, 0.8);">
                        @if($comment->title == null)
                        <strong>{{ __('global.title') }}</strong>: NONE
                        @else
                        <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                        @endif
                        <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
                    </div>
                    <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
                        <div class="card-body">
                            {!! $comment->content !!}
                        </div>
                    </div>
                    @endif
                @else
                <div style="background-color: rgba(200, 200, 200, 0.8);">
                    @if($comment->title == null)
                    <strong>{{ __('global.title') }}</strong>: NONE
                    @else
                    <strong>{{ __('global.title') }}</strong>: {{ $comment->title }}
                    @endif
                    <strong>{{ __('global.phase') }}</strong>: {{ $global_phase_to_show[$comment->phase] }}
                </div>
                <div class="collapse show card mb-2 @if($comment->phase) comment-style-{{$comment->phase}} @else comment-style @endif">
                    <div class="card-body">
                        {!! $comment->content !!}
                    </div>
                </div>
                @endif
            @endforeach

            @foreach($sub_task->descriptions as $key => $description)
            <div class="collapse show card mb-2">
                <div class="card-body">
                    {!! $description->content !!}
                </div>
            </div>
            @endforeach
        @endforeach
    @endforeach

    <!-- <table class="table">
        <thead>
            <tr class="table-primary">
                <td>Product Name</td>
                <td>Price</td>
                <td>In Stock</td>
            </tr>
        </thead>
        <tbody>
        
        </tbody>
    </table> -->
</body>

</html>