<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserWeekNorm extends Model
{
    protected $fillable = [
        'user_id', 'week', 'points', 'failed'
    ];
}
