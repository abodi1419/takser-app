<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    protected $fillable = ['name','description', 'user_id'];

    public function tasks(){
        return $this->hasMany(Task::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'users_groups_relation','group_id','user_id')->withPivot('status');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function emails(){
        return $this->hasMany(EmailGroup::class);
    }
}
