<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name', 'speciality_id', 'difficulty', 'description'
    ];

    public function lessons() {
        return $this->hasMany(Lesson::class);
    }

    public function exams() {
        return $this->hasMany(Exam::class);
    }
}
