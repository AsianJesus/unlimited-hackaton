<?php

namespace App\Http\Controllers;

use App\Field;
use Illuminate\Http\Request;

class FieldsController extends Controller
{
    protected $field;
    public function __construct(Field $model)
    {
        parent::__construct($model, []);
        $this->field = $model;
    }

    public function getByName($name) {
        return $this->field::where('name', 'LIKE', $name)
            ->with('specialities')
            ->first();
    }
}
