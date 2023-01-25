<table>
    <tbody>
        <tr>
            <td>Title of SubTask</td>
            <td>{{ $sub_task->title }}</td>
            <td></td>
            <td>Creator</td>
            <td>{{ $sub_task->creator() }}</td>
            <td></td>
            <td>Status</td>
            <td>{{ $status[$sub_task->status] }}</td>
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
        @foreach($sub_task->comments as $key => $comment)
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
            <td>{{ $sub_task->coordinater }}</td>
            <td>{{ $sub_task->university }}</td>
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
        @foreach($sub_task->descriptions as $key => $description)
        <tr>
            <td>{!! $description->content !!}</td>
        </tr>
        @endforeach
    </tbody>
</table>