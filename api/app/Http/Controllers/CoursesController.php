<?php

namespace App\Http\Controllers;

use App\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    protected $course;

    public function __construct(Course $model)
    {
        parent::__construct($model, []);

        $this->course = $model;
    }

    public function getById($id)
    {
        return $this->course::where('id', $id)
            ->withCount([ 'users', 'failed_users', 'completed_users' ])
            ->with([ 'lessons' ])
            ->first();
    }

    public function getDashboard ($id) {
        return $this->course::where('id', $id)
            ->with('completed_users')
            ->first();
    }
}
