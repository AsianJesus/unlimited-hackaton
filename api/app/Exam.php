<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = [
        'lesson_id', 'course_id'
    ];

    protected $with = [ 'my_passes' ];

    public function skills() {
        return $this->hasManyThrough(Skill::class, ExamSkill::class, 'exam_id', 'id', 'id', 'skill_id');
    }

    public function questions() {
        return $this->hasMany(Question::class);
    }

    public function my_passes() {
        return $this->hasMany(UserExam::class)->where('user_id', auth()->id());
    }

    public function passes() {
        return $this->hasMany(UserExam::class);
    }

}
