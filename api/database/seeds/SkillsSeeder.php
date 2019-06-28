<?php

use Illuminate\Database\Seeder;

class SkillsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    const SKILLS = [
        'IT',
        'Design',
        'System Administrator',
        'Management',
        'Finance'
    ];

    public function run()
    {
        foreach (self::SKILLS as $skill) {
            \App\Skill::create([ 'name' => $skill ]);
        }
    }
}
