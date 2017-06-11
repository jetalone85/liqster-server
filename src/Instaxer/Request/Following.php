<?php

namespace Instaxer\Request;

/**
 * Class Following
 * @package Instaxer\Request
 */
class Following
{
    /**
     * @var
     */
    private $instaxer;

    /**
     * Following constructor.
     * @param $instaxer
     */
    public function __construct($instaxer)
    {
        $this->instaxer = $instaxer;
    }

    /**
     * @param $user
     * @return array
     */
    public function getFollowing($user): array
    {
        $followingCount = $this->instaxer->instagram->getUserInfo($user)->getUser()->getFollowingCount();

        $array = [];
        $counter = ceil($followingCount / 200);

        $lastId = $user;

        for ($i = 1; $i <= $counter; $i++) {
            $fall = $this->instaxer->instagram->getUserFollowing($user, $lastId);
            $lastId = $fall->getNextMaxId();
            $array = array_merge($array, $fall->getFollowers());
        }

        return $array;
    }
}
