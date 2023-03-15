<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'status',
        'progress',
        'group_id',
        'user_id'
    ];

    public function group(){
        return $this->belongsTo(Group::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'users_tasks_relation','task_id','user_id')->withPivot("status");
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }
}
