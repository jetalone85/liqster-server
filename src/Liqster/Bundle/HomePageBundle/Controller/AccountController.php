<?php

namespace Liqster\Bundle\HomePageBundle\Controller;

use Cron\CronBundle\Entity\CronJob;
use Exception;
use Liqster\Bundle\HomePageBundle\Entity\Account;
use Liqster\Bundle\HomePageBundle\Entity\AccountInstagramCache;
use Liqster\Bundle\HomePageBundle\Entity\Purchase;
use Liqster\Bundle\HomePageBundle\Entity\Schedule;
use Liqster\Bundle\HomePageBundle\Form\AccountEditCommentsType;
use Liqster\Bundle\HomePageBundle\Form\AccountEditTagsType;
use Liqster\Bundle\HomePageBundle\Form\AccountEditType;
use Liqster\Bundle\HomePageBundle\Form\AccountProgramType;
use Liqster\Bundle\HomePageBundle\Form\AccountType;
use Liqster\Bundle\PaymentBundle\Domain\Przelewy24;
use Liqster\Bundle\PaymentBundle\Entity\Payment;
use Liqster\Domain\Cron\Composer;
use Liqster\Domain\MQ\MQ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @Route("/",     name="account_index")
     * @Method({"GET", "POST"})
     * @return         Response
     * @throws         \InvalidArgumentException
     * @throws         \UnexpectedValueException
     * @throws         \LogicException
     */
    public function indexAction(): Response
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $accounts = $em
                ->getRepository('LiqsterHomePageBundle:Account')
                ->findBy(['user' => $this->getUser(), 'disabled' => false]);
        } catch (Exception $exception) {
            return $this->redirectToRoute('account_new');
        }

        foreach ($accounts as $account) {
            if (!$account->getAccountInstagramCache()) {
                return $this->redirectToRoute('account_check', ['id' => $account->getId()]);
            }
        }


        if (count($accounts) === 1) {
            return $this->redirectToRoute('account_show', ['id' => $accounts[0]->getId()]);
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:index.html.twig', [
                'accounts' => $accounts,
            ]
        );
    }

    /**
     * Creates a new Account entity.
     *
     * @Route("/new",  name="account_new")
     * @Method({"GET", "POST"})
     * @param          Request $request
     * @return         RedirectResponse|Response
     * @throws         \InvalidArgumentException
     * @throws         \RuntimeException
     * @throws         Exception
     * @throws         \LogicException
     */
    public function newAction(Request $request)
    {
        $account = new Account();
        $schedule = new Schedule();
        $cronJob = new CronJob();

        $error = null;

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($request->query->get('status') === '500') {
            $error = '500';
        }

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $mq = new MQ();
                $response = $mq->query(
                    'POST',
                    'instaxers.json?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword())->getBody()->getContents();

            } catch (\Exception $exception) {
                return $this->redirectToRoute(
                    'account_new', [
                        'status' => $exception->getCode()
                    ]
                );
            }

            try {
                $em = $this->getDoctrine()->getManager();
                $account->setUser($this->getUser());
                $account->setCreated(new \DateTime('now'));
                $account->setModif(new \DateTime('now'));
                $account->setCommentsRun(true);
                $account->setComments('@liqster.pl :)');
                $account->setLikesRun(true);
                $account->setType('free');
                $em->persist($account);

                $schedule->setAccount($account);
                $schedule->setCreate(new \DateTime('now'));
                $schedule->setModification(new \DateTime('now'));
                $em->persist($schedule);

                $cronJob->setName($account->getId());
                $cronJob->setAccount($account);
                $cronJob->setCommand('instaxer:run ' . $account->getId());
                $cronJob->setDescription('<opis>');
                $cronJob->setEnabled(true);
                $cronJob->setSchedule('* 7-16 * * *');
                $em->persist($cronJob);

                $em->flush();

            } catch (\Exception $exception) {
                return $this->redirectToRoute('account_new', ['error' => 'createAccountError', 'content' => $exception->getMessage()]);
            }

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:new.html.twig', [
                'Account' => $account,
                'error' => $error,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * Finds and displays a Account entity.
     *
     * @Route("/{id}", name="account_show")
     * @Method({"GET", "POST"})
     * @param          Request $request
     * @param          Account $account
     * @return         Response
     * @throws         Exception
     */
    public function showAction(Request $request, Account $account): Response
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $accountInstagramCache = $em
                ->getRepository('LiqsterHomePageBundle:AccountInstagramCache')
                ->findOneBy(['account' => $account]);
            if (!$accountInstagramCache) {
                throw new \RuntimeException('Don\'t find any cache.');
            }
            $instagram = json_decode($accountInstagramCache->getValue(), true);
        } catch (Exception $exception) {
            return $this->redirectToRoute('account_check', ['id' => $account->getId()]);
        }

        $account->setLikesRun(true);
        $account->setCommentsRun(true);
        $account->setComments($account->getComments());
        $account->setTagsText($account->getTagsText());
        $em->merge($account);
        $em->flush();

        $editForm = $this->createForm(AccountEditType::class, $account);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $account->setTagsText($account->getTagsText());
            $account->setComments($account->getComments());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        $editTagsForm = $this->createForm(AccountEditTagsType::class, $account);
        $editTagsForm->handleRequest($request);

        if ($editTagsForm->isSubmitted() && $editTagsForm->isValid()) {
            $account->setName($account->getName());
            $account->setPassword($account->getPassword());
            $account->setComments($account->getComments());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        $editCommentsForm = $this->createForm(AccountEditCommentsType::class, $account);
        $editCommentsForm->handleRequest($request);

        if ($editCommentsForm->isSubmitted() && $editCommentsForm->isValid()) {
            $account->setName($account->getName());
            $account->setPassword($account->getPassword());
            $account->setTagsText($account->getTagsText());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        $editProgramForm = $this->createForm(AccountProgramType::class, $account->getSchedule());
        $editProgramForm->handleRequest($request);

        if ($editProgramForm->isSubmitted() && $editProgramForm->isValid()) {
            $schedule = $account->getSchedule();
            $schedule->setModification(new \DateTime('now'));
            $em->merge($schedule);
            $em->flush();

            $cronJob = $account->getCronJob();
            $cronJob->setSchedule(Composer::compose($schedule));

            $em->merge($cronJob);
            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
        }

        $deleteForm = $this->createDeleteForm($account);

        $query = $request->request->get('form');

        if ($query) {
            $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $query['id']]);

            $newStatus = false;
            if (count($query) === 2) {
                $newStatus = $query['enable'] === 'on';
            }
            $cronJob->setEnabled($newStatus);
            $em->flush();

            return $this->redirectToRoute('account_show', ['id' => $query['id']]);
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:show.html.twig', [
                'account' => $account,
                'date' => new \DateTime('now'),
                'instagram' => $instagram,
                'edit_form' => $editForm->createView(),
                'edit_tags_form' => $editTagsForm->createView(),
                'edit_comments_form' => $editCommentsForm->createView(),
                'edit_program_form' => $editProgramForm->createView(),
                'delete_form' => $deleteForm->createView(),
            ]
        );
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
            ->setAction($this->generateUrl('account_delete', ['id' => $account->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * Displays a form to edit an existing Account entity.
     *
     * @Route("/{id}/edit", name="account_edit")
     * @Method({"GET",      "POST"})
     * @param               Request $request
     * @param               Account $account
     * @return              RedirectResponse|Response
     * @throws              \LogicException
     */
    public function editAction(Request $request, Account $account)
    {
        $deleteForm = $this->createDeleteForm($account);
        $editForm = $this->createForm(AccountType::class, $account);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('account_edit', ['id' => $account->getId()]);
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:edit.html.twig', array(
                'Account' => $account,
                'edit_form' => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
        );
    }

    /**
     * Deletes a Account entity.
     *
     * @Route("/{id}/delete", name="account_delete")
     * @Method({"GET",        "DELETE"})
     * @param                 Request $request
     * @param                 Account $account
     * @return                RedirectResponse
     * @throws                \LogicException
     */
    public function deleteAction(Request $request, Account $account): RedirectResponse
    {
        $form = $this->createDeleteForm($account);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $account]);
        $cronJob->setEnabled(false);
        $em->merge($cronJob);

        $account->setDisabled(true);
        $em->merge($account);

        $em->flush();

        return $this->redirectToRoute('profile_deactivatedAccounts');
    }

    /**
     * @Route("/{id}/delete_force", name="account_delete_force")
     * @Method({"GET"})
     * @param Account $account
     * @return RedirectResponse
     * @throws \LogicException
     */
    public function deleteForceAction(Account $account): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();

        $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $account]);
        if ($cronJob) {
            $em->remove($cronJob);
        }

        $schedule = $em->getRepository('LiqsterHomePageBundle:Schedule')->findOneBy(['account' => $account]);
        if ($schedule) {
            $em->remove($schedule);
        }

        $accountInstagramCache = $em
            ->getRepository('LiqsterHomePageBundle:AccountInstagramCache')
            ->findOneBy(['account' => $account]);

        if ($accountInstagramCache) {
            $em->remove($accountInstagramCache);
        }

        $em->remove($account);

        $em->flush();

        $deactivatedAccounts = $em->getRepository('LiqsterHomePageBundle:Account')->findBy(['user' => $this->getUser(), 'disabled' => true]);

        if (!$deactivatedAccounts) {
            return $this->redirectToRoute('profile_deactivatedAccounts');
        }
        return $this->redirectToRoute('account_index');
    }

    /**
     * @Route("/{id}/check", name="account_check")
     * @Method({"GET", "PUT"})
     * @param Account $account
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function checkAction(Account $account): Response
    {
        $mq = new MQ();
        $instaxer_json = $mq->query(
            'POST',
            'instaxers/infos.json?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword())->getBody()->getContents();

        $em = $this->getDoctrine()->getManager();
        $accountInstagramCache = $em
            ->getRepository('LiqsterHomePageBundle:AccountInstagramCache')
            ->findOneBy(['account' => $account]);

        if (!$accountInstagramCache) {
            $accountInstagramCache = new AccountInstagramCache();
            $accountInstagramCache->setAccount($account);
            $accountInstagramCache->setCreate(new \DateTime('now'));
        }
        $accountInstagramCache->setName('info');
        $accountInstagramCache->setValue($instaxer_json);
        $accountInstagramCache->setModification(new \DateTime('now'));

        $em->persist($accountInstagramCache);
        $em->flush();

        $instagram_user = json_decode($instaxer_json, true);
        $image = $instagram_user['user']['hdProfilePicUrlInfo']['url'];

        $em = $this->getDoctrine()->getManager();

        $account->setImage($image);
        $account->setLikesRun(true);
        $account->setCommentsRun(true);
        $account->setComments($account->getComments());
        $account->setTagsText($account->getTagsText());
        $em->merge($account);
        $em->flush();

        return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
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

        return $this->render(
            'LiqsterHomePageBundle:Account:activate.html.twig',
            [
                'account' => $account,
                'cron' => $cron,
            ]
        );
    }

    /**
     * @Route("/{id}/recovery", name="account_recovery")
     * @Method("GET")
     * @param Account $account
     * @return Response
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     */
    public function recoveryAction(Account $account): Response
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $account->setDisabled(false);
            $em->merge($account);

            $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['account' => $account]);
            $cronJob->setEnabled(true);
            $em->merge($cronJob);

            $em->flush();

        } catch (Exception $exception) {
            return $this->redirectToRoute(
                'profile_deactivatedAccounts',
                ['error' => 'restore_account: we could not restore your account.']
            );
        }

        return $this->redirectToRoute('account_show', ['id' => $account->getId()]);
    }
}
