<?php

namespace Liqster\HomePageBundle\Command;

use Instaxer\Bot\RunLikeRepository;
use Instaxer\Domain\Model\ItemRepository;
use Instaxer\Instaxer;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class InstaxerRunCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('instaxer:run')
            ->addArgument('account', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cacheDir = $this->getContainer()->get('kernel')->getCacheDir();

        $repository = $this->getContainer()->get('doctrine')->getRepository('LiqsterHomePageBundle:Account');
        $account = $repository->find($input->getArgument('account'));

        $fs = new Filesystem();
        $fs->mkdir($cacheDir . '/instaxer/profiles/' . $account->getUser());

        $path = $cacheDir . '/instaxer/profiles/' . $account->getUser() . DIRECTORY_SEPARATOR . $account->getId() . '.dat';

        $instaxer = new Instaxer($path);
        $instaxer->login($account->getName(), $account->getPassword());

        $counter = 8;
        $long = 5;

        $tags = explode(',', $account->getTags());

        $likeRepository = new RunLikeRepository(new ItemRepository($tags), $instaxer, $counter, $long);
        $likeRepository->run();

        $output->writeln('Command result.');
    }
}
