<?php

namespace Liqster\Domain\MQ\Send;

use Liqster\Bundle\HomePageBundle\Entity\Account;
use Liqster\Domain\MQ\MQ;
use Liqster\Domain\MQ\Send;

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
