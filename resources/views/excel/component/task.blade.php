<table>
    <tbody>
        <tr>
            <td>Title of Task</td>
            <td>{{ $task->title }}</td>
            <td></td>
            <td>Creator</td>
            <td>{{ $task->creator() }}</td>
            <td></td>
            <td>Status</td>
            <td>{{ $status[$task->status] }}</td>
        </tr>

        <tr>
        </tr>

        <tr>
            <td style="background-color: yellow;">Report</td>
            <td style="background-color: yellow;">Coordinator</td>
            <td style="background-color: yellow;">University</td>
            <td style="background-color: yellow;">Title</td>
            <td style="background-color: yellow;">Phase</td>
            <td style="background-color: yellow;">Content</td>
        </tr>
        @foreach($task->comments as $key => $comment)
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
            <td>{{ $task->coordinater }}</td>
            <td>{{ $task->university }}</td>
            <td>{{ $comment->title }}</td>
            <td>{{ $phase[$comment->phase] }}</td>
            <td>{!! $comment->content !!}</td>
        </tr>
        @endforeach

        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>
        <tr>
        </tr>

        <tr>
            <td style="background-color: yellow;">More Info</td>
        </tr>
        @foreach($task->descriptions as $key => $description)
        <tr>
            <td>{!! $description->content !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>