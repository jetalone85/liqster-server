<?php

namespace Liqster\Domain\MQ\Send;

use Liqster\Domain\MQ\MQ;
use Liqster\Domain\MQ\Send;
use Liqster\HomePageBundle\Entity\Account;

class Like extends Send
{
    public static function send(MQ $MQ, Account $account, $item)
    {
        $response = $MQ->query(
            'POST',
            'instaxers/likes?username=' .
            $account->getName() .
            '&password=' .
            $account->getPassword() .
            '&id=' .
            $item['id']);

        return $response;
    }
}
