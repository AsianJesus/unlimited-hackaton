<?php

namespace App\Http\Controllers;

use App\Lesson;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    protected $lesson;

    public function __construct(Lesson $model)
    {
        parent::__construct($model, []);

        $this->lesson = $model;
    }
}
