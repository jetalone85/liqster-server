<?php

namespace Liqster\HomePageBundle\Controller;

use Instaxer\Instaxer;
use Liqster\HomePageBundle\Entity\Account;
use Liqster\HomePageBundle\Form\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Account controller.
 *
 * @Route("account")
 */
class AccountController extends Controller
{
    /**
     * Lists all Account entities.
     *
     * @Route("/", name="account_index")
     * @Method("GET")
     * @throws \LogicException
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $accounts = $em->getRepository('LiqsterHomePageBundle:Account')->findBy(['user' => $this->getUser()]);

        return $this->render('LiqsterHomePageBundle:Account:index.html.twig', array(
            'accounts' => $accounts,
        ));
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/new", name="account_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        $account = new Account();
        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $account->setUser($this->getUser());
            $em->persist($account);
            $em->flush($account);

            return $this->redirectToRoute('account_show', array('id' => $account->getId()));
        }

        return $this->render('LiqsterHomePageBundle:Account:new.html.twig', array(
            'Account' => $account,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id}", name="account_show")
     * @Method("GET")
     * @param Account $account
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction(Account $account)
    {
        $deleteForm = $this->createDeleteForm($account);

        return $this->render('LiqsterHomePageBundle:Account:show.html.twig', array(
            'account' => $account,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a Account entity.
     *
     * @param Account $account The Account entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Account $account)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('account_delete', array('id' => $account->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/{id}/edit", name="account_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Account $account
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function editAction(Request $request, Account $account)
    {
        $deleteForm = $this->createDeleteForm($account);
        $editForm = $this->createForm(AccountType::class, $account);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_edit', array('id' => $account->getId()));
        }

        return $this->render('LiqsterHomePageBundle:Account:edit.html.twig', array(
            'Account' => $account,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Account entity.
     *
     * @Route("/{id}", name="account_delete")
     * @Method("DELETE")
     * @param Request $request
     * @param Account $account
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Account $account)
    {
        $form = $this->createDeleteForm($account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush($account);
        }

        return $this->redirectToRoute('account_index');
    }

    /**
     * @Route("/{id}/check", name="account_check")
     * @Method("GET")
     * @param Account $account
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function checkAction(Account $account)
    {
        $cacheDir = $this->container->get('kernel')->getCacheDir();


        $fs = new Filesystem();
        $fs->mkdir($cacheDir . '/instaxer/profiles/' . $account->getUser());

        $path = $cacheDir . '/instaxer/profiles/' . $account->getUser() . DIRECTORY_SEPARATOR . $account->getId() . '.dat';

        $instaxer = new Instaxer($path);
        $instaxer->login($account->getName(), $account->getPassword());

        return $this->render('LiqsterHomePageBundle:Account:check.html.twig', array(
            'account' => $account,
        ));
    }

    /**
     * @Route("/{id}/activate", name="account_activate")
     * @Method("GET")
     * @param Account $account
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function activateAction(Account $account)
    {
        $em = $this->getDoctrine()->getManager();
        $cron = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $account->getId()]);

        return $this->render('LiqsterHomePageBundle:Account:activate.html.twig', array(
            'account' => $account,
            'cron' => $cron
        ));
    }
}
