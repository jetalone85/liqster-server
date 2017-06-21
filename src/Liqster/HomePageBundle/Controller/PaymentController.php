<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class PaymentController
 * @package Liqster\HomePageBundle\Controller
 */
class PaymentController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @Route("/payment")
     * @Method({"POST"})
     */
    public function indexAction(Request $request): Response
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($request, 'json');
        file_put_contents('test.txt', $jsonContent);
    }
}
