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
        $instaxer->login('vodefgafy', 'vodef@gafy.net');

        $request = new PublishPhoto($instaxer);
        $result = $request->pull(__DIR__ . '/test.jpg', 'test');

        $this->assertEquals('ok', $result->getStatus());
    }
}
