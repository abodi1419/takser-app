<table class="table text-center">
    <thead>
    <tr>
        <th>{{__('Title')}}</th>
        <th>{{__('Start')}}</th>
        <th>{{__('End')}}</th>
        <th>{{__('Group')}}</th>
        <th>{{__('Status')}}</th>
        <th>{{__('Actions')}}</th>
    </tr>
    </thead>
    <tbody>
    @forelse($tableTasks as $task)
        <tr @if($task->status==4) class="text-danger fst-italic" @endif>
            <td>{{$task->title}}</td>
            <td>
                @if(app()->getLocale()=='ar')
                    {{\Carbon\Carbon::parse($task->start)->translatedFormat('j F, Y')}}
                @else
                    {{date('d M, Y',strtotime($task->start))}}
                @endif
                <br>
                {{date('h:i A',strtotime($task->start))}}
            </td>
            <td>
                @if(app()->getLocale()=='ar')
                    {{\Carbon\Carbon::parse($task->end)->translatedFormat('j F, Y')}}
                @else
                    {{date('d M, Y',strtotime($task->end))}}
                @endif
                <br>
                {{date('h:i A',strtotime($task->end))}}
            </td>
            <td>
                <a class="nav-link" href="{{route('groups.show',$task->group)}}">{{$task->group->name}}</a>
            </td>
            <td>
                @if($task->status == 1)
                    <span class="badge text-bg-info">
                                    {{__("Created")}}
                                </span>
                @elseif($task->status == 2)
                    <span class="badge text-bg-warning">
                                    {{__("Started")}}
                                </span>
                @elseif($task->status == 3)
                    <span class="badge text-bg-success">
                                    {{__("Ended")}}
                                </span>
                @elseif($task->status == 4)
                    <span class="badge text-bg-danger">
                                    {{__("Overdue")}}
                                </span>

                @endif
            </td>
            <td>
                <a href="{{route('tasks.show',$task)}}" class="btn btn-info">
                    <i class="fa fa-eye"></i>
                </a>
            @if($index==1)
                    <a href="{{route('tasks.edit',$task)}}" class="btn btn-warning">
                        <i class="fa fa-edit"></i>
                    </a>
                @elseif($index==2)
                    <a href="{{route('tasks.deselect',$task)}}" class="btn btn-white text-success">
                        <i class="fa fa-toggle-on"></i>
                    </a>
                @elseif($index==3)
                    <a href="{{route('tasks.uncomplete',$task)}}" class="btn btn-danger">
                        <i class="fa fa-times"></i>
                    </a>
                @endif
                {{--                            <a href="{{route('groups.destroy',$group)}}" class="btn btn-danger">{{__('Delete')}}</a>--}}
            </td>
        </tr>
        <tr>
            <td colspan="5">

                <div class="progress" style="height: 30px" >
                    @if($task->progress==0)
                        <div class="progress-bar text-danger" role="progressbar" aria-label="Example with label" style="height: 30px;width: 100%; background-color: #e9ecef" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            {{__('Progress')}} {{$task->progress}}%
                        </div>
                    @else
                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-label="Example with label" style="height: 30px;width: {{$task->progress}}%;" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100">
                            {{__('Progress')}} {{$task->progress}}%
                        </div>
                    @endif
                </div>
            </td>
            <td></td>
        </tr>

    @empty
        <tr>
            <td colspan="8">
                @if($index==1)
                    {{__("No tasks created")}}
                @elseif($index==2)
                    {{__("No tasks assigned todo")}}
                @elseif($index==3)
                    {{__("No tasks completed")}}
                @endif
            </td>
        </tr>
    @endforelse

    </tbody>
</table>
