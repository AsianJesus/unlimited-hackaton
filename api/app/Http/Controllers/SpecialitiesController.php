<?php

namespace App\Http\Controllers;

use App\Speciality;
use Illuminate\Http\Request;

class SpecialitiesController extends Controller
{
    protected $speciality;

    public function __construct(Speciality $model)
    {
        parent::__construct($model, []);
        $this->speciality = $model;
    }

    public function getByName($name) {
        return $this->speciality::where('name', $name)
            ->with([ 'field.specialities', 'courses' ])
            ->first();
    }
}
