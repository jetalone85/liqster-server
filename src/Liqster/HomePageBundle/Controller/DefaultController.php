<?php

namespace Liqster\HomePageBundle\Controller;

use FOS\UserBundle\Form\Factory\FactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
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

        /**
         * @var $formFactory FactoryInterface
         */
        $formFactory = $this->get('fos_user.registration.form.factory');
        $form = $formFactory->createForm();

        return $this->render(
            'LiqsterHomePageBundle:Default:index.html.twig', [
                'products' => $products,
                'form' => $form->createView(),
            ]
        );
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

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/termsOfService", name="homepage_termsOfService")
     * @Method({"GET"})
     */
    public function termsOfServiceAction(): Response
    {
        return $this->render(
            'LiqsterHomePageBundle:Default:termsOfService.html.twig', [
            ]
        );
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/privacyPolicy", name="homepage_privacyPolicy")
     * @Method({"GET"})
     */
    public function privacyPolicyAction(): Response
    {
        return $this->render(
            'LiqsterHomePageBundle:Default:privacyPolicy.html.twig', [
            ]
        );
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/cookiesPolicy", name="homepage_cookiesPolicy")
     * @Method({"GET"})
     */
    public function cookiesPolicyAction(): Response
    {
        return $this->render(
            'LiqsterHomePageBundle:Default:cookiesPolicy.html.twig', [
            ]
        );
    }
}
