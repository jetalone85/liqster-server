<?php

use PHPUnit\Framework\TestCase;

class RepostPhotoTest extends TestCase
{
    public function testExec()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new \Instaxer\Instaxer($path);
        $instaxer->login('lucy@lucyu.com', 'lucyu@');

        $request = new \Instaxer\Bot\RepostPhoto($instaxer);
        $result = $request->exec('instagram');

        $this->assertEquals('ok', $result->getStatus());
    }
}
