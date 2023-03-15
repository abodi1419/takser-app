<?php
namespace App\Services;

use App\Jobs\SendInvitationEmailJob;
use App\Models\Group;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NotificationChannels\Telegram\TelegramMessage;
use function Symfony\Component\String\u;

class GroupService{

    // Store group
    public function store(Request $request){
        $validatedData = $request->validate([
            'name'=>'string|min:3|max:255|required',
            'description' => 'string|nullable'
        ]);
        Auth::user()->groups()->create($validatedData);
    }

    // Update group
    public function update(Request $request, Group $group){
        $validatedData = $request->validate([
            'name'=>'string|min:3|max:255|required',
            'description' => 'string|nullable'
        ]);
        $group->name = $validatedData['name'];
        $group->description = $validatedData['description'];
        $group->save();
    }

    // Add participants to group
    public function addParticipant(Request $request, Group $group){
        $request->validate(['email'=>'email|required']);
        if($group->emails()->where('email',$request->email)->first()!=null){
            return false;
        }
        if($group->users()->where('email',$request->email)->first()!=null){
            return  false;
        }

        $job = new SendInvitationEmailJob(Auth::user()->name,$group->name,$group->id,$request->email);
        $job->delay(Carbon::now()->addSeconds(5));
        dispatch($job);
        $group->emails()->create(['email'=>$request->email]);
        $this->sendMessage(Auth::user(), "<strong>New invite</strong> \n you have invited \n".$request->email."\n to ".$group->name);
        return true;
    }

    // Accept group participation
    public function accepGroupInvitation(Group $group){
        DB::transaction(function() use ($group) {
            $group->emails()->where('email',Auth::user()->email)->delete();
            $group->users()->attach(Auth::user(),['status'=>'1']);
        });
        $this->sendMessage($group->user, "<strong>New participatnt</strong> \n User with name \n".Auth::user()->name."\n and email \n".Auth::user()->email."\n has joined ".$group->name);

    }

    // Reject group participation
    public function rejectGroupInvitation(Group $group){
        DB::transaction(function() use ($group) {
            $group->emails()->where('email',Auth::user()->email)->delete();
            $group->users()->attach(Auth::user(),['status'=>'2']);
        });
        $this->sendMessage($group->user, "<strong>User reject</strong> \n User with name \n".Auth::user()->name."\n and email \n".Auth::user()->email."\n has rejected invite to ".$group->name);


    }

    public function leaveGroup(Group $group){
        $this->removeUserFromGroup($group,Auth::user());
        $this->sendMessage($group->user, "<strong>User left</strong> \n User with name \n".Auth::user()->name."\n and email \n".Auth::user()->email."\n left from ".$group->name);

    }

    public function kickOut(Group $group, User $user){
        if($group->user_id == Auth::id()){
            $this->removeUserFromGroup($group,$user);
            $this->sendMessage($user, "You were removed from ".$group->name.' by '.Auth::user()->name);

        }
    }

    private function removeUserFromGroup(Group $group, User $user){
        if($group->users()->where('id',$user->id)->first()==null){
            abort(404);
        }
        DB::transaction(function() use ($group,$user) {
            $user->tasksAssigned()->detach($group->tasks()->get('id'));
            $group->users()->detach($user);
            $group->users()->detach($user);
        });
    }

    // Return auth user groups
    public function getUserGroups(){
        return Auth::user()->groups;
    }

    // Return auth user shared groups
    public function getActiveSharedGroups(){
        return Auth::user()->groupsBelongTo()->wherePivot('status',1)->get(['id','name','description']);

    }

    // Return group tasks
    public function getGroupTasks(Group $group){
        return $group->tasks()->orderBy("start")->get();
    }


    public function getGroupUsers(Group $group){
        return $group->users()->wherePivot('status',1)->get();
    }

    public function authorizeWithShareGete(Group $group){
        if($group->user_id != Auth::id() &&
            $group->users()->wherePivot('status','1')->where('user_id',Auth::id())->first()==null)
        {
            abort(403);
        }
    }

    public function sendMessage(User $user, $message){
        if($user->chat_id!=null){
            TelegramMessage::create($message)->to($user->chat_id)->options(['parse_mode' => 'HTML'])->send();
        }
    }

    public function authorizeGate(Group $group){
        if($group->user_id != Auth::id()){
            abort(403);
        }
    }

    public function confirmInvitation(Group $group){
        if($group->emails()->where('email',Auth::user()->email)->first()==null){
            abort(403, "You have no invitation to this group");
        }
    }


}
