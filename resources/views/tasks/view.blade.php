@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header bg-white">
                <h6>
                    <span class="fw-bold">
                        {{__('Title')}}
                    </span>
                </h6>
                <p>
                    {{$task->title}}
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Status')}}
                    </span>
                </h6>
                <p>
                    @if($task->status == 1)
                        <span class="badge text-bg-info">
                                    {{__("Created")}}
                                </span>
                    @elseif($task->status == 2)
                        <span class="badge text-bg-danger">
                                    {{__("Started")}}
                                </span>
                    @elseif($task->status == 3)
                        <span class="badge text-bg-success">
                                    {{__("Ended")}}
                                </span>
                    @endif
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Progress')}}
                    </span>
                </h6>
                    @if($task->progress==0)
                        <div class="progress-bar text-danger d-none d-md-block w-50" role="progressbar" aria-label="Example with label" style="height: 30px;width: 100%; background-color: #e9ecef" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            {{__('Progress')}} {{$task->progress}}%
                        </div>
                        <div class="progress-bar text-danger d-sm-block d-md-none d-lg-none" role="progressbar" aria-label="Example with label" style="height: 30px;width: 100%; background-color: #e9ecef" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                            {{__('Progress')}} {{$task->progress}}%
                        </div>
                    @else
                        <div class="progress d-sm-block d-md-none d-lg-none" style="height: 30PX">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-label="Example with label" style="height: 30px; width: {{$task->progress}}%;" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100">
                                {{__('Progress')}} {{$task->progress}}%
                            </div>
                        </div>
                        <div class="progress d-none d-md-block w-50" style="height: 30PX">
                            <div class="progress-bar progress-bar-striped" role="progressbar" aria-label="Example with label" style="height: 30px;width: {{$task->progress}}%;" aria-valuenow="{{$task->progress}}" aria-valuemin="0" aria-valuemax="100">
                                {{__('Progress')}} {{$task->progress}}%
                            </div>
                        </div>
                    @endif

                <h6>
                    <span class="fw-bold">
                        {{__('Task created by')}}
                    </span>
                </h6>
                <p>
                    {{$task->user->name}} | {{$task->user->email}}
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Group')}}
                    </span>
                </h6>
                <p>
                    <a href="{{route('groups.show',$task->group)}}" class="nav-link">
                        {{$task->group->name}}

                    </a>
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Group owner')}}
                    </span>
                </h6>
                <p>
                    {{$task->group->user->name}} | {{$task->group->user->email}}
                </p>


            </div>
            <div class="card-body">
                <div class="row small" id="task-info-{{$task->id}}">
                    <div class="col-sm-12 col-md-6 fw-bold mb-3">
                        {{__("Start")}}
                        <br>
                        @if(app()->getLocale()=='ar')
                            {{\Carbon\Carbon::parse($task->start)->translatedFormat('j F, Y')}}
                        @else
                            {{date('d M, Y',strtotime($task->start))}}
                        @endif
                        <span class="d-none d-sm-none d-lg-inline-block" style="width: 75%; border: black 1px solid; margin-left: 5px;"></span>
                        <br>
                        {{date('h:i A',strtotime($task->start))}}
                    </div>
                    <div class="col-sm-12 col-md-6 fw-bold mb-3">
                        {{__("End")}}
                        <br>
                        @if(app()->getLocale()=='ar')
                            {{\Carbon\Carbon::parse($task->end)->translatedFormat('j F, Y')}}
                        @else
                            {{date('d M, Y',strtotime($task->end))}}
                        @endif
                        <br>
                        {{date('h:i A',strtotime($task->end))}}
                    </div>
                </div>
                <h6 class="fw-bold">
                    {{__('Description')}}:
                </h6>
                <p>{{$task->description?$task->description:__("No description")}}</p>

            </div>
            <div class="card-footer bg-white">
                @php
                    $isSelected = false;
                @endphp
                <h4>{{__("Assigned to")}}</h4>
                <ul>
                    @forelse($users as $user)
                        <li>
                            @if($user->email == \Illuminate\Support\Facades\Auth::user()->email)
                                @php $isSelected = true; @endphp
                                <span class="text-success fw-bold">{{__("You")}} </span>|
                            @else
                                {{$user->name}} |
                            @endif
                                <span class="text-secondary">
                                    {{$user->email}}
                                </span>
                        </li>
                    @empty
                        <li>{{__('Not assigned to anyone')}}</li>
                    @endforelse
                </ul>
                @if(!$isSelected)
                    <a href="{{route('tasks.select',$task)}}" class="btn btn-primary">{{__('Select')}}</a>
                @else
                    <a href="{{route('tasks.deselect',$task)}}" class="btn btn-danger">{{__('Deselect')}}</a>

                @endif
                @if($isSelected)
                    @if(\Illuminate\Support\Facades\Auth::user()->isTaskCompleted($task)==null)
                        <a href="{{route('tasks.complete',$task)}}" class="btn btn-primary">{{__('Complete')}}</a>
                    @else
                        <a href="{{route('tasks.uncomplete',$task)}}" class="btn btn-primary">{{__('Not complete')}}</a>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
