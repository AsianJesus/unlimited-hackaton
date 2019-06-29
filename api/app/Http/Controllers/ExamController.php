<?php

namespace App\Http\Controllers;

use App\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $exam;

    public function __construct(Exam $model)
    {
        parent::__construct($model, []);

        $this->exam = $model;
    }

    public function getById($id)
    {
        return $this->exam::where('id', $id)->with('questions')->firstOrFail();
    }
}
