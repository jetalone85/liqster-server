<?php

namespace Liqster\PaymentBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DefaultController
 * @package Liqster\PaymentBundle\Controller
 *
 * @Route("/payment")
 */
class DefaultController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @Route("/", name="payment_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request): Response
    {
//        $params = $request->request->all();

//        dump($request->query->get(234));

//        $encoders = array(new XmlEncoder(), new JsonEncoder());
//        $normalizers = array(new ObjectNormalizer());
//
//        $serializer = new Serializer($normalizers, $encoders);

///        $jsonContent = $serializer->serialize($params, 'json');


//        print_r($params);

        $em = $this->getDoctrine()->getManager();

        $payment = $em->getRepository('LiqsterPaymentBundle:Payment')->findOneBy([
            'session' => $request->query->get('p24_session_id')
        ]);

//        dump($payment);

        $payment->setP24OrderId($request->query->get('p24_order_id'));
        $payment->setP24Statement($request->query->get('p24_statement'));
        $payment->setP24Sign($request->query->get('p24_sign'));

        $em->merge($payment);
        $em->flush();

        return new Response(200);
    }
}
