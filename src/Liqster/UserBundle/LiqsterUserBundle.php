<?php

namespace Liqster\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class LiqsterUserBundle
 * @package Liqster\UserBundle
 */
class LiqsterUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
