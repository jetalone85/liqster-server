<?php

namespace Instaxer;

use Instagram\API\Framework\InstagramException;
use Instagram\Instagram;
use Instaxer\Domain\Session;

/**
 * Class Instaxer
 * @package Instaxer
 */
class Instaxer
{
    /**
     * @var Instagram
     */
    public $instagram;

    /**
     * @var Session
     */
    public $session;

    /**
     * Instaxer constructor.
     * @param $sessionFilePath
     */
    public function __construct($sessionFilePath)
    {
        $this->instagram = new Instagram();
        $this->instagram->setVerifyPeer(false);

        $this->session = new Session($sessionFilePath);
        $this->session->restoredFromSession = FALSE;
    }

    /**
     * @param string $user
     * @param string $password
     * @return $this
     * @throws \Exception
     */
    public function login(string $user, string $password)
    {
        if ($this->session->checkExistsSessionFile()) {
            try {
                $savedSession = $this->session->getSevedSession();

                if ($savedSession !== FALSE) {
                    $this->instagram->initFromSavedSession($savedSession);
                    $currentUser = $this->instagram->getCurrentUserAccount();
                    if ($currentUser->getUser()->getUsername() === $user) {
                        $this->session->restoredFromSession = TRUE;
                    }
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
            }
        }

        if (!$this->session->restoredFromSession) {
            try {
                $this->instagram->login($user, $password);
                $savedSession = $this->instagram->saveSession();
                $this->session->saveSession($savedSession);
            } catch (InstagramException $e) {
                throw new InstagramException('Error login', 1);
            }
        }

        return $this;
    }

    /**
     * @return Instagram
     */
    public function getInstagram(): Instagram
    {
        return $this->instagram;
    }
}
