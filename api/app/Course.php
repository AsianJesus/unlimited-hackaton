<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'speciality_id', 'difficulty', 'description', 'level'
    ];

    protected $withCount = [ 'lessons', 'exams' ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function exams() {
        return $this->hasMany(Exam::class);
    }

    public function users() {
        return $this->hasMany(UserCourse::class);
    }

    public function failed_users() {
        return $this->users()->where('has_passed', false);
    }
    public function completed_users() {
        return $this->users()->where('has_passed', true);
    }
}
