<!doctype html>
<html>
<head>
    <meta charset="utf-8"/>
</head>
<body>

<table>
    <thead>

    </thead>
    <tbody>
    @foreach($groups as $index=>$group)
        <tr>
            <td colspan="7"  style="font-weight: bold; height: 50px; vertical-align: center; text-align: center; background-color: lightseagreen">

                @if($index+1==1)
                    {{$index+1}}St
                @elseif($index+1==2)
                    {{$index+1}}nd
                @elseif($index+1==2)
                    {{$index+1}}rd
                @else
                    {{$index+1}}th
                @endif
                 Group
            </td>
        </tr>
        <tr>
            <th colspan="2" style="width: 100px; font-weight: bold; height: 50px; vertical-align: center; text-align: center">Name</th>
            <th colspan="3" style="width: 250px; font-weight: bold; vertical-align: center; text-align: center">Description</th>
            <th colspan="2" style="width: 250px; font-weight: bold; vertical-align: center; text-align: center">Users</th>
            {{--        <th>Tasks</th>--}}
            {{--        <th>Start</th>--}}
            {{--        <th>End</th>--}}
        </tr>
        <tr>
            <td colspan="2" style="text-align: center; height: 50px; vertical-align: center">{{ $group->name }}</td>
            <td colspan="3" style="text-align: center; vertical-align: center">{{ $group->description }}</td>
            <td colspan="2" style="text-align: center; vertical-align: center">
                @forelse($group->users as $index=>$user)
                    <span>{{$user->name}}</span>@if($index+1<count($group->users)),&emsp; @endif
                @empty
                    <span style="color: darkred">No users!</span>
                @endforelse
            </td>
{{--            @foreach($group->tasks as $index=>$task)--}}
{{--                @if($index>0)--}}
{{--                    <tr>--}}
{{--                @endif--}}

{{--                @if($index>0)--}}
{{--                    </tr>--}}
{{--                @endif--}}
{{--            @endforeach--}}
        </tr>
        <tr>
            <td colspan="7" style="font-weight: bold; height: 50px; vertical-align: center; background-color: lightblue">Group tasks</td>
        </tr>
        <tr>
            <th style="font-weight: bold; width: 100px; height: 50px; vertical-align: center; text-align: center">Title</th>
            <th style="font-weight: bold; width: 200px; vertical-align: center; text-align: center">Description</th>
            <th style="font-weight: bold; width: 150px; vertical-align: center; text-align: center">Start</th>
            <th style="font-weight: bold; width: 150px; vertical-align: center; text-align: center">End</th>
            <th style="font-weight: bold; width: 70px; vertical-align: center; text-align: center">Progress</th>
            <th style="font-weight: bold; width: 80px; vertical-align: center; text-align: center">Status</th>
            <th style="font-weight: bold; width: 200px; vertical-align: center; text-align: center">Users</th>
        </tr>
        @forelse($group->tasks as $task)
            <tr>
                <td style="text-align: center; height: 50px; vertical-align: center">{{$task->title}}</td>
                <td style="text-align: center; vertical-align: center">{{$task->description}}</td>
                <td style="text-align: center; color: darkgreen; vertical-align: center">
                    {{date("d M, Y", strtotime($task->start))}}
                    <br>
                    {{date("H:i A", strtotime($task->start))}}
                </td>
                <td style="text-align: center; color: red; vertical-align: center">
                    {{date("d M, Y", strtotime($task->end))}}
                    <br>
                    {{date("H:i A", strtotime($task->end))}}
                </td>
                <td style="text-align: center; vertical-align: center">{{$task->progress}}%</td>
                <td style="text-align: center; vertical-align: center; font-weight: bold;
                @if($task->status==1)color: blue
                @elseif($task->status==2) color: orange
                @elseif($task->status==3) color: green
                @elseif($task->status==4) color: red
                @endif
                ;">
                    @if($task->status==1)
                        <span style="color: blue">Created</span>
                    @elseif($task->status==2)
                        <span style="color: red">Running</span>
                    @elseif($task->status==3)
                        <span style="">Finished</span>
                    @elseif($task->status==4)
                        <span style="">Overdue</span>
                    @endif
                </td>
                <td style="text-align: center; vertical-align: center">
                    @forelse($task->users as $index=>$user)
                        <span>{{$user->name}}</span>@if($index+1<count($task->users)),&emsp; @endif
                    @empty
                        <span style="color: darkred">No users!</span>
                    @endforelse
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" style="text-align: center; height: 50px; vertical-align: center">No tasks added!</td>
            </tr>
        @endforelse
        <tr><td colspan="7"></td></tr>
    @endforeach

    </tbody>
</table>
</body>
</html>
