<?php

namespace Liqster\PaymentBundle\Controller;

use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function indexAction(LoggerInterface $logger): Response
    {
        $logger->info('I just got the logger');
        return $this->render('LiqsterPaymentBundle:Default:index.html.twig');
    }
}
