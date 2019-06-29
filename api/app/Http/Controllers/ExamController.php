<?php

namespace App\Http\Controllers;

use App\Course;
use App\Exam;
use App\UserCourse;
use App\UserExam;
use App\UserSkill;
use App\UserSkillGain;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    protected $exam;

    public function __construct(Exam $model)
    {
        parent::__construct($model, []);

        $this->exam = $model;
    }

    public function getById($id)
    {
        return $this->exam::where('id', $id)->with('questions')->firstOrFail();
    }

    public function pass(Request $request, $id) {
        $score = $request->input('score', 0);
        $exam = $this->exam::where('id', $id)->first();
        $user_id = auth()->id();
        $is_successful = $score >= 90;
        $exam->passes()->create([ 'user_id' => $user_id, 'score' => $score, 'is_finished' => $is_successful]);

        if ($is_successful) {
            $skills = $exam->skills()->get();
            foreach ($skills as $skill) {
                $user_skill = UserSkill::where('user_id', $user_id)->where('skill_id', $skill->id)->first();
                if ($user_skill == null) {
                    UserSkill::create([ 'user_id' => $user_id, 'skill_id' => $skill->id]);
                } else {
                    $user_skill->level++;
                    $user_skill->save();
                }
                UserSkillGain::create([ 'user_id' => $user_id, 'skill_id' => $skill->id, 'date' => date('Y-m-d')]);
            }

            $course = Course::where('id', $exam->course_id)->first();

            $exam_ids = array_map(function ($e) { return $e['exam_id']; }, UserExam::where('user_id', $user_id)->whereHas('exam', function ($where) use ($course) {
                $where->where('course_id', $course->id);
            })->where('is_finished', true)->get()->toArray());
            if ($course->exams()->whereNotIn('id', $exam_ids)->count() == 0){
                UserCourse::where('user_id', $user_id)->where('course_id', $course->id)->update([ 'has_passed' => true ]);
            }
        }
    }
}
