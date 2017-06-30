<?php

namespace Liqster\HomePageBundle\Controller;

use Liqster\HomePageBundle\Entity\User;
use Liqster\HomePageBundle\Form\ProfileProfileType;
use Liqster\HomePageBundle\Form\ProfileSettingsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ProfileController
 * @package Liqster\HomePageBundle\Controller
 *
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     * @throws \LogicException
     * @internal param User $user
     * @Route("/", name="profile_index")
     * @Method({"GET","POST"})
     */
    public function indexAction(Request $request): Response
    {
        $editForm = $this->createForm(ProfileProfileType::class, $this->getUser());
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('profile_index');
        }

        return $this->render('LiqsterHomePageBundle:Profile:index.html.twig', [
            'user' => $this->getUser(),
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \LogicException
     * @Route("/setting", name="profile_setting")
     * @Method({"GET","POST"})
     */
    public function settingsAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $editForm = $this->createForm(ProfileSettingsType::class, $this->getUser());
        $editForm->handleRequest($request);

        $deleteForm = $this->createDeleteForm($this->getUser());

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updatePassword($this->getUser());
            $em->flush();
            return $this->redirectToRoute('profile_setting');
        }

        return $this->render('LiqsterHomePageBundle:Profile:setting.html.twig', [
            'user' => $this->getUser(),
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        ]);
    }

    /**
     *
     * @param User $user
     * @return Form The form
     */
    private function createDeleteForm(User $user): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/notifications", name="profile_notifications")
     * @Method({"GET"})
     */
    public function notificationsAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Profile:notifications.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/billing", name="profile_billing")
     * @Method({"GET"})
     */
    public function billingAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Profile:billing.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * @return Response
     * @throws \LogicException
     * @Route("/security", name="profile_security")
     * @Method({"GET"})
     */
    public function securityAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Profile:security.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    /**
     * Deletes a User entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param User $user
     * @return RedirectResponse
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function deleteAction(Request $request, User $user): RedirectResponse
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush($user);
        }
        return $this->redirectToRoute('homepage');
    }
}
