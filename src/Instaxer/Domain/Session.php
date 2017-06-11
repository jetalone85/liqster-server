<?php

namespace Instaxer\Domain;

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
        return file_get_contents($this->sessionFile);
    }

    /**
     * @param $savedSession
     */
    public function saveSession($savedSession)
    {
        file_put_contents($this->sessionFile, $savedSession);
    }
}
