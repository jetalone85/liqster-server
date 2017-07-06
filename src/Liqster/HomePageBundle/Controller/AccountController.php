<?php

namespace Liqster\HomePageBundle\Controller;

use Cron\CronBundle\Entity\CronJob;
use Exception;
use Instagram\API\Framework\InstagramException;
use Liqster\HomePageBundle\Entity\Account;
use Liqster\HomePageBundle\Entity\AccountInstagramCache;
use Liqster\HomePageBundle\Entity\Purchase;
use Liqster\HomePageBundle\Form\AccountEditCommentsType;
use Liqster\HomePageBundle\Form\AccountEditTagsType;
use Liqster\HomePageBundle\Form\AccountEditType;
use Liqster\HomePageBundle\Form\AccountPaymentType;
use Liqster\HomePageBundle\Form\AccountType;
use Liqster\MQBundle\Domain\MQ;
use Liqster\PaymentBundle\Domain\Przelewy24;
use Liqster\PaymentBundle\Entity\Payment;
use Ramsey\Uuid\Uuid;
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

        $accounts = $em->getRepository('LiqsterHomePageBundle:Account')->findBy(['user' => $this->getUser()]);

        if ($accounts === []) {
            return $this->redirectToRoute('account_new');
        }

        foreach ($accounts as $account) {
            $purchase = $em->getRepository('LiqsterHomePageBundle:Purchase')->findOneBy(['account' => $account]);

            /**
             * @TODO
             * do przeniesienia do domain
             */
            if ($account->isPayed() === false && $purchase->getStatus() === 'verify') {
                $account->setPayed(true);
                $em->flush();

                $mailer = $this->get('swiftmailer.mailer.default');

                $message = $mailer->createMessage()
                    ->setSubject('Aktywacja')
                    ->setFrom('admin@liqster.pl')
                    ->setTo($this->getUser()->getEmail())
                    ->setBody(
                        $this->renderView(
                            '@LiqsterHomePage/Email/activate_account.txt.twig', [
                                'user' => $this->getUser()
                            ]
                        ),
                        'text/html'
                    );

                $mailer->send($message);
            }
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
        $cronJob = new CronJob();

        $form = $this->createForm(AccountType::class, $account);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $mq = new MQ();
                $instaxer = $mq->query(
                    'instaxers?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword())->getBody()->getContents();

                /**
                 * @TODO:
                 * Dodać Account Instagram Cache
                 */

            } catch (InstagramException $exception) {
                return $this->redirectToRoute(
                    'account_new', [
                        'status' => $exception->getMessage()
                    ]
                );
            }

            $em = $this->getDoctrine()->getManager();
            $account->setUser($this->getUser());
            $account->setCreated(new \DateTime('now'));
            $account->setModif(new \DateTime('now'));
            $em->persist($account);

            $cronJob->setName($account->getId());
            $cronJob->setAccount($account);
            $cronJob->setCommand('instaxer:run ' . $account->getId());
            $cronJob->setDescription(' ');
            $cronJob->setSchedule('*/30 * * * *');
            $cronJob->setEnabled(false);
            $em->persist($cronJob);

            $em->flush();

            return $this->redirectToRoute('account_new_payment', array('id' => $account->getId()));
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:new.html.twig', [
                'Account' => $account,
                'form' => $form->createView(),
            ]
        );
    }

    /**
     *
     * @Route("/{id}/new_payment",  name="account_new_payment")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @param Account $account
     * @return Response
     * @throws \LogicException
     */
    public function newPaymentAction(Request $request, Account $account): Response
    {
        $payment = new Payment();
        $purchase = new Purchase();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(AccountPaymentType::class, $account);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $purchase->setAccount($account);
            $purchase->setCreate(new \DateTime('now'));
            $purchase->setModification(new \DateTime('now'));
            $purchase->setProduct($account->getProduct());
            $purchase->setStatus('open');
            $em->persist($purchase);

            $payment->setCreate(new \DateTime('now'));
            $payment->setSession(Uuid::uuid4());
            $payment->setPurchase($purchase);

            $crc = md5($payment->getSession() . '|' . 61791 . '|' . 1 . '|' . 'PLN' . '|' . 'c751931d7ae41926');

            $payment->setToken($crc);

            $em->persist($payment);

            $em->flush();

            try {
                $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

                $P24->addValue('p24_session_id', $payment->getSession());
                $P24->addValue('p24_amount', $account->getProduct()->getPrice());
                $P24->addValue('p24_currency', 'PLN');
                $P24->addValue('p24_email', $this->getUser()->getEmail());
                $P24->addValue('p24_description', ' ');
                $P24->addValue('p24_country', 'PL');
                $P24->addValue('p24_phone', '+48500600700');
                $P24->addValue('p24_language', 'pl');
                $P24->addValue('p24_method', '1');
                $P24->addValue('p24_url_return', 'http://liqster.pl/account/');
                $P24->addValue('p24_url_status', 'http://liqster.pl/payment/');
                $P24->addValue('p24_time_limit', 0);

                $P24->trnRegister(true);

                die();

            } catch (Exception $exception) {
                echo $exception->getMessage() . "\n";
            }
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:newPayment.html.twig', [
                'Account' => $account,
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

        $accountInstagramCache = $em
            ->getRepository('LiqsterHomePageBundle:AccountInstagramCache')
            ->findOneBy(['account' => $account]);
        $instagram = json_decode($accountInstagramCache->getValue(), true);

        $editForm = $this->createForm(AccountEditType::class, $account);
        $editForm->handleRequest($request);

        $editTagsForm = $this->createForm(AccountEditTagsType::class, $account);
        $editTagsForm->handleRequest($request);

        $editCommentsForm = $this->createForm(AccountEditCommentsType::class, $account);
        $editCommentsForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $account->setTagsText($account->getTagsText());
            $account->setComments($account->getComments());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', array('id' => $account->getId()));
        }

        if ($editTagsForm->isSubmitted() && $editTagsForm->isValid()) {
            $account->setName($account->getName());
            $account->setPassword($account->getPassword());
            $account->setComments($account->getComments());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', array('id' => $account->getId()));
        }

        if ($editCommentsForm->isSubmitted() && $editCommentsForm->isValid()) {
            $account->setName($account->getName());
            $account->setPassword($account->getPassword());
            $account->setTagsText($account->getTagsText());
            $account->setModif(new \DateTime('now'));

            $em->flush();

            return $this->redirectToRoute('account_show', array('id' => $account->getId()));
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

            return $this->redirectToRoute('account_show', array('id' => $query['id']));
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:show.html.twig', array(
                'account' => $account,
                'instagram' => $instagram,
                'edit_form' => $editForm->createView(),
                'edit_tags_form' => $editTagsForm->createView(),
                'edit_comments_form' => $editCommentsForm->createView(),
                'delete_form' => $deleteForm->createView(),
            )
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
            ->setAction($this->generateUrl('account_delete', array('id' => $account->getId())))
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

            return $this->redirectToRoute('account_edit', array('id' => $account->getId()));
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
        $em->remove($cronJob);

        $purchase = $em->getRepository('LiqsterHomePageBundle:Purchase')->findOneBy(['account' => $account]);
        $em->remove($purchase);

        $payment = $em->getRepository('LiqsterPaymentBundle:Payment')->findOneBy(['purchase' => $purchase]);
        $em->remove($payment);

        $em->remove($account);
        $em->flush();

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
            'instaxers/infos?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword())->getBody()->getContents();

        $em = $this->getDoctrine()->getManager();
        $accountInstagramCache = $em->getRepository('LiqsterHomePageBundle:AccountInstagramCache')->findOneBy(['account' => $account]);
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
        $image = $instagram_user['user']['profile_pic_url'];

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

        return $this->render(
            'LiqsterHomePageBundle:Account:activate.html.twig', array(
                'account' => $account,
                'cron' => $cron,
            )
        );
    }
}
