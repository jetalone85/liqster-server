<?php

namespace Liqster\PaymentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class DefaultController
 * @package Liqster\PaymentBundle\Controller
 *
 * @Route("/payment")
 */
class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @param Request $request
     * @return Response
     * @throws \InvalidArgumentException
     * @Route("/", name="payment_index")
     * @Method({"POST"})
     */
    public function indexAction(Request $request): Response
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($request, 'json');
        file_put_contents(__DIR__ . '/../../../../var/cache/test.txt', $jsonContent);

        return new Response('200');
    }
}
