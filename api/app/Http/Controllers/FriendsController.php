<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FriendsController extends Controller
{

    public function __construct(User $model)
    {
        parent::__construct($model, []);
    }

    public function addFriend($id)
    {
        $user_id = auth()->id();
        if (DB::table('friends')->where('first_id', $user_id)->where('second_id', $id)->count() == 0) {
            return json_encode(DB::table('friends')->insert(['first_id' => $user_id, 'second_id' => $id]));
        } else {
            return response('He is already a friend', 405);
        }
    }

    public function deleteFriend($id) {
        $user_id = auth()->id();
        return DB::table('friends')->where(function ($w) use ($user_id, $id) {
            $w->where('first_id', $user_id)
                ->where('second_id', $id);
        })->orWhere(function ($w) use ($user_id, $id) {
            $w->where('first_id', $id)
                ->where('second_id', $user_id);
        })->delete();
    }

    public function getFriends() {
        $user_id = auth()->id();
        $result = DB::table('friends')->where('first_id', $user_id)->pluck('second_id')->toArray();
        $result = array_merge(DB::table('friends')->orWhere('second_id', $user_id)->pluck('first_id')->toArray(), $result);
        $users = User::whereIn('id', $result)->get()->toArray();
        return $users;
    }

}
