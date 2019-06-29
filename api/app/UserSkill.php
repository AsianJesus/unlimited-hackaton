<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserSkill extends Model
{
    protected $fillable = [
      'user_id', 'skill_id', 'level'
    ];

    public function skill() {
        return $this->belongsTo(Skill::class);
    }
}
