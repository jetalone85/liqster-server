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
            ->setDescription('...')
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

        $counter = 20;
        $long = 10;

        $tags = ['love', 'TagsForLikes', 'TagsForLikesApp', 'TFLers', 'tweegram', 'photooftheday', '20likes', 'amazing', 'smile', 'follow4follow', 'like4like', 'look', 'instalike'];

        $likeRepository = new RunLikeRepository(new ItemRepository($tags), $instaxer, $counter, $long);
        $likeRepository->run();

        $output->writeln('Command result.');
    }
}
