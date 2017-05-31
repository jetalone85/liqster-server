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
        if ($this->getUser()) {
            return $this->redirectToRoute('account_index');
        }

        return $this->render('LiqsterHomePageBundle:Default:index.html.twig', array());
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/howtoworks", name="pageHowTo")
     * @Method({"GET"})
     */
    public function pageHowToAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Default:pageHowTo.html.twig', array());
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/contact", name="pageContact")
     * @Method({"GET"})
     */
    public function pageContactAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Default:pageContact.html.twig', array());
    }

    /**
     * @return Response
     * @Route("/tags.json", name="tags", defaults={"_format": "json"})
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function tagsAction(): Response
    {
        $tags = $this->getDoctrine()->getRepository('LiqsterHomePageBundle:Tag')->findBy([], ['name' => 'ASC']);

        return $this->render('LiqsterHomePageBundle:Default:tags.json.twig', ['tags' => $tags]);
    }
}
