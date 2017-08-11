<?php

namespace Liqster\Bundle\HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProductController
 * @package Liqster\Bundle\HomePageBundle\Controller
 */
class ProductController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function fieldAction(Request $request): Response
    {
        $em = $this->getDoctrine();
        $product = $em
            ->getRepository('LiqsterHomePageBundle:Product')
            ->findOneBy(['id' => $request->attributes->get('id')]);

        return $this->render(
            'LiqsterHomePageBundle:Product:field.html.twig',
            [
                'product' => $product
            ]
        );
    }
}
