<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends Controller
{
    /**
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        dump($request);
        return $this->render(
            'LiqsterHomePageBundle:Error:index.html.twig', [
            ]
        );
    }
}
