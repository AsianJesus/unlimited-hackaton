<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function __construct(User $model)
    {
        parent::__construct($model, []);
    }

    public function sendMessage(Request $request, $id) {
        $user_id = auth()->id();
        if (DB::table('chats')->where(function ($w) use ($user_id, $id) {
            $w->where('first', $user_id)
                ->where('second', $id);
        })->where(function ($w) use ($user_id, $id) {
            $w->where('first', $id)
                ->where('second', $user_id);
        })->count() == 0) {
            DB::table('chats')->insert([ 'first' => $user_id, 'second' => $id ]);
        }
        $text = $request->input('message', '');
        $message = [
            'from' => $user_id,
            'to' => $id,
            'message' => $text,
            'created_at' => date('Y-m-d H:i:s')
        ];
        DB::table('messages')->insert($message);
        return $message;
    }

    public function getChats (Request $request) {
        $user_id = auth()->id();
        $ids =DB::table('chats')->where('first', $user_id)->pluck('second')->toArray();
        $ids =array_merge(DB::table('chats')
            ->where('second', $user_id)->pluck('first')->toArray(), $ids);
        return array_map(function ($u) use ($user_id){
            $id = $u['id'];
            $last_message = DB::table('messages')->where(function ($w) use ($user_id, $id) {
                $w->where('from', $user_id)
                    ->where('to', $id);
            })->orWhere(function ($w) use ($user_id, $id) {
                $w->where('from', $id)
                    ->where('to', $user_id);
            })->orderBy('created_at', 'desc')->first();
            $u['last_message'] = $last_message != null ? $last_message->message : null;
            return $u;
        }, User::whereIn('id', $ids)->get()->toArray());
    }

    public function getMessages (Request $request, $id) {
        $user_id = auth()->id();
        return DB::table('messages')->where(function ($w) use ($user_id, $id) {
            $w->where('from', $user_id)
                ->where('to', $id);
        })->orWhere(function ($w) use ($user_id, $id) {
            $w->where('from', $id)
                ->where('to', $user_id);
        })->orderBy('created_at', 'asc')->get();
    }
}
