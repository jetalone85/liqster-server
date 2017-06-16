<?php

use Instaxer\Instaxer;
use Instaxer\Request\UserFeed;
use PHPUnit\Framework\TestCase;

class UserFeedTest extends TestCase
{
    public function testUserFeed()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);
        $instaxer->login('lucy@lucyu.com', 'lucyu@');

        $user = $instaxer->instagram->getUserByUsername('instagram');
        $request = new UserFeed($instaxer);

        $userFeed = $request->get($user);

        $this->assertEquals('ok', $userFeed->getStatus());
    }
}
