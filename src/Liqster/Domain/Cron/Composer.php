<?php

namespace Liqster\Domain\Cron;
use Liqster\HomePageBundle\Entity\Schedule;

/**
 * Class Composer
 * @package Liqster\Domain\Cron
 */
class Composer
{
    public static function compose(Schedule $schedule): string
    {
        return '*/30 ' . self::switchDaily($schedule) . ' * * *';
    }

    public static function switchDaily(Schedule $schedule)
    {
        $array = [];
        $text = '';

        if ($schedule->getMorning() === 1) {
            $array[] = '6-9';
        }
        if ($schedule->getNoon() === 1) {
            $array[] ='10-12';
        }
        if ($schedule->getAfternoon() === 1) {
            $array[] ='13-16';
        }
        if ($schedule->getEvening() === 1) {
            $array[] ='17-20';
        }
        if ($schedule->getNoon() === 1) {
            $array[] = '10-12';
        }
    }
}
