<?php

namespace Liqster\Bundle\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseController
 * @package Liqster\Bundle\HomePageBundle\Controller
 */
class PurchaseController extends Controller
{
    /**
     * @Route("/purchase/active", name="purchase_active")
     * @Method({"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function activeAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Purchase:active.html.twig', []);
    }
}
