<?php

namespace Liqster\Domain\Mess;

use PHPUnit\Framework\TestCase;

class MessMinutesTest extends TestCase
{
    public function testGetMinutesFromEntryWithDivision()
    {
        $data = '*/30 12-13,15-16 * * *';

        $minutes = MessMinutes::getMinutes($data);
        $this->assertEquals('*/30', $minutes);
    }

    public function testGetMinutesFromEntryWithoutDivision()
    {
        $data = '17 12-13,15-16 * * *';

        $minutes = MessMinutes::getMinutes($data);
        $this->assertEquals('17', $minutes);
    }

    public function testAddMessValueToMinutes()
    {
        $minutes = '15';

        $newMinutes = MessMinutes::addMessValue($minutes);
        $this->assertEquals(true, is_int($newMinutes));
        $this->assertLessThan(59, $newMinutes);
    }

    public function testAddMessValueToMinutesMoreThan60()
    {
        $minutes = '58';

        $newMinutes = MessMinutes::addMessValue($minutes);
        $this->assertEquals(true, is_int($newMinutes));
        $this->assertLessThan(59, $newMinutes);
    }

    public function testMessEntryTypical()
    {
        $data = '17 12-13,15-16 * * *';

        $newEntry = MessMinutes::messEntry($data);
        $this->assertGreaterThan(17, MessMinutes::getMinutes($newEntry));
    }

    public function testMessEntryBigValue()
    {
        $data = '52 12-13,15-16 * * *';

        $newEntry = MessMinutes::messEntry($data);
        $this->assertLessThan(52, MessMinutes::getMinutes($newEntry));
    }

    public function testMessEntryFirstTime()
    {
        $data = '*/30 12-13,15-16 * * *';

        $newEntry = MessMinutes::messEntry($data);
        $this->assertNotEquals('*/30', MessMinutes::getMinutes($newEntry));
    }
}
