<?php

namespace Liqster\Bundle\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Liqster\Bundle\UserBundle\Form\ReminderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RemindController
 * @package Liqster\Bundle\UserBundle\Controller
 */
class RemindController extends BaseController
{
    /**
     * @Route("/registration/remind", name="fos_reminder")
     * @param Request $request
     * @return Response
     * @throws \LogicException
     * @throws \OutOfBoundsException
     */
    public function remindAction(Request $request): Response
    {

        $reminderForm = $this->createForm(ReminderType::class);
        $reminderForm->handleRequest($request);

        if ($reminderForm->isSubmitted() && $reminderForm->isValid()) {

            $email = $reminderForm->get('email')->getData();
            $user = $this->get('fos_user.user_manager')->findUserByEmail($email);

            $url = $this->generateUrl('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), true);

            $mailer = $this->get('swiftmailer.mailer.default');

            $message = $mailer->createMessage()
                ->setSubject('Weryfikacja konta')
                ->setFrom('admin@liqster.pl')
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        '@FOSUser/Registration/email.txt.twig', [
                            'user' => $user,
                            'confirmationUrl' => $url
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
        }

        return $this->render(
            '@FOSUser/Reminder/reminder.html.twig', [
                'user' => $this->getUser(),
                'reminder_form' => $reminderForm->createView(),
            ]
        );
    }
}
