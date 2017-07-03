<?php

namespace Liqster\HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package Liqster\HomePageBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @return Response
     * @Route("/tags.json", name="tags", defaults={"_format": "json"})
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function tagsAction(): Response
    {
        $tags = $this->getDoctrine()->getRepository('LiqsterHomePageBundle:Tag')->findBy([], ['name' => 'ASC'], 999);

        return $this->render('LiqsterHomePageBundle:Api:tags.json.twig', ['tags' => $tags]);
    }
}
