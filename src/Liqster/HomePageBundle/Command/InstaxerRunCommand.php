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

        $counter = 5;
        $long = 5;

        $tags = ['photo', 'photos', 'picture', 'photographer', 'pictures', 'snapshot', 'art', 'beautiful', 'instagood', 'picoftheday', 'photooftheday', 'color', 'all_shots', 'exposure', 'composition', 'focus', 'capture', 'moment', 'photoshoot', 'photodaily', 'photogram',
            'follow', 'like4like', 'love', 'instagood', 'photooftheday', 'tbt', 'cute', 'beautiful', 'me', 'followme', 'happy', 'follow', 'fashion', 'selfie', 'picoftheday', 'like4like', 'girl', 'tagsforlikes', 'instadaily', 'friends', 'summer', 'fun', 'smile', 'igers', 'instalike', 'likeforlike', 'repost', 'food', 'instamood', 'follow4follow', 'art', 'style'];


        $likeRepository = new RunLikeRepository(new ItemRepository($tags), $instaxer, $counter, $long);
        $likeRepository->run();

        $output->writeln('Command result.');
    }
}
