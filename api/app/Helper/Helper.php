<?php


namespace App\Helper;


class Helper
{
    static public function getWeek() {
        return floor((time() / 86400- 4) / 7);
    }
}
