<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
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
        $em = $this->getDoctrine();
        $products = $em->getRepository('LiqsterHomePageBundle:Product')->findAll();

        return $this->render('LiqsterHomePageBundle:Default:index.html.twig', [
            'products' => $products,
        ]);
    }


    /**
     * @param Request $request
     * @return Response
     * @throws \InvalidArgumentException
     */
    public function cookieInfoAction(Request $request): Response
    {
        $cookieInfo = $request->cookies->has('cookieconsent_status');
        $response = new Response();

        if (!$cookieInfo) {
            $cookie = new Cookie('cookieconsent_status', 'allow');
            $response->headers->setCookie($cookie);
            return $this->render('@LiqsterHomePage/Default/cookieInfo.html.twig');
        }

        return $response;
    }
}
