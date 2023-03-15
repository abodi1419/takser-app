<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'chat_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function tasksAssigned(){
        return $this->belongsToMany(Task::class,'users_tasks_relation','user_id','task_id')->withPivot("status");
    }

    public function tasksCreated(){
        return $this->hasMany(Task::class,'user_id','id');
    }

    public function groups(){
        return $this->hasMany(Group::class,'user_id','id');
    }

    public function groupsBelongTo(){
        return $this->belongsToMany(Group::class,'users_groups_relation','user_id','group_id')->withPivot('status');
    }
    public function isTaskCompleted(Task $task)
    {
        return $this->belongsToMany(Task::class,'users_tasks_relation','user_id','task_id')->wherePivot('status',"=",1)->where('id','=',$task->id)->first();
    }
}
