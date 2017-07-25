<?php

namespace Liqster\Domain\Sleep;

/**
 * Class Sleep
 * @package Liqster\Domain\Sleep
 */
class Sleep
{
    /**
     * @param $int
     * @param float $range
     */
    public static function random($int, $range = 0.4)
    {
        sleep(random_int(self::calculateRangeDown($int, $range), self::calculateRangeUp($int, $range)));
    }

    /**
     * @param $int
     */
    public static function hard($int)
    {
        sleep($int);
    }

    /**
     * @param int $base
     * @param float $range
     * @return int
     */
    public static function calculateRangeDown(int $base, float $range): int
    {
        return (int)($base - ($base * $range));
    }

    /**
     * @param int $base
     * @param float $range
     * @return int
     */
    public static function calculateRangeUp(int $base, float $range): int
    {
        return (int)($base + ($base * $range));
    }
}
