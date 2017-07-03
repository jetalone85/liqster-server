<?php

namespace Liqster\HomePageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends Controller
{
    public function fieldAction(Request $request): Response
    {
        $em = $this->getDoctrine();
        $product = $em->getRepository('LiqsterHomePageBundle:Product')->findOneBy(['id' => $request->attributes->get('id')]);

        return $this->render(
            'LiqsterHomePageBundle:Product:field.html.twig', [
            'product' => $product
            ]
        );
    }
}
