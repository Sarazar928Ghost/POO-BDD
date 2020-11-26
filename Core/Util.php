<?php
namespace App\Core;

abstract class Util
{
    public static function getRefererOrRacine()
    {
        $location = 'Location: ';
        $location .= isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/';
        return $location;
    }
}