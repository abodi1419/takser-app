@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>{{__('My groups')}}</h3>
        @php
        $index=1;
        @endphp
        @include('groups.__groups_table')
        <h3>{{__('Shared groups')}}</h3>
        @php
            $groups = $sharedGroups;
            $index=2;
        @endphp
        @include('groups.__groups_table')
    </div>
@endsection
