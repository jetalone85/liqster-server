<?php

use Instagram\Instagram;
use Instaxer\Instaxer;
use PHPUnit\Framework\TestCase;

class InstaxerTest extends TestCase
{
    public function testConstruct()
    {
        $path = __DIR__ . '/../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);

        $this->assertEquals($instaxer->session->sessionFile, $path);
    }

    public function testLogin()
    {
        $path = __DIR__ . '/../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);

        $instaxer->login('vodefgafy', 'vodef@gafy.net');
        $this->assertEquals('APITESTUSER', $instaxer->instagram->getLoggedInUser()->getFullName());
    }

    public function testInstagram()
    {

        $path = __DIR__ . '/../../var/cache/instaxer/profiles/session.dat';
        $instaxer = new Instaxer($path);

        $instaxer->login('vodefgafy', 'vodef@gafy.net');

        $this->assertInstanceOf(Instagram::class, $instaxer->getInstagram());
    }
}
