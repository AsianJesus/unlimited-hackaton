<?php

namespace App\Http\Controllers;

use App\User;
use App\UserLanguage;
use App\UserSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthenticationController extends Controller
{
    protected $user;


    public function __construct(User $model)
    {
        parent::__construct($model, []);
        $this->user = $model;
    }

    public function add(Request $request)
    {
        // $request['password'] = Hash::make($request['password']);
        $user = parent::add($request);
        $token = auth()->login($user);
        foreach ($request->input('languages', []) as $language) {
            UserLanguage::create([ 'language_id' => $language, 'user_id' => $user->id]);
        }
        foreach ($request->input('skills', []) as $skill) {
            UserSkill::create([ 'skill_id' => $skill, 'user_id' => $user->id]);
        }
        return [
            'user' => $this->getById($user->id),
            'token' => $token
        ];
    }

    public function login(Request $request) {
        // return $request->all();
        $mail = $request->input('email', '');
        $password = $request->input('password', '');
        $user = User::where('email', $mail)->where('password', $password)->first();
        // $user = User::where('email', $mail)->where('password', Hash::make($password))->first();
        if ($user == null) {
            return response('No user found', 403);
        }
        $token = auth()->login($user);
        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
