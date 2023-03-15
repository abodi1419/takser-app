@extends('layouts.app')

@section('content')
    <div class="container">
        <select class="form-select" id="selectType" onchange="changeType(this)">
            <option>{{__("Created tasks")}}</option>
            <option>{{__("Todo tasks")}}</option>
            <option>{{__("Completed tasks")}}</option>
        </select>
        <div id="table-div">

        </div>
    </div>
    <script>
        if(document.location.search){
            let selectOption = document.getElementById("selectType");
            selectOption.selectedIndex = document.location.search.split("=")[1];
            changeType(selectOption)
        }else{
            window.history.replaceState(null, null, "?type="+0);
            let selectOption = document.getElementById("selectType");
            selectOption.selectedIndex = document.location.search.split("=")[1];
            changeType(selectOption);
        }
        function changeType(e){
            let tableDiv = document.getElementById("table-div")
            if(e.selectedIndex==0){
                console.log("1")
                window.history.replaceState(null, null, "?type="+0);
                @php
                    $tableTasks = $tasks;
                    $index=1;
                @endphp
                    tableDiv.innerHTML = `
                    @include("tasks.__index_table")
                `;
            }else if(e.selectedIndex==1){
                window.history.replaceState(null, null, "?type="+1);
                @php
                    $index=2;
                    $tableTasks = $todoTasks;
                @endphp
                    tableDiv.innerHTML = `
                    @include("tasks.__index_table")
                `;
            }else if(e.selectedIndex==2){
                window.history.replaceState(null, null, "?type="+2);
                @php
                    $index=3;
                    $tableTasks = $doneTasks;
                @endphp
                    tableDiv.innerHTML = `
                    @include("tasks.__index_table")
                `;
            }

        }
    </script>
@endsection
