<?php

namespace Liqster\Bundle\AdminBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LiqsterPurchasePlusCommand
 *
 * @package Liqster\Bundle\AdminBundle\Command
 */
class LiqsterPurchasePlusCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:purchase:plus')
            ->addArgument('purchase', InputArgument::REQUIRED)
            ->setDescription('');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \InvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository('LiqsterHomePageBundle:Purchase');

        $purchase = $repository->find($input->getArgument('purchase'));

        $output->writeln($purchase->getPaid());

        $purchase->setPaid((new \DateTime('now'))->add(new \DateInterval('PT10M')));

        $output->writeln($purchase->getPaid()->format('Y-m-d H:i:s'));

        $em->merge($purchase);
        $em->flush();
    }
}
