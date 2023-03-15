<?php

namespace App\Http\Controllers;

use App\Jobs\SendInvitationEmailJob;
use App\Models\EmailGroup;
use App\Models\Group;
use App\Models\User;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    private GroupService $groupService;

    public function __construct(GroupService $groupService){
        $this->groupService = $groupService;
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = $this->groupService->getUserGroups();
        $sharedGroups = $this->groupService->getActiveSharedGroups();
        return view('groups.index',compact('groups','sharedGroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('groups.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->groupService->store($request);
        return redirect()->back()->with(['message'=>'Created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function show(Group $group)
    {
        $this->groupService->authorizeWithShareGete($group);
        $tasks = $this->groupService->getGroupTasks($group);
        $users = $this->groupService->getGroupUsers($group);
        return view('groups.view',compact('group','tasks','users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function edit(Group $group)
    {
        $this->groupService->authorizeGate($group);
        return view('groups.edit',compact('group'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Group $group)
    {
        $this->groupService->authorizeGate($group);
        $this->groupService->update($request,$group);
        return redirect()->back()->with(['message'=>'Updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Http\Response
     */
    public function destroy(Group $group)
    {
        //
    }

    public function addParticipant(Request $request,Group $group){
        $this->groupService->authorizeGate($group);
        $invited = $this->groupService->addParticipant($request,$group);
        if(!$invited){
            return redirect()->back()->withErrors(['message'=>'Already invited!']);
        }

        return redirect()->back()->with(['message'=>'Invitation sent successfully!']);

    }

    public function joinView(Group $group){
        if(Auth::id()==$group->user_id){
            return redirect()->to(route('groups.show',$group->id));
        }
        $this->groupService->confirmInvitation($group);
        $tasks = $this->groupService->getGroupTasks($group);
        $users = $this->groupService->getGroupUsers($group);
        return view('groups.join',compact('group','tasks','users'));

    }

    public function join(Group $group){
        $this->groupService->confirmInvitation($group);
        $this->groupService->accepGroupInvitation($group);
        return redirect()->to(route('groups.index'))->with(['message'=>'Joined successfully!']);

    }

    public function reject(Group $group){
        $this->groupService->confirmInvitation($group);
        $this->groupService->rejectGroupInvitation($group);
        return redirect()->to(route('groups.index'))->with(['message'=>'Rejected successfully!']);
    }

    public function leave(Group $group){
        $this->groupService->leaveGroup($group);
        return redirect()->to(route('groups.index'))->with(['message'=>'Leaved successfully!']);
    }

    public function kick(User $user,Group $group){
        $this->groupService->kickOut($group,$user);
        return redirect()->back()->with(['message'=>$user->name.' was kicked successfully!']);
    }

}
