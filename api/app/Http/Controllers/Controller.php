<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    private $model;
    private $rules;

    public function __construct($model, $rules)
    {
        $this->model = $model;
        $this->rules = $rules;
    }

    public function add(Request $request)
    {
//        $this->validate($request, $this->rules);
        $lastInsertedRow = $this->model::create($request->all());
        return $lastInsertedRow;
    }

    public function update(Request $request, $id)
    {
//        $this->validate($request, $this->rules);
        $update = $this->model::findOrFail($id);
        $result = $update->fill($request->all())->save();
        return response()->json($result);
    }

    public function delete(Request $request, $id)
    {
        $deleted = $this->model::destroy($id);
        return $deleted;
    }

    public function getAll()
    {
        return $this->model::all();
    }

    public function getById($id)
    {
        return $this->model::where('id', $id)->first();
    }

    public function updateMany(Request $request) {
        $result = 0;
        foreach($request->all() as $id => $value) {
            $result += $this->model::where('id', $id)->update($value);
        }
        return $result;
    }
}
