<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserNorm extends Model
{
    protected $fillable = [
        'user_id', 'points'
    ];
}
