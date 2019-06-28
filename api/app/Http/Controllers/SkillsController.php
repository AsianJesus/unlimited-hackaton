<?php

namespace App\Http\Controllers;

use App\Skill;
use Illuminate\Http\Request;

class SkillsController extends Controller
{
    public function __construct(Skill $model)
    {
        parent::__construct($model, []);
    }
}
