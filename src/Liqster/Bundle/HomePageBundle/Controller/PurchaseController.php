<?php

namespace Liqster\Bundle\HomePageBundle\Controller;

use Liqster\Bundle\HomePageBundle\Entity\Purchase;
use Liqster\Bundle\HomePageBundle\Form\AccountPaymentType;
use Liqster\Bundle\PaymentBundle\Entity\Payment;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PurchaseController
 * @package Liqster\Bundle\HomePageBundle\Controller
 * @Route("/purchase")
 */
class PurchaseController extends Controller
{
    /**
     * @Route("/active", name="purchase_active")
     * @Method({"GET"})
     * @return Response
     * @throws \LogicException
     */
    public function activeAction(): Response
    {
        return $this->render('LiqsterHomePageBundle:Purchase:active.html.twig', []);
    }

    /**
     * @Route("/delete/{id}", name="purchase_delete")
     * @Method({"GET"})
     * @param Purchase $purchase
     * @return RedirectResponse
     * @throws \LogicException
     */
    public function deleteAction(Purchase $purchase): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $payment = $purchase->getPayment();

        $account = $purchase->getAccount();

        $em->remove($payment);
        $em->remove($purchase);
        $em->flush();

        return $this->redirectToRoute('account_show', ['id' => $account->getId()]);

    }

    /**
     * @Route("/duplicate/{id}", name="purchase_duplicate")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @param Purchase $purchase
     * @return Response
     * @throws \LogicException
     */
    public function duplicateAction(Request $request, Purchase $purchase): Response
    {
        $em = $this->getDoctrine()->getManager();

        $account = $purchase->getAccount();

        $form = $this->createForm(AccountPaymentType::class, $account);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $newPurchase = new Purchase();
            $newPurchase->setProduct($purchase->getProduct());
            $newPurchase->setAccount($purchase->getAccount());
            $newPurchase->setCreate(new \DateTime('now'));
            $newPurchase->setModification(new \DateTime('now'));
            $newPurchase->setStatus('open');
            $em->persist($newPurchase);

            $payment = new Payment();
            $payment->setCreate(new \DateTime('now'));
            $payment->setSession(Uuid::uuid4());
            $payment->setPurchase($newPurchase);

            $crc = md5($payment->getSession() . '|' . 61791 . '|' . 1 . '|' . 'PLN' . '|' . '8938c81eb462a997');
            $payment->setToken($crc);

            $em->persist($payment);

            $em->flush();

            return $this->redirectToRoute(
                'account_continue_payment',
                [
                    'account' => $account->getId(),
                    'purchase' => $newPurchase->getId()
                ]
            );
        }

        return $this->render(
            'LiqsterHomePageBundle:Account:newPayment.html.twig',
            [
                'Account' => $account,
                'form' => $form->createView(),
            ]
        );
    }


    /**
     * @Route("/freeToPayed/{purchase}", name="purchase_from_free_to_payed")
     * @Method({"GET"})
     * @param Purchase $purchase
     * @return RedirectResponse
     * @throws \LogicException
     */
    public function fromFreeToPayedAction(Purchase $purchase): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();

        $account = $purchase->getAccount();

        $newPurchase = new Purchase();
        $newPurchase->setProduct($purchase->getProduct());
        $newPurchase->setAccount($purchase->getAccount());
        $newPurchase->setCreate(new \DateTime('now'));
        $newPurchase->setModification(new \DateTime('now'));
        $newPurchase->setStatus('open');
        $em->persist($newPurchase);

        $payment = new Payment();
        $payment->setCreate(new \DateTime('now'));
        $payment->setSession(Uuid::uuid4());
        $payment->setPurchase($newPurchase);

        $crc = md5($payment->getSession() . '|' . 61791 . '|' . 1 . '|' . 'PLN' . '|' . '8938c81eb462a997');
        $payment->setToken($crc);

        $em->persist($payment);

        $em->flush();

        return $this->redirectToRoute('account_continue_payment', ['account' => $account->getId(), 'purchase' => $newPurchase->getId()]);
    }
}
