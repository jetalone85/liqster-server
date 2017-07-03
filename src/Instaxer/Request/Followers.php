<?php

namespace Instaxer\Request;

/**
 * Class Followers
 *
 * @package Instaxer\Request
 */
class Followers
{
    /**
     * @var
     */
    private $instaxer;

    /**
     * Followers constructor.
     *
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
    public function getFollowers($user): array
    {
        $followersCount = $this->instaxer->instagram->getUserInfo($user)->getUser()->getFollowerCount();

        $array = [];
        $counter = ceil($followersCount / 200);

        $lastId = $user;

        for ($i = 1; $i <= $counter; $i++) {
            $fall = $this->instaxer->instagram->getUserFollowers($user, $lastId);
            $lastId = $fall->getNextMaxId();
            $array = array_merge($array, $fall->getFollowers());
        }

        return $array;
    }
}
