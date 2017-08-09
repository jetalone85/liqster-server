<?php

namespace Liqster\Bundle\StatisticBundle\Command;

use Liqster\Bundle\StatisticBundle\Entity\Statistic;
use Liqster\Domain\MQ\MQ;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class LiqsterStatisticGetCommand
 * @package Liqster\Bundle\StatisticBundle\Command
 */
class LiqsterStatisticGetCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('liqster:statistic:get')
            ->addArgument('account', InputArgument::OPTIONAL);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository('LiqsterHomePageBundle:Account');
        $account = $repository->find($input->getArgument('account'));

        $mq = new MQ();
        $instaxer_json = $mq->query(
            'POST',
            'instaxers/infos.json?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword()
        );

        if ($instaxer_json) {
            $output->writeln('Get statistics to account: ' . $input->getArgument('account'));
        }

        $statistic = new Statistic();
        $statistic->setAccount($account);
        $statistic->setCreate(new \DateTime('now'));
        $statistic->setContent($instaxer_json->getBody()->getContents());

        $em->persist($statistic);
        $em->flush();

        $output->writeln('<info>Saved: ' . $statistic->getCreate()->format('Y-m-d H:i:s') . '</info>');
    }
}
