<?php

namespace Instaxer\Domain;

use Symfony\Component\Filesystem\Filesystem;

/**
 * Class Session
 * @package Instaxer\Domain
 */
final class Session
{
    /**
     * @var
     */
    public $restoredFromSession;

    /**
     * @var
     */
    public $sessionFile;

    /**
     * Session constructor.
     * @param $sessionFile
     */
    public function __construct($sessionFile)
    {
        $this->sessionFile = $sessionFile;
    }

    /**
     * @return bool
     */
    public function checkExistsSessionFile(): bool
    {
        return is_file($this->sessionFile);
    }

    /**
     * @return string
     */
    public function getSevedSession(): string
    {
        if (is_writable(dirname($this->sessionFile))) {
            return file_get_contents($this->sessionFile);
        }
    }

    /**
     * @param $savedSession
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function saveSession($savedSession)
    {
        $fs = new Filesystem();

        if (file_exists($this->sessionFile)) {
            unlink($this->sessionFile);
        } else {
            $fs->mkdir(dirname($this->sessionFile));
        }

        $fs->dumpFile($this->sessionFile, $savedSession);
    }
}
