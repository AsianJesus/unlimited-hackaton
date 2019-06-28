<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Speciality extends Model
{
    protected $fillable = [
        'name', 'field_id'
    ];

    protected $table = 'specialities';

    public function courses() {
        return $this->hasMany(Course::class);
    }
}
