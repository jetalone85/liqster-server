<?php

namespace Liqster\PaymentBundle\Command;

use Liqster\PaymentBundle\Domain\Przelewy24;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LiqsterPaymentsConfirmCommand
 *
 * @package Liqster\PaymentBundle\Command
 */
class LiqsterPaymentsConfirmCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:payments:confirm')
            ->setDescription('...');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $payments = $em->getRepository('LiqsterPaymentBundle:Payment')->findBy(
            [
                'verify' => null
            ]
        );

        foreach ($payments as $payment) {

            if ($payment->getP24OrderId()) {

                $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);
                $P24->addValue('p24_session_id', $payment->getSession());
                $P24->addValue('p24_amount', $payment->getPurchase()->getProduct()->getPrice());
                $P24->addValue('p24_currency', 'PLN');
                $P24->addValue('p24_order_id', $payment->getP24OrderId());

                $RES = $P24->trnVerify();

                if ($RES['error'] === '0') {

                    $now = new \DateTime('now');
                    $period = $payment->getPurchase()->getProduct()->getPeriod();

                    $payment->setVerify('ok');
                    $payment->setVerifyDate($now);

                    $payment->getPurchase()->setStatus('verify');
                    $payment->getPurchase()->setModification($now);
                    $payment->getPurchase()->setPaid($now->modify('+' . $period . ' day'));

                    $em->flush();
                }
            }
        }
    }
}
