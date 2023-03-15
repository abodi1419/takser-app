<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailGroup extends Model
{
    use HasFactory;
    public $timestamps =false;

    protected $fillable = ['email','group_id'];

    public function email(){
        return $this->belongsTo(Group::class);
    }
}
