@extends('layouts.app')

@section('content')
    <style>
        p{
            text-align: center;
        }
    </style>
    <div class="container">
        <h3>{{__("Group information")}}</h3>
        <div class="card">
            <div class="card-header bg-white">
                <h6>
                    <span class="fw-bold">
                        {{__('Name')}}
                    </span>
                </h6>
                <p>
                    {{$group->name}}
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Description')}}
                    </span>
                </h6>
                <p>
                    {{$group->description}}
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Created by')}}
                    </span>
                </h6>
                <p>
                    {{$group->user->name}} | {{$group->user->email}}
                </p>
                <h6>
                    <span class="fw-bold">
                        {{__('Created at')}}
                    </span>
                </h6>
                <p>
                    @if(app()->getLocale()=='ar')
                        {{ \Carbon\Carbon::parse($group->created_at)->translatedFormat('j F, Y') }}
                    @else
                        {{$group->created_at->format("d M, Y ")}}
                    @endif
                </p>

            </div>
            <div class="card-body">
                <h4>{{__("Tasks")}}</h4>
                <ul>
                    @forelse($tasks as $task)
                        <li>
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <a href="{{route('tasks.show',$task)}}" class="nav-link">
                                        {{$task->title}}

                                    </a>

                                </div>
                                <div class="col-sm-12 col-md-6 text-primary">
                                    <button class="btn text-primary w-100 show-hide" id="show-hide-{{$task->id}}" onclick="showInfo(this,{{$task->id}})">
                                        {{__('Show more')}}
                                    </button>
                                </div>
                            </div>
                            <div class="row small" id="task-info-{{$task->id}}" hidden="hidden">
                                <div class="col-sm-12 col-md-6 fw-bold mb-3">
                                    {{__("Start")}}
                                    <br>
                                    @if(app()->getLocale()=='ar')
                                        {{ \Carbon\Carbon::parse($task->start)->translatedFormat('j F, Y') }}
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
                                        {{ \Carbon\Carbon::parse($task->end)->translatedFormat('j F, Y') }}
                                    @else
                                        {{date('d M, Y',strtotime($task->end))}}
                                    @endif
                                    <br>
                                    {{date('h:i A',strtotime($task->end))}}
                                </div>
                                <div class="col-12">
                                    {{__('Description')}}:
                                    <p>{{$task->description?$task->description:__("No description")}}</p>
                                </div>
                                <div class="col-12 mt-2">
                                    {{__('Users')}}
                                    <ul class="mb-2 " style="list-style-type: square;">
                                        @php
                                            $isSelected = false;
                                        @endphp
                                        @forelse($task->users()->get(['name','email']) as $user)

                                            <li>
                                                 <span class="fw-bold">
                                                     @if($user->email == \Illuminate\Support\Facades\Auth::user()->email)
                                                         @php $isSelected = true; @endphp
                                                         <span class="text-success fw-bold">{{__("You")}} </span>|
                                                     @else
                                                        {{$user->name}} |
                                                     @endif
                                                </span>
                                                <span class="text-secondary">
                                                    {{$user->email}}
                                                </span>
                                            </li>
                                        @empty
                                            <li>{{__('No users took this task')}}</li>
                                        @endforelse
                                    </ul>
                                    <div class="text-center" >

                                        @if(!$isSelected)
                                            <a href="{{route('tasks.select',$task)}}" style="min-width: 80px" class="btn btn-primary">{{__('Select')}}</a>
                                        @else
                                            <a href="{{route('tasks.deselect',$task)}}" class="btn btn-danger">{{__('Deselect')}}</a>

                                        @endif
                                    </div>

                                </div>
                            </div>
                            <br>
                        </li>
                    @empty
                        <li>{{__('No tasks added')}}</li>
                    @endforelse
                </ul>

            </div>
            <div class="card-footer bg-white">
                <h4>{{__("Participants")}}</h4>
                <ul>
                    @forelse($users as $user)
                        <li>
                            <div class="row mb-2">
                                <div class="col">
                                    <span class="fw-bold">
                                        {{$user->name}} |
                                    </span>
                                    <span class="text-secondary">
                                        {{$user->email}}
                                    </span>
                                </div>
                                @if($group->user_id==\Illuminate\Support\Facades\Auth::id())
                                    <div class="col">
                                        <a class="btn btn-danger fw-bold" href="{{route("groups.kick",[$user,$group])}}">X</a>
                                    </div>
                                @endif

                            </div>

                        </li>
                    @empty
                        <li>{{__('No participants added')}}</li>
                    @endforelse
                </ul>
                @if($group->user_id==Auth::id())
                    <form method="POST" action="{{ route('groups.add.participant',$group) }}">
                        @csrf
                        @method('PATCH')
                        <div class="row mb-3">
                            <label for="email" class="col-12 col-form-label text-start">{{ __('Email Address') }}</label>

                            <div class="col-8">
                                <input id="email" type="email" class="form-control mb-3 @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                @enderror
                            </div>

                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Send invitation') }}
                                </button>
                            </div>
                        </div>

                    </form>
                @endif
            </div>
        </div>

    </div>
    <script>
        if(document.location.search){

            // e.innerHTML = "Show more";
            document.getElementById("show-hide-"+ document.location.search.split("=")[1]).click();
        }
        function showInfo(e,id){
            let buttons = document.getElementsByClassName('show-hide');
            for(let i=0; i<buttons.length; i++){
                if(buttons[i].innerHTML=='{{__("Hide")}}' && buttons[i]!=e){
                    buttons[i].click();
                }
            }
            let text = e.innerHTML;
            if(text == '{{__("Hide")}}') {
                e.innerHTML = '{{__("Show more")}}';
                document.getElementById("task-info-"+id).hidden = true;
                window.history.pushState("object or string", document.title, window.location.pathname );

            }else {
                e.innerHTML = '{{__("Hide")}}';
                window.history.replaceState(null, null, "?task="+id);
                document.getElementById("task-info-"+id).hidden = false;
            }
        }
    </script>
@endsection
