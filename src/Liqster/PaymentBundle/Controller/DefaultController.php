<?php

namespace Liqster\PaymentBundle\Controller;

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
     * @return Response
     */
    public function indexAction(): Response
    {
        return $this->render('LiqsterPaymentBundle:Default:index.html.twig');
    }
}
