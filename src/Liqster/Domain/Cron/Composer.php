<?php

namespace Liqster\Domain\Cron;

/**
 * Class Composer
 * @package Liqster\Domain\Cron
 */
class Composer
{
    public static function compose($period): string
    {
        return '0/30 ' . self::switchDaily($period) . ' * * *';
    }

    public static function switchDaily($period)
    {
        switch ($period) {
            case 'morning':
                return '6-11';
                break;
            case 'work':
                return '9-14';
                break;
            case 'afternoon':
                return '14-19';
                break;
            case 'evening':
                return '18-23';
                break;
            case 'morningAndEvening':
                return '7-11,18-21';
                break;
        }
    }
}
