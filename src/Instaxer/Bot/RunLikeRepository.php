<?php

namespace Instaxer\Bot;

use Instaxer\Domain\Model\ItemRepository;
use Instaxer\Instaxer;

class RunLikeRepository
{
    private $repository;
    private $instaxer;
    private $counter;
    private $long;

    /**
     * RunLikeRepository constructor.
     *
     * @param ItemRepository $repository
     * @param Instaxer $instaxer
     * @param int $counter
     * @param int $long
     */
    public function __construct(ItemRepository $repository, Instaxer $instaxer, int $counter = 1, int $long = 1)
    {
        $this->repository = $repository;
        $this->instaxer = $instaxer;
        $this->counter = $counter;
        $this->long = $long;
    }

    public function run()
    {
        for ($c = 0; $c < $this->counter; $c++) {
            $item = $this->repository->getRandomItem();

            echo sprintf('#%s: ' . "\r\n", $item->getItem());

            $hashTagFeed = $this->instaxer->instagram->getTagFeed($item->getItem());
            $items = array_slice($hashTagFeed->getItems(), 0, $this->long);

            foreach ($items as $hashTagFeedItem) {

                $id = $hashTagFeedItem->getId();
                $user = $this->instaxer->instagram->getUserInfo($hashTagFeedItem->getUser())->getUser();
                $followRatio = $user->getFollowerCount() / $user->getFollowingCount();

                echo sprintf('User: %s; ', $user->getUsername());
                echo sprintf('id: %s,  ', $id);
                echo sprintf('followers: %s,  ratio: %s, ', $user->getFollowerCount(), round($followRatio, 1));

                $likeCount = $hashTagFeedItem->getLikeCount();
                $commentCount = $hashTagFeedItem->getCommentCount();

                echo sprintf('photo: %s/%s ', $likeCount, $commentCount);

                if ($user->getFollowingCount() > 100) {
                    $this->instaxer->instagram->likeMedia($hashTagFeedItem->getID());
                    echo sprintf('[liked] ');
                }

                echo sprintf("\r\n");
            }
        }
    }
}
