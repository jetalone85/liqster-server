<?php

namespace Instaxer\Bot;

use Instaxer\Domain\Model\ItemRepository;
use Instaxer\Instaxer;

class RunPunchAccounts
{
    private $repository;
    private $instaxer;

    /**
     * RunLikeRepository constructor.
     * @param ItemRepository $repository
     * @param Instaxer $instaxer
     */
    public function __construct(ItemRepository $repository, Instaxer $instaxer)
    {
        $this->repository = $repository;
        $this->instaxer = $instaxer;
    }

    public function run()
    {
        $item = $this->repository->getRandomItem();

        echo sprintf('@%s: ' . "\r\n", $item->getItem());

        $usersFeed = $this->instaxer->instagram->getUserFeed($this->instaxer->instagram->getUserByUsername($item->getItem()));

        $usersItems = array_slice($usersFeed->getItems(), 0, 10);


        foreach ($usersItems as $usersItem) {
            $comments = $usersItem->getComments();

            foreach ($comments as $comment) {
                $commentUser = $comment->getUser();
                echo sprintf("---------- Comment ----------\n");
                echo sprintf("User: %s [%s]\n", $commentUser->getFullName(), $commentUser->getUsername());
                echo sprintf("Text: %s\n", $comment->getText());
                echo sprintf("--------- \\Comment ----------\n");


                $singleUserFeed = $this->instaxer->instagram->getUserFeed($commentUser);

                foreach (array_slice($singleUserFeed->getItems(), 0, 3) as $hashTagFeedItem) {

                    $id = $hashTagFeedItem->getId();
                    $user = $this->instaxer->instagram->getUserInfo($hashTagFeedItem->getUser())->getUser();
                    $followRatio = $user->getFollowerCount() / $user->getFollowingCount();

                    echo sprintf('User: %s; ', $user->getUsername());
                    echo sprintf('id: %s,  ', $id);
                    echo sprintf('followers: %s,  ratio: %s, ', $user->getFollowerCount(), round($followRatio, 1));

                    $likeCount = $hashTagFeedItem->getLikeCount();
                    $commentCount = $hashTagFeedItem->getCommentCount();

                    echo sprintf('photo: %s/%s ', $likeCount, $commentCount);

                    if ($user->getFollowingCount() > 50) {
                        $this->instaxer->instagram->likeMedia($hashTagFeedItem->getID());
                        echo sprintf('[liked] ');
                    }

                    echo sprintf("\r\n");
                }

            }
        }
    }
}
