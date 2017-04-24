<?php

namespace Liqster\HomePageBundle\Controller;

use Liqster\HomePageBundle\Entity\Account;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResultController extends Controller
{
    /**
     * Finds and displays a Account entity.
     *
     * @Route("/account/{id}/reports", name="result_list")
     * @Method({"GET"})
     * @param Account $account
     * @return Response
     * @throws \LogicException
     */
    public function listAction(Account $account): Response
    {
        $em = $this->getDoctrine()->getManager();

        $reports = $em->getRepository('CronBundle:CronReport')->findBy(['job' => $account->getCronJob()]);

        $reports = array_reverse(array_slice($reports, -100, 100, true));

        return $this->render('LiqsterHomePageBundle:Result:list.html.twig', array(
            'reports' => $reports,
        ));
    }
}
