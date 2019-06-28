<?php

namespace App\Http\Controllers;

use App\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    public function __construct(Language $model)
    {
        parent::__construct($model, []);
    }
}
