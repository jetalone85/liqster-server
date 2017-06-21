<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PaymentController
 * @package Liqster\HomePageBundle\Controller
 */
class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/payment")
     * @Method({"POST"})
     */
    public function indexAction(Request $request): Response
    {
    }
}
