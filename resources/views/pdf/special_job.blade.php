<!DOCTYPE html>
<html>
    <head>
        <title>WP - PDF</title>
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
            {{ $job_card->title }}
            @if($job_card->status == 0)
            <span style="color:#ffc107; font-size: 11px;">{{ $status[$job_card->status] }}</span>
            @elseif($job_card->status == 1)
            <span style="color:#dc3545; font-size: 11px;">{{ $status[$job_card->status] }}</span>
            @elseif($job_card->status == 2)
            <span style="color:#198754; font-size: 11px;">{{ $status[$job_card->status] }}</span>
            @elseif($job_card->status == 3)
            <span style="color:black; font-size: 11px;">{{ $status[$job_card->status] }}</span>
            @else
            <span style="color:#ffc107; font-size: 11px;">{{ $status[$job_card->status] }}</span>
            @endif
        </h1>

        <div style="border: solid 1px black; padding: 10px; margin-bottom: 20px;">
            <strong>{{ __('global.coordinater') }}</strong>: {{ $job_card->coordinater }}
            <strong>{{ __('global.university') }}</strong>: {{ $job_card->university }}
        </div>

        @foreach($job_card->comments as $key => $comment)
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

        @if($job_card->question_description != null)
        <div class="grid-container" style="margin-bottom: 0.625rem;">
            <div class="item1" style="text-align: left; padding-right: 10px; padding-left: 10px;">{!! $job_card->question_description->content !!}</div>            
        </div>
        @endif
        @foreach($job_card->questions as $key => $question)
        <div class="grid-container" style="margin-bottom: 0.625rem;">
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

        @foreach($job_card->descriptions as $key => $description)
        <div class="grid-container" style="background-color: yellow; margin-bottom: 0.625rem;">
            <div class="item1" style="background-color: rgba(255, 255, 255, 0.8); text-align: left; padding-right: 10px; padding-left: 10px;">{!! $description->content !!}</div>            
        </div>
        @endforeach
    </body>
</html>