<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Instaxer\Factory;
use Instaxer\Instaxer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class InstaxerController
 * @package AppBundle\Controller
 */
class InstaxerController extends FOSRestController
{
    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));

        return new View($instaxer, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerInfoAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $info = $instaxer->instagram->getUserInfo($instaxer->instagram->getLoggedInUser());

        return new View($info, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerTagAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $tagFeed = $instaxer->instagram->getTagFeed($request->get('tag'));

        return new View($tagFeed, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerFeedAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $userFeed = $instaxer->instagram->getUserFeed($request->get('user_id'));

        return new View($userFeed, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerUserAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $userInfo = $instaxer->instagram->getUserInfo($instaxer->instagram->getUserByUsername($request->get('user_name')));

        return new View($userInfo, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerLikeAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $response = $instaxer->instagram->likeMedia($request->get('id'));

        return new View($response, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerCommentAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));
        $response = $instaxer->instagram->commentOnMedia($request->get('id'), $request->get('comment'));

        return new View($response, Response::HTTP_OK);
    }

    /**
     * @param Request $request
     * @return View|Instaxer
     * @throws \Exception
     */
    public function postInstaxerThisAction(Request $request)
    {
        $instaxer = Factory::create($request->get('username'), $request->get('password'));

        return new View($instaxer, Response::HTTP_OK);
    }
}
