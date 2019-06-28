<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'lesson_id', 'course_id'
    ];

    public function skills() {
        return $this->hasManyThrough(Skill::class, ExamSkill::class, 'exam_id', 'id', 'id', 'skill_id');
    }
}
