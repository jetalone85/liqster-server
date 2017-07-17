<?php

namespace Liqster\HomePageBundle\Controller;

use Liqster\HomePageBundle\Entity\ApiDump;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/tags", name="tags", defaults={"_format": "json"})
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function tagsAction(): Response
    {
        $tags = $this->getDoctrine()->getRepository('LiqsterHomePageBundle:Tag')->findBy([], ['name' => 'ASC'], 999);

        return $this->render('LiqsterHomePageBundle:Api:tags.json.twig', ['tags' => $tags]);
    }

    /**
     * @Route("/tags/input", name="tags_input")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function getTagsAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $test = new ApiDump();
        $test->setData($request->query->get('data'));
        $em->persist($test);
        $em->flush();

        return $this->render('LiqsterHomePageBundle:Api:ok.json.twig');
    }
}
