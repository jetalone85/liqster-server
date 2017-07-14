<?php

namespace Liqster\HomePageBundle\Command;

use Liqster\Domain\MQ\MQ;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class InstaxerRunExternalCommand
 *
 * @package Liqster\HomePageBundle\Command
 */
class InstaxerRunExternalCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('instaxer:run:ext')
            ->addArgument('account', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cd = $this->getContainer()->get('kernel')->getCacheDir();
        $em = $this->getContainer()->get('doctrine');
        $fs = new Filesystem();
        $mq = new MQ();

        $test = $mq->query(
            'POST',
            'instaxers/tests'
        );

        $output->writeln('Command result.');
    }
}
