<?php

namespace Liqster\Domain\Cron;

use PHPUnit\Framework\TestCase;

/**
 * Class ComposerTest
 * @package Liqster\Domain\Cron
 */
class ComposerTest extends TestCase
{
    public function testSwitchDaily()
    {
        $period = 'afternoon';
        $range = Composer::switchDaily($period);

        $this->assertEquals('14-19', $range);
    }

    public function testCompose()
    {
        $period = 'morning';
        $crontab = Composer::compose($period);

        $this->assertEquals('0/30 6-11 * * *', $crontab);
    }
}
