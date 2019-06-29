<?php

namespace App\Http\Controllers;

use App\Course;
use App\UserCourse;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    protected $course;

    public function __construct(Course $model)
    {
        parent::__construct($model, []);

        $this->course = $model;
    }

    public function getById($id)
    {
        return $this->course::where('id', $id)
            ->withCount([ 'users', 'failed_users', 'completed_users' ])
            ->with([ 'lessons', 'exams' ])
            ->first();
    }

    public function checkIfGoing ($id) {
        return json_encode(UserCourse::where('user_id', auth()->id())->where('course_id', $id)->first() != null);
    }

    public function getDashboard ($id) {
        return $this->course::where('id', $id)
            ->with('completed_users')
            ->first();
    }

    public function join($id) {
        $user_id = auth()->id();
        $userCourse = UserCourse::where('user_id', $user_id)->where('course_id', $id)->first();
        if ($userCourse != null && $userCourse->has_passed != false) {
            return response('You already joined this course', 405);
        }
        if ($userCourse == null) {
            return UserCourse::create([
                'user_id' => $user_id,
                'course_id' => $id
            ]);
        } else {
            $userCourse->has_passed = null;
            $userCourse->save();
            return $userCourse;
        }
    }

    public function exit($id) {
        $user = auth()->user();
        $course = UserCourse::where('user_id', $user->id)->where('course_id', $id)->first();
        if ($course->has_passed != null) {
            return response('You cannot exit course', 405);
        }
        $course->has_passed = false;
        $course->save();
        return null;
    }
}
