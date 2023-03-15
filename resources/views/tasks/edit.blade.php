@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit task') }}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('tasks.update',$task) }}">
                            @csrf
                            @method("PATCH")
                            <div class="row mb-3">
                                <label for="group_id" class="col-md-4 col-form-label text-md-end">{{ __('Group') }}</label>

                                <div class="col-md-6">
                                    <select name="group_id" id="group_id" class="form-select">
                                        <option value="" selected>No selection</option>
                                        @foreach($groups as $group)
                                            <option value="{{$group->id}}" @if($task->group_id==$group->id) selected @endif>{{$group->name}}</option>
                                        @endforeach
                                    </select>

                                    @error('group_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-end">{{ __('Title') }}</label>

                                <div class="col-md-6">
                                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ $task->title }}" required autofocus>

                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-end">{{ __('Description') }}</label>

                                <div class="col-md-6">
                                    <input id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{ $task->description}}">

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="start" class="col-md-4 col-form-label text-md-end">{{ __('Start') }}</label>

                                <div class="col-md-6">
                                    <input id="start" type="datetime-local" class="form-control @error('start') is-invalid @enderror" name="start" value="{{ $task->start }}">

                                    @error('start')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="end" class="col-md-4 col-form-label text-md-end">{{ __('End') }}</label>

                                <div class="col-md-6">
                                    <input id="end" type="datetime-local" class="form-control @error('end') is-invalid @enderror" name="end" value="{{ $task->end }}">

                                    @error('end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
