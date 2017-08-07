<?php

namespace Liqster\Bundle\HomePageBundle\Command;

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
 * @package Liqster\Bundle\HomePageBundle\Command
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
            $tags = explode(',', $account->getTagsText());

            $tag = $tags[random_int(0, count($tags) - 1)];
            $tag = str_replace(' ', '', $tag);
            $tag = str_replace('#', '', $tag);

            $mq = new MQ();
            $instaxer_json = $mq->query(
                'POST',
                'instaxers/tags.json?username=' .
                $account->getName() .
                '&password=' .
                $account->getPassword() .
                '&tag=' .
                $tag
            );

            $tag_feed = json_decode($instaxer_json->getBody()->getContents(), true);

//            $output->writeln('Ranked:');
//            $items = array_slice($tag_feed['ranked_items'], 0, random_int(1, 3));
//
//            foreach ($items as $item) {
//                $response = $mq->query(
//                    'POST',
//                    'instaxers/likes.json?username=' .
//                    $account->getName() .
//                    '&password=' .
//                    $account->getPassword() .
//                    '&id=' .
//                    $item['id']
//                );
//
//                $output->writeln('tag: ' . $tag . '; id: ' . $item['id']);
//
//                Sleep::random(15);
//            }

            $output->writeln('Normal:');
            $items = array_slice($tag_feed['items'], 0, random_int(1, 3));

            foreach ($items as $item) {
                $response = $mq->query(
                    'POST',
                    'instaxers/likes.json?username=' .
                    $account->getName() .
                    '&password=' .
                    $account->getPassword() .
                    '&id=' .
                    $item['id']
                );

                $output->writeln('tag: ' . $tag . '; id: ' . $item['id']);

                Sleep::random(15);
            }
        }

//        if ($account->isCommentsRun()) {
//            $tags = explode(', ', $account->getTagsText());
//            $comments = explode(',', $account->getComments());
//
//            for ($i = 1; $i <= 3; $i++) {
//                $tag = $tags[random_int(0, count($tags) - 1)];
//
//                $mq = new MQ();
//
//                $instaxer_json = $mq->query(
//                    'POST',
//                    'instaxers/tags.json?username=' .
//                    $account->getName() .
//                    '&password=' .
//                    $account->getPassword() .
//                    '&tag=' .
//                    $tag
//                );
//
//                $tag_feed = json_decode($instaxer_json->getBody()->getContents(), true);
//
//                $items = array_slice($tag_feed['items'], 0, 1);
//
//                foreach ($items as $item) {
//                    $comment = $comments[random_int(0, count($tags) - 1)];
//
//                    $response = $mq->query(
//                        'POST',
//                        'instaxers/comments.json?username=' .
//                        $account->getName() .
//                        '&password=' .
//                        $account->getPassword() .
//                        '&id=' .
//                        $item['id'] .
//                        '&comment=' .
//                        $comment
//                    );
//
//                    $output->writeln('comment: ' . $tag . '; id: ' . $item['id'] . '; comment: ' . $comment);
//
//                    Sleep::random(10);
//                }
//            }
//        }
    }
}
