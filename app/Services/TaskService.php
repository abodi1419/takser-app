<?php
namespace App\Services;

use App\Models\Group;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TaskService{

    public function store(Request $request)
    {
        $request->validate([
            'start'=>'date_format:Y-m-d\TH:i|required',
            'end'=>'date_format:Y-m-d\TH:i|required'
        ]);
        $start = str_replace('T',' ',$request->start);
        $end = str_replace('T',' ',$request->end);
        $request['start']= $start;
        $request['end']= $end;
        $validatedData = $request->validate([
            'title'=>'string|required',
            'description' => 'string|nullable',
            'start'=>'before:end|after:yesterday',
            'end'=>'after:start',
        ]);
        if($request->group_id!=null){
            $group = Group::find($request->group_id);
            if($group!=null){
                if(Auth::id() != $group->user_id && $group->users()->where('user_id',Auth::id())->first()==null){
                    abort(403, "You don't own this resource");
                }
                $validatedData['group_id']=$group->id;
            }else{
                abort("404","Group not found");
            }
        }
        $validatedData['status']=1;
        $validatedData['progress']=0;
        Auth::user()->tasksCreated()->create($validatedData);
    }

    public function update(Request $request, Task $task){
        $request->validate([
            'start'=>'date_format:Y-m-d\TH:i|required',
            'end'=>'date_format:Y-m-d\TH:i|required'
        ]);
        $start = str_replace('T',' ',$request->start);
        $end = str_replace('T',' ',$request->end);
        $request['start']= $start;
        $request['end']= $end;
        $validatedData = $request->validate([
            'title'=>'string|required',
            'description' => 'string|nullable',
            'start'=>'before:end',
            'end'=>'after:start',
        ]);
        if($request->group_id!=null){
            $group = Group::find($request->group_id);
            if($group!=null){
                if(Auth::id() != $group->user_id && $group->users()->where('user_id',Auth::id())->first()==null){
                    abort(403, "You don't own this resource");
                }
                $validatedData['group_id']=$group->id;
            }else{
                abort("404","Group not found");
            }
        }
//        $validatedData['status']=1;
//        $validatedData['progress']=0;
        $task->update($validatedData);
    }
    public function getUserTasks()
    {
        return Auth::user()->tasksCreated;
    }

    public function getTodoTasks(){
        return Auth::user()->tasksAssigned()->wherePivot("status",0)->get();
    }
    public function getDoneTasks(){
        return Auth::user()->tasksAssigned()->wherePivot("status",1)->get();
    }

    public function selectTask(\App\Models\Task $task)
    {
        if(Auth::user()->tasksAssigned()->where("id",$task->id)->first()!=null){
            return false;
        }
        DB::transaction(function () use ($task){
            Auth::user()->tasksAssigned()->attach($task);
            $task->progress = $this->calcProgress($task, 0);
            $task->status = $this->calcStatus($task);
            $task->save();
        });
        return true;
    }

    public function getTaskUsers(\App\Models\Task $task)
    {
        return $task->users;
    }

    public function deselectTask(\App\Models\Task $task)
    {
        if(Auth::user()->tasksAssigned()->where("id",$task->id)->first()!=null){
            DB::transaction(function () use ($task){
                Auth::user()->tasksAssigned()->detach($task);
                $progress=$this->calcProgress($task,0);
                if($task->progress != $progress){
                    $task->progress = $progress;
                    $task->status = $this->calcStatus($task);
                    $task->save();
                }
            });
            return true;
        }
        return false;
    }

    public function authorizeGate(\App\Models\Task $task)
    {
        if(Auth::id()!=$task->id){
            abort(403);
        }
    }

    public function complete(Task $task){
        $progress = $this->calcProgress($task,1);
        $task->progress = $progress;
        $task->status = $this->calcStatus($task);
        DB::transaction(function () use ($task) {
            Auth::user()->tasksAssigned()->updateExistingPivot($task->id, array('status' => '1'), false);
            $task->save();
        });

    }

    public function authorizeWithShareGete(Task $task)
    {

        if($task->user_id != Auth::id() && $task->group->users()->where('id',Auth::id())->first()==null){
            abort(404);
        }
    }

    public function uncomplete(Task $task)
    {
        $progress =$task->progress;
        if(Auth::user()->isTaskCompleted($task)==null){
            abort(404);
        }else{
            $progress = $this->calcProgress($task,-1);
        }

        $task->progress = $progress;
        $task->status = $this->calcStatus($task);
        DB::transaction(function () use ($task) {
            Auth::user()->tasksAssigned()->updateExistingPivot($task->id, array('status' => '0'), false);
            $task->save();
        });
    }


    private function calcProgress(Task $task,$value)
    {
        $usersCount = $task->users()->count();
        $usersFinished = $task->users()->wherePivot('status','=',1)->count();
        if($value==1 && $usersFinished>=$usersCount){
            return 100;
        }else {
            $usersFinished += $value;
        }
        if($usersFinished==0){
            return 0;
        }
        return 100/$usersCount*$usersFinished;
    }

    private function calcStatus(Task $task)
    {
        $status = 1;
        if(strtotime($task->start)<now()->getTimestamp()){
            $status = 2;
        }else{
            $status = 1;
        }

        if($task->progress == 100){
            $status = 3;
        }
        return $status;
    }
}
