<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    private TaskService $taskService;
    public function __construct(TaskService $taskService){
        $this->middleware('auth');
        $this->taskService = $taskService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = $this->taskService->getUserTasks();
        $todoTasks = $this->taskService->getTodoTasks();
        $doneTasks = $this->taskService->getDoneTasks();
        return view('tasks.index',compact('tasks','todoTasks','doneTasks'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Auth::user()->groups;
        return view('tasks.create',compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        dd($request->all());
        $this->taskService->store($request);

        return redirect()->back()->with(['message'=>'Created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {

        $this->taskService->authorizeWithShareGete($task);
        $users = $this->taskService->getTaskUsers($task);
        return view('tasks.view',compact('task','users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        $this->taskService->authorizeGate($task);
        if($task->status == 3){
            redirect()->back()->withErrors(['message'=>"This task is already done!"]);
        }
        $groups = Auth::user()->groups;
        return view("tasks.edit",compact('task','groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Task $task)
    {
        $this->taskService->authorizeGate($task);
        if($task->status == 3){
            redirect()->back()->withErrors(['message'=>"This task is already done!"]);
        }
        //
        $this->taskService->update($request,$task);
        return redirect()->back()->with(['message'=>'Updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        //
    }

    public function selectTask(Task $task){
        $selected = $this->taskService->selectTask($task);
        if(!$selected){
            return redirect()->back()->withErrors(['message'=>'You can not select this task']);
        }
        return redirect()->back()->with(['message'=>'Task selected']);
    }

    public function deselectTask(Task $task){
        $selected = $this->taskService->deselectTask($task);
        if(!$selected){
            return redirect()->back()->withErrors(['message'=>'You can not deselect this task']);

        }
        return redirect()->back()->with(['message'=>'Task deselected']);
    }

    public function complete(Task $task){
        $this->taskService->authorizeWithShareGete($task);
        $this->taskService->complete($task);
        return redirect()->back()->with(['message'=>"Updated successfully"]);
    }
    public function uncomplete(Task $task){
        $this->taskService->authorizeWithShareGete($task);
        $this->taskService->uncomplete($task);
        return redirect()->back()->with(['message'=>"Updated successfully"]);
    }
}
