<?php

namespace Instaxer;

use FOS\RestBundle\View\View;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class Factory
 * @package Instaxer
 */
class Factory
{
    /**
     * @param $username
     * @param $password
     * @return View|Instaxer
     * @throws \Exception
     */
    public static function create($username, $password)
    {
        if (empty($username) || empty($password)) {
            return new View('NULL VALUES ARE NOT ALLOWED', Response::HTTP_NOT_ACCEPTABLE);
        }

        $fs = new Filesystem();
        $fs->mkdir('./instaxer/profiles');

        $path = './instaxer/profiles/' . $username . '.dat';
        $instaxer = new Instaxer($path);
        $instaxer->login($username, $password);

        return $instaxer;
    }
}
