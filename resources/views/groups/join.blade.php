@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-white">
                <h6>
                    {{__('Name').': '.$group->name}}
                </h6>
                <h6>
                    {{__('Description').': '.$group->description?$group->description:__("No description")}}
                </h6>
            </div>
            <div class="card-body">
                <h4>{{__("Tasks")}}</h4>
                <ul>
                    @forelse($tasks as $task)
                        <li>
                            {{$task->title}}
                            <div class="row small">
                                <div class="col-6">
                                    {{date('d M, Y',strtotime($task->start))}}
                                    <br>
                                    {{date('h:i A',strtotime($task->start))}}
                                </div>
                                <div class="col-6">
                                    {{date('d M, Y',strtotime($task->end))}}
                                    <br>
                                    {{date('h:i A',strtotime($task->end))}}
                                </div>
                            </div>
                            <br>
                        </li>
                    @empty
                        <li>{{__('No tasks added')}}</li>
                    @endforelse
                </ul>
                <h4>{{__("Participants")}}</h4>
                <ul>
                    @forelse($users as $user)
                        <li>{{$user->email.' '.$user->name}}</li>
                    @empty
                        <li>{{__('No participants added')}}</li>
                    @endforelse
                </ul>
                <a class="btn btn-primary me-3" href="{{route('groups.join',$group)}}">Join</a>
                <a class="btn btn-danger" href="{{route('groups.reject',$group)}}">Reject</a>
            </div>
        </div>

    </div>
@endsection
