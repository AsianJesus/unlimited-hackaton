<?php

use App\Course;
use App\Helper\Helper;
use App\UserCourse;
use App\UserExam;
use App\UserLesson;
use App\UserPointChange;
use App\UserSkill;
use App\UserSkillGain;
use App\UserWeekNorm;
use Illuminate\Database\Seeder;

class FieldsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    const FIELDS = [
        'IT' => [
            'desc' => 'İnformasiya texnologiyası (İT) – informasiya ehtiyatlarından istifadə olunması proseslərinin ağırlığını azaltmaq, onların etibarlığını və operativliyini çoxaltmaq məqsədilə informasiyanın toplanması, emalı, saxlanması, ötürülməsini təmin edən və texnoloji zəncirdə birləşdirən metodlar, istehsal prosesləri və texniki-proqram vasitələri toplusudur.',
            'specs' => [
                'System Administrator',
                'Developer',
                'Dev OPs'
            ]
        ],
        'Marketing' => [
            'desc' => 'Marketinq işləri və strategiyası istehsalçı şirkətlərə gəlir verməklə bərabər istehlakçıları razı salacaq məhsul istehsalı ilə nəticələnən prosesdir. ',
            'specs' => [
                'SMM'
            ]
        ],
        'Dillər' => [
            'desc' => 'Xarici dillərin tədrisi digər humanitar fənlərin tədrisindən özünün spesifikliyi ilə seçilir.',
            'specs' => [
                "İngilis dili",
                "Rus dili",
                "Alman dili",
                "Yunan dili"
            ]
        ],
        'İncəsənət' => [
            'desc' => 'Incəsənət — insan hisslərinin təsvir edən vasitədir. Həmçinin istənilən fəaliyyət sahəsində insanın göstərdiyi yüksək.   --- Dizayn — sənaye məhsullarının formasını müəyyənləşdirmək üçün edilən yaradıcı fəaliyyətdir. Bura həm xarici görünüş, həm struktur və funksional cizgilərini müəyyənləşdirmək vəzifələri aiddir bacarıq, məharət də nəzərdə tutulur.',
            'specs' => [
                "Əl işlər"
            ]
        ],
        'Dizayn' => [
            'desc' => 'Ənənəvi yanaşmaya görə, bu cür spesifiklik ilk növbədə xarici dil dərslərinin yeni məzmun verməkdən daha çox mövcud fikirləri yeni işarələr sisteminə çevirməyə xidmət etməsindən irəli gəlir',
            'specs' => [
                "Veb Dizayn",
                "İnteryer Dizayneri",
                "Poliqrafik Dizayn",
                "Arxitektur Dizayn"
            ]
        ]
    ];
    public function run()
    {
        /*
         * Users lessons seeding goes the following way:
         *  10 users are choosed for the course
         *  the course is done within 7 days
         *  every day students pass one lesson or exam
         *  at exam, only 75% of students pass
         *  Failed students fails the course
         */
        $skills = \App\Skill::all()->toArray();

        $users = \App\User::all()->pluck('id')->toArray();

        $start_date = new DateTime();
        $start_date->modify("-7 day");
        foreach(self::FIELDS as $field => $info) {
            $field = \App\Field::create([ 'name' => $field, 'description' => $info['desc'] ]);
            $specialities = $info['specs'];
            foreach ($specialities as $speciality) {
                $speciality = $field->specialities()->create([ 'name' => $speciality ]);
                foreach (factory(\App\Course::class, 2)->make() as $course) {
                    $course = $speciality->courses()->create($course->toArray());
                    echo "Started seeding course #".$course->id."\n";
                    // Generate lessons
                    shuffle($users);
                    $students = array_slice($users, 0, 10);

                    $lessons = [];

                    foreach (factory(\App\Lesson::class, 5)->make() as $lesson) {
                        $lesson = $course->lessons()->create($lesson->toArray());
                        array_push($lessons, $lesson->toArray());
                    }
                    $exams = [];
                    // Generate exams
                    for ($index = 1; $index <= 2; $index++) {
                        $exam = $course->exams()->create([ 'lesson_id' => $index * 2]);
                        array_push($exams, $exam);
                        // Create questions for exam
                        foreach(factory(\App\Question::class, 10)->make() as $question) {
                            $exam->questions()->create($question->toArray());
                        }

                        \App\ExamSkill::create(['exam_id' => $exam->id, 'skill_id' => $skills[rand(0, sizeof($skills) - 1)]['id']]);
                    }

                    // Populate course with users
                    foreach ($students as $user_id) {
                        UserCourse::create([
                            'user_id' => $user_id,
                            'course_id' => $course->id
                        ]);
                    }

                    $lessons_count = 0;
                    $date = clone $start_date;
                    while(!empty($students) && (!empty($lessons) || !(empty($exams)))) {
                            if (empty($exams)) {
                                $this->passLesson($students, $lessons[0], $date->format('Y-m-d'));
                                array_splice($lessons, 0, 1);
                            } else
                            if (empty($lessons)) {
                                $students = $this->passExam($students, $exams[0], $date->format('Y-m-d'));
                                array_splice($exams, 0, 1);
                            } else {
                                $exam = $exams[0];
                                $lesson = $lessons[0];
                                if ($exam->lesson_id <= $lessons_count) {
                                    $students = $this->passExam($students, $exam, $date->format('Y-m-d'));
                                    array_splice($exams, 0, 1);
                                } else {
                                    $this->passLesson($students, $lesson, $date->format('Y-m-d'));
                                    array_splice($lessons, 0, 1);
                                    $lessons_count++;
                                }
                            }
                            $date->modify("+1 day");
                        }

                        // Finish course for users and fail for whom who hasn't passed
                        UserCourse::where('course_id', $course->id)
                            ->whereIn('user_id', $students)
                            ->update(['has_passed' => true]);
                        UserCourse::where('course_id', $course->id)
                            ->whereNotIn('user_id', $students)
                            ->update(['has_passed' => false]);
                    }

                }
            }
        }

    private function passExam($users, $exam, $date) {
        $result = [];
        foreach ($users as $user_id) {
            $score = rand(0, 100) > 75 ? 50 : 95;
            $is_successful = $score >= 90;
            $exam->passes()->create(['user_id' => $user_id, 'score' => $score, 'is_finished' => $is_successful]);

            if ($is_successful) {
                $skills = $exam->skills()->get();
                foreach ($skills as $skill) {
                    $user_skill = UserSkill::where('user_id', $user_id)->where('skill_id', $skill->id)->first();
                    if ($user_skill == null) {
                        UserSkill::create(['user_id' => $user_id, 'skill_id' => $skill->id]);
                    } else {
                        $user_skill->level++;
                        $user_skill->save();
                    }
                    UserSkillGain::create(['user_id' => $user_id, 'skill_id' => $skill->id, 'date' => $date]);
                }

                $course = Course::where('id', $exam->course_id)->first();

                $exam_ids = array_map(function ($e) {
                    return $e['exam_id'];
                }, UserExam::where('user_id', $user_id)->whereHas('exam', function ($where) use ($course) {
                    $where->where('course_id', $course->id);
                })->where('is_finished', true)->get()->toArray());
                if ($course->exams()->whereNotIn('id', $exam_ids)->count() == 0) {
                    UserCourse::where('user_id', $user_id)->where('course_id', $course->id)->update(['has_passed' => true]);
                }
                array_push($result, $user_id);
            }
        }
        return $result;
    }

    private function passLesson($users, $lesson, $date) {
        foreach ($users as $user_id) {
            UserLesson::create(['user_id' => $user_id, 'lesson_id' => $lesson['id']]);
            $points_change = UserPointChange::where('user_id', $user_id)->where('date', date('Y-m-d'))->first();
            if ($points_change == null) {
                UserPointChange::create(['user_id' => $user_id, 'date' => $date, 'points' => 1]);
            } else {
                $points_change->points += 1;
                $points_change->save();
            }
            $user = \App\User::find($user_id);
            $user->points++;
            $user->save();
        }
    }
}
