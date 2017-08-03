<?php

namespace Liqster\Bundle\AdminBundle\Command;

use Liqster\Bundle\HomePageBundle\Entity\DiscountCode;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LiqsterProductAddCommand
 *
 * @package Liqster\Bundle\AdminBundle\Command
 */
class LiqsterDiscountAddCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:discount:add')
            ->setDescription('Add first sample product to database');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $discount = new DiscountCode();
        $discount->setCreate(new \DateTime('now'));
        $discount->setModification(new \DateTime('now'));
        $discount->setKey('polowa');
        $discount->setPromotion(0.5);
        $em->persist($discount);
        $em->flush();
    }
}
