<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserPointChange extends Model
{
    protected $fillable = [
        'user_id', 'points', 'date'
    ];
}
