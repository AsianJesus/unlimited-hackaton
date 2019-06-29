<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLesson extends Model
{
    protected $fillable = [
        'lesson_id', 'user_id'
    ];

    public function lesson() {
        return $this->belongsTo(Lesson::class);
    }
}
