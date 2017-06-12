<?php

use Instaxer\Domain\Session;
use PHPUnit\Framework\TestCase;

class SessionTest extends TestCase
{
    public function testConstruct()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        $session = new Session($path);

        $this->assertEquals($path, $session->sessionFile);
    }

    public function testCheckExistsSessionFile()
    {
        $path = __DIR__ . '/../../../var/cache/instaxer/profiles/session.dat';
        if (file_exists($path)) {
            unlink($path);
        }

        $session = new Session($path);

        $this->assertEquals(false, $session->checkExistsSessionFile());
    }

    public function testSaveSessionAndGetSevedSession()
    {
        $path = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '../var/cache/instaxer/profiles/session.dat';

        $savedSession = '{sessionData: example}';

        $session = new Session($path);
        $session->saveSession($savedSession);

        $this->assertEquals($savedSession, $session->getSevedSession());
    }
}
