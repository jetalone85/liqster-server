<?php

use Instaxer\Instaxer;
use Instaxer\Request\TagFeed;
use PHPUnit\Framework\TestCase;

class TagFeedTest extends TestCase
{
    public function testTagFeed()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);
        $instaxer->login('vodefgafy', 'vodef@gafy.net');

        $tag = 'instagram';
        $request = new TagFeed($instaxer);

        $tagFeed = $request->get($tag);

        $this->assertEquals('ok', $tagFeed->getStatus());
    }
}
