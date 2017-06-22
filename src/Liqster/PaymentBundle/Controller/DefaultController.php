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
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @Route("/", name="payment_index")
     * @Method({"POST"})
     */
    public function indexAction(Request $request): Response
    {
        $params = $request->request->all();

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($params, 'json');


//        print_r($params);

//        $em = $this->getDoctrine()->getManager();
//
//        $payment = $em->getRepository('LiqsterPaymentBundle:Payment')->findOneBy([
//            'session' => $params['p24_session_id']
//        ]);
//
//        $payment->setP24OrderId($params['p24_order_id']);
//        $payment->setP24Statement($params['p24_statement']);
//        $payment->setP24Sign($params['p24_sign']);

//        $em->persist($payment);
//        $em->flush();

        return new Response($jsonContent);
    }
}
