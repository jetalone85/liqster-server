<?php

namespace Liqster\Bundle\HomePageBundle\Command;

use Liqster\Bundle\HomePageBundle\Entity\Purchase;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InstaxerRunCommand
 *
 * @package Liqster\Bundle\HomePageBundle\Command
 */
class LiqsterPurchaseValidateCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:purchase:validate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $now = new \DateTime('now');

        $output->writeln('Current time: ' . $now->format('Y-m-d H:i:s'));

        $qb = $em->createQueryBuilder();
        $qb->select('e')
            ->from(Purchase::class, 'e')
            ->where('e.paid < :now and e.status = :status')
            ->setParameter('now', $now)
            ->setParameter('status', 'verify');

        $purchases = $qb->getQuery()->getResult();

        if ($purchases) {
            foreach ($purchases as $purchase) {
                $purchase->setStatus('old');
                $purchase->setModification($now);
                $em->merge($purchase);
            }
        }

        $em->flush();
    }
}
