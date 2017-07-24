<?php

namespace Liqster\Domain\Sleep;

use PHPUnit\Framework\TestCase;

/**
 * Class SleepTest
 * @package Liqster\Domain\Sleep
 */
class SleepTest extends TestCase
{
    public function testCalculateRangeUp()
    {
        $base = 10;
        $range = 0.4;

        $result = Sleep::calculateRangeUp($base, $range);
        $this->assertGreaterThan($base, $result);
    }
}
