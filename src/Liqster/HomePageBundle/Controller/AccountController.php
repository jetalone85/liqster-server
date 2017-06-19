<?php

namespace Liqster\HomePageBundle\Controller;

use Cron\CronBundle\Entity\CronJob;
use Instagram\API\Framework\InstagramException;
use Instaxer\Instaxer;
use Liqster\HomePageBundle\Entity\Account;
use Liqster\HomePageBundle\Form\AccountType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function indexAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $accounts = $em->getRepository('LiqsterHomePageBundle:Account')->findBy(['user' => $this->getUser()]);

        $query = $request->request->get('form');

        if ($query) {
            $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $query['id']]);

            $newStatus = false;
            if (count($query) === 2) {
                $newStatus = $query['enable'] === 'on';
            }
            $cronJob->setEnabled($newStatus);
            $em->flush();

            return $this->redirectToRoute('account_index');
        }

        return $this->render('LiqsterHomePageBundle:Account:index.html.twig', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/new", name="account_new")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws \Exception
     * @throws \LogicException
     */
    public function newAction(Request $request)
    {
        $account = new Account();

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            try {
                $path = './instaxer/profiles/' . $this->getUser() . DIRECTORY_SEPARATOR . $account->getName() . '.dat';

                $instaxer = new Instaxer($path);
                $instaxer->login($account->getName(), $account->getPassword());

            } catch (InstagramException $exception) {
                return $this->redirectToRoute('account_new', [
                    'status' => $exception->getMessage()
                ]);
            }

            $em = $this->getDoctrine()->getManager();
            $account->setUser($this->getUser());
            $account->setCreated(new \DateTime('now'));
            $account->setModif(new \DateTime('now'));
            $em->persist($account);

            $cronJob = new CronJob();
            $cronJob->setName($account->getId());
            $cronJob->setAccount($account);
            $cronJob->setCommand('instaxer:run ' . $account->getId());
            $cronJob->setDescription(' ');
            $cronJob->setSchedule('*/30 * * * *');
            $cronJob->setEnabled(false);
            $em->persist($cronJob);

            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);

        }

        return $this->render('LiqsterHomePageBundle:Account:new.html.twig', [
            'Account' => $account,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id}", name="account_show")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Account $account
     * @return Response
     * @throws \Exception
     */
    public function showAction(Request $request, Account $account): Response
    {
        $path = './instaxer/profiles/' . $this->getUser() . DIRECTORY_SEPARATOR . $account->getName() . '.dat';

        $instaxer = new Instaxer($path);
        $instaxer->login($account->getName(), $account->getPassword());

        $instagram = $instaxer->instagram->getUserInfo($instaxer->instagram->getCurrentUserAccount()->getUser());

        $deleteForm = $this->createDeleteForm($account);

        $editForm = $this->createEditForm($account);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_show', array('id' => $account->getId()));
        }

        return $this->render('LiqsterHomePageBundle:Account:show.html.twig', array(
            'account' => $account,
            'instagram' => $instagram,
            'delete_form' => $deleteForm->createView(),
            'edit_form' => $editForm->createView(),
        ));
    }

    /**
     * Creates a form to delete a Account entity.
     *
     * @param Account $account The Account entity
     *
     * @return Form The form
     */
    private function createDeleteForm(Account $account): Form
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('account_delete', array('id' => $account->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @param Account $account
     * @return Form The form
     */
    private function createEditForm(Account $account): Form
    {
        return $this->createFormBuilder($account)
            ->add('name', TextType::class, array(
                'label' => 'Name',
                'required' => false
            ))
            ->add('save', SubmitType::class)
            ->getForm();
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/{id}/edit", name="account_edit")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Account $account
     * @return RedirectResponse|Response
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
     * @return RedirectResponse
     * @throws \LogicException
     */
    public function deleteAction(Request $request, Account $account): RedirectResponse
    {
        $form = $this->createDeleteForm($account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($account);
            $em->flush();
        }

        return $this->redirectToRoute('account_index');
    }

    /**
     * @Route("/{id}/check", name="account_check")
     * @Method({"GET", "PUT"})
     * @param Account $account
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \LogicException
     * @throws \Exception
     */
    public function checkAction(Account $account): Response
    {
        $fs = new Filesystem();
        $fs->mkdir('./instaxer/profiles/' . $account->getUser());

        $path = './instaxer/profiles/' . $this->getUser() . DIRECTORY_SEPARATOR . $account->getName() . '.dat';

        $instaxer = new Instaxer($path);
        $instaxer->login($account->getName(), $account->getPassword());

        $image = $instaxer->instagram->getCurrentUserAccount()->getUser()->getHdProfilePicUrlInfo()->getUrl();

        $em = $this->getDoctrine()->getManager();
        $account->setImage($image);
        $em->flush();

        return $this->redirectToRoute('account_show', array('id' => $account->getId()));
    }

    /**
     * @Route("/{id}/activate", name="account_activate")
     * @Method("GET")
     * @param Account $account
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function activateAction(Account $account): Response
    {
        $em = $this->getDoctrine()->getManager();
        $cron = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $account->getId()]);

        return $this->render('LiqsterHomePageBundle:Account:activate.html.twig', array(
            'account' => $account,
            'cron' => $cron,
        ));
    }
}
