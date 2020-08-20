<?php

namespace App\Lib;

class Util
{
    public static function queryTime($start, $end, $field, $trans = true)
    {
        if ($trans) {
            $start = $start ? strtotime($start) : 0;
            $end = $end ? strtotime($end) : 0;
        }
        $queryTime = [];
        if (!$start && !$end) {
            return false;
        }
        $temp = 0;
        if ($start > $end && $end !== 0) {
            $temp = $start;
            $start = $end;
            $end = $temp;
        }
        if ($start === 0) {
            $queryTime = [$field, '<', $end];
        } else if ($end === 0) {
            $queryTime = [$field, '>', $start];
        } else {
            $queryTime = [$field, 'between', [$start, $end]];
        }
        return $queryTime;
    }
}