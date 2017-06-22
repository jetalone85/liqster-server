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

        print_r($params);

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

//        return new Response('200 OK');
    }
}
