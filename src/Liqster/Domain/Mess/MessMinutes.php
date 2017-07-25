<?php

namespace Liqster\Domain\Mess;

/**
 * Class MessMinutes
 * @package Liqster\Domain\Mess
 */
class MessMinutes extends Mess
{
    public static function messEntry($entry)
    {
        $minutes = self::getMinutes($entry);

        if ($minutes[0] === '*') {
            $minutes = (new \DateTime('now'))->format('i');
        }

        $newMinutes = self::addMessValue($minutes);
        return self::composer($entry, $newMinutes);
    }

    public static function getMinutes($entry)
    {
        return explode(' ', $entry)[0];
    }

    public static function addMessValue($value)
    {
        $messValue = random_int(2, 5);
        $newValue = (int)$value + $messValue;

        if ($newValue > 59) {
            $newValue -= 59;
        }
        return $newValue;
    }

    public static function composer($entry, $minutes)
    {
        $array = explode(' ', $entry);
        $array[0] = $minutes;
        return implode(' ', $array);
    }
}
