<?php


namespace App\Helpers;


class DateHelper
{
    public static function applyFormatToDatetime(\DateTime $value, $format = 'Y-m-d H:i:s'){
        return $value->format($format);
    }
}