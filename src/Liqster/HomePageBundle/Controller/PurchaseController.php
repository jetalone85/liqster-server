<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseController
 * @package Liqster\HomePageBundle\Controller
 */
class PurchaseController extends Controller
{
    /**
     * @Route("/purchase/active", name="purchase_active")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function activeAction(Request $request): Response
    {
        return $this->render('LiqsterHomePageBundle:Purchase:active.html.twig', []);
    }
}
