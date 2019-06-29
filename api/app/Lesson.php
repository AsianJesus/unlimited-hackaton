<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'name', 'type', 'link', 'content', 'course_id', 'required_time'
    ];

    protected $withCount = [
        'my_passes'
    ];

    public function users() {
        return $this->hasMany(UserLesson::class);
    }

    public function my_passes() {
        $my_id = auth()->id();
        return $this->hasMany(UserLesson::class)->where('user_id', $my_id);
    }
}
