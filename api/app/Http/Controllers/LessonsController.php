<?php

namespace App\Http\Controllers;

use App\Helper\Helper;
use App\Lesson;
use App\UserLesson;
use App\UserPointChange;
use App\UserWeekNorm;
use App\User;

class LessonsController extends Controller
{
    protected $lesson;

    public function __construct(Lesson $model)
    {
        parent::__construct($model, []);

        $this->lesson = $model;
    }

    public function pass($id) {
        $user_id = auth()->id();
        $user = User::findOrFail($user_id);
        $user_lesson = UserLesson::where('user_id', $user_id)->where('lesson_id', $id)->first();
        if ($user_lesson != null) {
            return response('Lesson is already passed', 405);
        }
        $user_lesson = UserLesson::create([ 'user_id' => $user_id, 'lesson_id' => $id ]);
        $points_change = UserPointChange::where('user_id', $user_id)->where('date', date('Y-m-d'))->first();
        if ($points_change == null) {
            UserPointChange::create([ 'user_id' => $user_id, 'date' => date('Y-m-d'), 'points' => 1]);
        } else {
            $points_change->points += 1;
            $points_change->save();
        }
        $user->points++;
        $user->save();
        $week = Helper::getWeek();
        $weekly = UserWeekNorm::where('user_id', $user_id)->where('week', $week)->first();
        if ($weekly == null) {
            UserWeekNorm::create([ 'user_id' => $user_id, 'week' => $week, 'points' => 1 ]);
        } else {
            $weekly->points++;
            $weekly->save();
        }
        return $user_lesson;
    }
}
