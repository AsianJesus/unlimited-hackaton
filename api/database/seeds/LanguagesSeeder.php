<?php

use Illuminate\Database\Seeder;

class LanguagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    const LANGUAGES = [
        'Azərbaycan',
        'İngilis dili',
        'Alman dili',
        'Rus dili'
    ];

    public function run()
    {
        foreach (self::LANGUAGES as $language) {
            \App\Language::create([ 'name' => $language ]);
        }
    }
}
