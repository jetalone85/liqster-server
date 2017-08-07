<?php

namespace Liqster\Bundle\HomePageBundle\Controller;

use Liqster\Bundle\HomePageBundle\Entity\ApiDump;
use Liqster\Domain\MQ\MQ;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DefaultController
 *
 * @package Liqster\Bundle\HomePageBundle\Controller
 */
class ApiController extends Controller
{
    /**
     * @return Response
     * @Route("/tags", name="tags", defaults={"_format": "json"})
     * @throws \UnexpectedValueException
     * @throws \LogicException
     */
    public function tagsAction(): Response
    {
        $tags = $this->getDoctrine()->getRepository('LiqsterHomePageBundle:Tag')->findBy([], ['name' => 'ASC'], 999);

        return $this->render('LiqsterHomePageBundle:Api:tags.json.twig', ['tags' => $tags]);
    }

    /**
     * @Route("/tags/input", name="tags_input")
     * @Method({"GET", "POST"})
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function getTagsAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $test = new ApiDump();
        $test->setData($request->query->get('data'));
        $em->persist($test);
        $em->flush();

        return $this->render('LiqsterHomePageBundle:Api:ok.json.twig');
    }

    /**
     * @Route("/api/market/tags/{name}", name="tags_input")
     * @Method({"GET"})
     * @param Request $request
     * @return Response
     * @throws \LogicException
     */
    public function getApiMarketTagsAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $account = $em
            ->getRepository('LiqsterHomePageBundle:Account')
            ->findOneBy(['user' => $this->getUser()]);

        $mq = new MQ();
        $instaxer_json = $mq->query(
            'POST',
            'instaxers.json/users?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword() .
            '&user_name=' .
            $request->get('name')
        );

        $user_info = json_decode($instaxer_json->getBody()->getContents(), true);


        $instaxer_json = $mq->query(
            'POST',
            'instaxers.json/feeds?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword() .
            '&user_id=' .
            $user_info['user']['pk']
        );

        $user_feed = json_decode($instaxer_json->getBody()->getContents(), true);

        foreach ($user_feed['items'] as $item) {
            preg_match_all("/(#\w+)/", $item['caption']['text'], $samples);
            $array[] = $samples;
        }


        $array = array_merge(...array_merge(...$array));
        $array = array_count_values($array);

        return $this->render('@LiqsterHomePage/Api/marketTags.json.twig', ['response' => $array]);
    }

}
