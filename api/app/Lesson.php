<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'name', 'type', 'link', 'content', 'course_id', 'required_time'
    ];


}
