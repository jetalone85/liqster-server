<?php

namespace Liqster\Bundle\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class LiqsterUserBundle
 *
 * @package Liqster\Bundle\UserBundle
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
