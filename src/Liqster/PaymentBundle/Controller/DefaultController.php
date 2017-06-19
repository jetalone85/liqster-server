<?php

namespace Liqster\PaymentBundle\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class DefaultController
 * @package Liqster\PaymentBundle\Controller
 *
 * @Route("/payment")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @param LoggerInterface $logger
     */
    public function indexAction(LoggerInterface $logger)
    {
        $logger->info('I just got the logger');
    }
}
