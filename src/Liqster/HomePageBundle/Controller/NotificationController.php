<?php

namespace Liqster\HomePageBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NotificationController extends Controller
{
    /**
     * @Route("/notif/sendEmail", name="notification_test_email")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function sendTestEmailAction(Request $request): Response
    {
        $mailer = $this->get('swiftmailer.mailer.default');

        $message = $mailer->createMessage('message')
            ->setSubject('Test')
            ->setFrom('admin@liqster.pl')
            ->setTo($this->getUser()->getEmail())
            ->setBody(
                $this->renderView(
                    'LiqsterHomePageBundle:Email:notification_confirm.txt.twig', [
                    'user' => $this->getUser()
                    ]
                ),
                'text/html'
            );

        $mailer->send($message);

        return $this->render('LiqsterHomePageBundle:Notification:confirm.html.twig', []);
    }
}
