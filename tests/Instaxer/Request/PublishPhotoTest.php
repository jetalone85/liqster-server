<?php

use Instaxer\Instaxer;
use Instaxer\Request\PublishPhoto;
use PHPUnit\Framework\TestCase;

class PublishPhotoTest extends TestCase
{
    public function testPublishPhoto()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);
        $instaxer->login('lucy@lucyu.com', 'lucyu@');

        $request = new PublishPhoto($instaxer);
        $result = $request->pull(__DIR__ . '/test.jpg', 'ModelTest');

        $this->assertEquals('ok', $result->getStatus());
    }
}
