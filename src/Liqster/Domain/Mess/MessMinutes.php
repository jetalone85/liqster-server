<?php

namespace Liqster\Domain\Mess;

/**
 * Class MessMinutes
 * @package Liqster\Domain\Mess
 */
/**
 * Class MessMinutes
 * @package Liqster\Domain\Mess
 */
class MessMinutes extends Mess
{
    /**
     * @param $entry
     * @return string
     */
    public static function messEntry($entry): string
    {
        $minutes = self::getMinutes($entry);

        if ($minutes[0] === '*') {
            $minutes = (new \DateTime('now'))->format('i');
        }

        $newMinutes = self::addMessValue($minutes);
        return self::composer($entry, $newMinutes);
    }

    /**
     * @param $entry
     * @return mixed
     */
    public static function getMinutes($entry)
    {
        return explode(' ', $entry)[0];
    }

    /**
     * @param $value
     * @return int
     */
    public static function addMessValue($value): int
    {
        $messValue = random_int(1, 5);
        $newValue = (int)$value + $messValue;

        if ($newValue > 59) {
            $newValue -= 59;
        }
        return $newValue;
    }

    /**
     * @param $entry
     * @param $minutes
     * @return string
     */
    public static function composer($entry, $minutes): string
    {
        $array = explode(' ', $entry);
        $array[0] = $minutes;
        return implode(' ', $array);
    }
}
