<table>
    <tbody>
        <tr>
            <td>Title of WP</td>
            <td>{{ $job_card->title }}</td>
            <td></td>
            <td>Creator</td>
            <td>{{ $job_card->creator() }}</td>
            <td></td>
            <td>Status</td>
            <td>{{ $status[$job_card->status] }}</td>
        </tr>

        <tr></tr>

        <tr>
            <td style="background-color: yellow;">Report</td>
            <td style="background-color: yellow;">Coordinator</td>
            <td style="background-color: yellow;">University</td>
            <td style="background-color: yellow;">Title</td>
            <td style="background-color: yellow;">Phase</td>
            <td style="background-color: yellow;">Content</td>
        </tr>
        
        @foreach($job_card->comments as $key => $comment)
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td style="background-color: yellow;">Title</td>
            <td style="background-color: yellow;">Phase</td>
            <td style="background-color: yellow;">Content</td>
        </tr>
        <tr>
            <td></td>
            <td>{{ $job_card->coordinater }}</td>
            <td>{{ $job_card->university }}</td>
            <td>{{ $comment->title }}</td>
            <td>{{ $phase[$comment->phase] }}</td>
            <td>{!! $comment->content !!}</td>
        </tr>
        @endforeach

        <tr></tr>
        <tr></tr>

        @if($job_card->special == 1)
        <tr>
            <td style="background-color: yellow;">Question</td>
            <td style="background-color: yellow;">Answer</td>
            <td style="background-color: yellow;">Description</td>
        </tr>

        @if($job_card->question_description != null)
        <tr>
            <td></td>
            <td></td>
            <td>{!! $job_card->question_description->content !!}</td>
        </tr>
        @endif

        @foreach($job_card->questions as $key => $question)
        <tr>
            <td>{{ $question->content }}</td>
            @if($question->type == 0)
            <td>{{ $question->correct_answer_string() }}</td>
            @else            
                @if(count($question->answers))
                    @foreach($question->answers as $answer_key => $answer)
                    <td>{{ $answer->answer }}</td>
                    @endforeach
                @else
                    <td></td>
                @endif
            @endif
            <td></td>
        </tr>
        @endforeach
        @endif

        <tr></tr>
        <tr></tr>

        <tr>
            <td style="background-color: yellow;">More Info</td>
        </tr>
        @foreach($job_card->descriptions as $key => $description)
        <tr>
            <td>{!! $description->content !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>