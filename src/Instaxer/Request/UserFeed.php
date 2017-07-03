<?php

namespace Instaxer\Request;

use Instagram\API\Response\Model\User;
use Instagram\API\Response\UserFeedResponse;
use Instaxer\Request;

/**
 * Class UserFeed
 *
 * @package Instaxer\Request
 */
class UserFeed extends Request
{
    /**
     * @param User $user
     * @return UserFeedResponse
     * @throws \Exception
     */
    public function get(User $user): UserFeedResponse
    {
        return $this->instaxer->instagram->getUserFeed($user);
    }
}
