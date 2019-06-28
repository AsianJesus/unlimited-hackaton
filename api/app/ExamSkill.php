<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExamSkill extends Model
{
    protected $fillable = [
        'exam_id', 'skill_id'
    ];
}
