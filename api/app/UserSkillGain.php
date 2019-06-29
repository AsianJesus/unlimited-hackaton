<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkillGain extends Model
{
    protected $fillable = [
        'user_id', 'skill_id', 'date'
    ];
}
