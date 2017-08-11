<?php

namespace Cron\CronBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CronRestartCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('cron:restart')
            ->setDescription('');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();

        $cronJobs = $em->getRepository('CronCronBundle:CronJob')->findAll();

        foreach ($cronJobs as $cronJob) {
            $cronJob->setSchedule('* * * * *');
            $em->merge($cronJob);
        }

        $em->flush();
    }
}
