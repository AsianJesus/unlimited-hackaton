<?php

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
        $skills = \App\Skill::all()->toArray();
        foreach(self::FIELDS as $field => $info) {
            $field = \App\Field::create([ 'name' => $field, 'description' => $info['desc'] ]);
            $specialities = $info['specs'];
            foreach ($specialities as $speciality) {
                $speciality = $field->specialities()->create([ 'name' => $speciality ]);
                foreach (factory(\App\Course::class, 3)->make() as $course) {
                    $course = $speciality->courses()->create($course->toArray());
                    // Generate lessons
                    foreach (factory(\App\Lesson::class, 5)->make() as $lesson) {
                        $course->lessons()->create($lesson->toArray());
                    }
                    // Generate exams
                    for ($index = 1; $index <= 2; $index++) {
                        $exam = $course->exams()->create([ 'lesson_id' => $index * 2]);

                        // Create questions for exam
                        foreach(factory(\App\Question::class, 10)->make() as $question) {
                            $exam->questions()->create($question->toArray());
                        }

                        \App\ExamSkill::create(['exam_id' => $exam->id, 'skill_id' => $skills[rand(0, sizeof($skills) - 1)]['id']]);
                    }

                }
            }
        }
    }
}
