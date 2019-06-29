<?php

namespace App\Http\Controllers;

use App\Course;
use App\Helper\Helper;
use App\User;
use App\UserCourse;
use App\UserLesson;
use App\UserNorm;
use App\UserPointChange;
use App\UserSkill;
use App\UserSkillGain;
use App\UserWeekNorm;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected $user;
    public function __construct(User $model)
    {
        parent::__construct($model, []);
        $this->user = $model;
    }


    public function getPointsStatistics(Request $request) {
        $date = new \DateTime();
        $interval = $request->input('interval', 'w');

        $date->modify('-1 '.($interval == 'w' ? 'week' : 'month'));
        $user_id = $request->input('user_id') ?? auth()->id();
        $points_change = UserPointChange::where('date', '>=', $date->format('Y-m-d'))
            ->where('user_id', $user_id)
            ->get()->toArray();
        $keys = [];
        foreach ($points_change as $point_change) {
            $keys[$point_change['date']] = $point_change['points'];
        }
        $result = [];
        $currentDate = new \DateTime();
        while ($date <= $currentDate) {
            $s = $date->format('Y-m-d');
            $result[$s] = $keys[$s] ?? 0;
            $date->modify('+1 day');
        }
        return $result;
    }
    public function getWeekInformation(Request $request) {
        $user_id = $request->input('user_id') ?? auth()->id();
        $user = $this->user::find($user_id);
        $info = UserWeekNorm::where('user_id', $user_id)->where('week', Helper::getWeek())->first();
        return [
            'user' => $user,
            'info' => $info,
            'required' => env('WEEK_NORM', 5)
        ];
    }

    public function getUserSkillsInfo (Request $request) {
        $user_id = $request->input('user_id') ?? auth()->id();
        return UserSkillGain::where('user_id', $user_id)
            ->with('skill')
            ->orderBy('created_at', 'desc')->get();
    }
    public function getUserSkills (Request $request) {
        $user_id = $request->input('user_id') ?? auth()->id();
        return UserSkill::where('user_id', $user_id)
            ->with('skill')->get();
    }

    public function getLastLessons (Request $request) {
        $user_id = $request->input('user_id') ?? auth()->id();
        return UserLesson::where('user_id', $user_id)
            ->with([ 'lesson.course' ])
            ->orderBy('created_at', 'desc')->get();
    }
}
