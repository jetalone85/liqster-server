<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 * @package Liqster\HomePageBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     * @return Response
     * @throws \LogicException
     * @Route("/", name="homepage")
     * @Method({"GET"})
     */
    public function indexAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Default:index.html.twig');
    }
}
