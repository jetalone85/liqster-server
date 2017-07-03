<?php

namespace Liqster\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class LiqsterUserBundle
 *
 * @package Liqster\UserBundle
 */
class LiqsterUserBundle extends Bundle
{
    /**
     * @return string
     */
    public function getParent(): string
    {
        return 'FOSUserBundle';
    }
}
