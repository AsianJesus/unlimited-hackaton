<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserExam extends Model
{
    protected $fillable = [
        'user_id', 'exam_id', 'score', 'is_finished'
    ];
}
