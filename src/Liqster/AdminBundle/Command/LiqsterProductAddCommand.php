<?php

namespace Liqster\AdminBundle\Command;

use Liqster\HomePageBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LiqsterProductAddCommand
 *
 * @package Liqster\AdminBundle\Command
 */
class LiqsterProductAddCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:product:add')
            ->setDescription('...');
    }

    /**
     * @param InputInterface  $input
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

        $product = new Product();
        $product->setCreate(new \DateTime('now'));
        $product->setModification(new \DateTime('now'));
        $product->setPeriod(10);
        $product->setPrice(1900);
        $product->setType('10 dni');
        $product->setDescription('0');
        $product->setImage('0');
        $product->setStatus('0');

        $em->persist($product);
        $em->flush();
    }
}
