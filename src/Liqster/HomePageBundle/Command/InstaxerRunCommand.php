<?php

namespace Liqster\HomePageBundle\Command;

use Liqster\Domain\Mess\MessMinutes;
use Liqster\Domain\MQ\MQ;
use Liqster\Domain\Sleep\Sleep;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class InstaxerRunCommand
 *
 * @package Liqster\HomePageBundle\Command
 */
class InstaxerRunCommand extends ContainerAwareCommand
{
    /**
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this
            ->setName('instaxer:run')
            ->addArgument('account', InputArgument::REQUIRED);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $repository = $em->getRepository('LiqsterHomePageBundle:Account');
        $account = $repository->find($input->getArgument('account'));

        $cronJob = $em->getRepository('CronCronBundle:CronJob')->findOneBy(['name' => $account->getId()]);
        $cronJob->setSchedule(MessMinutes::messEntry($cronJob->getSchedule()));

        $em->merge($cronJob);
        $em->flush();

        if ($account->isLikesRun()) {
            $tags = explode(', ', $account->getTagsText());

            $tag = $tags[random_int(0, count($tags) - 1)];

            $mq = new MQ();
            $instaxer_json = $mq->query(
                'POST',
                'instaxers/tags?username=' .
                $account->getName() .
                '&password=' .
                $account->getPassword() .
                '&tag=' .
                $tag
            );

            $tag_feed = json_decode($instaxer_json->getBody()->getContents(), true);

            $items = array_slice($tag_feed['ranked_items'], 0, 6);

            foreach ($items as $item) {
                $response = $mq->query(
                    'POST',
                    'instaxers/likes?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword() .
                    '&id=' .
                    $item['id']);

                $output->writeln('tag: ' . $tag . '; id: ' . $item['id']);

                Sleep::random(30);
            }

            $items = array_slice($tag_feed['items'], 0, random_int(3, 9));

            foreach ($items as $item) {
                $response = $mq->query(
                    'POST',
                    'instaxers/likes?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword() .
                    '&id=' .
                    $item['id']);

                $output->writeln('tag: ' . $tag . '; id: ' . $item['id']);

                Sleep::random(30);
            }
        }

        if ($account->isCommentsRun()) {
            $tags = explode(', ', $account->getTagsText());
            $comments = explode(',', $account->getComments());

            $tag = $tags[random_int(0, count($tags) - 1)];

            $mq = new MQ();

            $instaxer_json = $mq->query(
                'POST',
                'instaxers/tags?username=' .
                $account->getName() .
                '&password=' .
                $account->getPassword() .
                '&tag=' .
                $tag
            );

            $tag_feed = json_decode($instaxer_json->getBody()->getContents(), true);

            $items = array_slice($tag_feed['items'], 0, 2);

            foreach ($items as $item) {
                $comment = $comments[random_int(0, count($tags) - 1)];

                $response = $mq->query(
                    'POST',
                    'instaxers/comments?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword() .
                    '&id=' .
                    $item['id'] .
                    '&comment=' .
                    $comment
                );

                $output->writeln('comment: ' . $tag . '; id: ' . $item['id'] . '; comment: ' . $comment);

                Sleep::random(30);
            }
        }
    }
}
