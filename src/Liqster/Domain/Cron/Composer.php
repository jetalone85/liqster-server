<?php

namespace Liqster\Domain\Cron;

use Liqster\HomePageBundle\Entity\Schedule;

/**
 * Class Composer
 * @package Liqster\Domain\Cron
 */
class Composer
{
    /**
     * @param Schedule $schedule
     * @return string
     */
    public static function compose(Schedule $schedule): string
    {
        return '*/2 ' . self::switchDaily($schedule) . ' * * *';
    }

    /**
     * @param Schedule $schedule
     * @return string
     */
    public static function switchDaily(Schedule $schedule)
    {
        $array = [];
        $text = '';

        if ($schedule->getMorning() === '1') {
            $array[] = '6-9';
        }
        if ($schedule->getNoon() === '1') {
            $array[] = '10-12';
        }
        if ($schedule->getAfternoon() === '1') {
            $array[] = '13-16';
        }
        if ($schedule->getEvening() === '1') {
            $array[] = '17-20';
        }
        if ($schedule->getNight() === '1') {
            $array[] = '21-3';
        }

        $numItems = count($array);
        $i = 0;

        foreach ($array as $key => $item) {
            if (++$i === $numItems) {
                $text .= $item;
            } else {
                $text .= $item . ',';
            }
        }

        return $text;
    }
}
