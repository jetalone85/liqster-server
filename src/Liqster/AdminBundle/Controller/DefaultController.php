<?php

namespace Liqster\AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/admin")
     */
    public function indexAction(): Response
    {
        return $this->render('@Admin/Default/index.html.twig');
    }
}
