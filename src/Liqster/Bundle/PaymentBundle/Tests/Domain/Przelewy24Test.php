<?php

namespace Liqster\Bundle\PaymentBundle\Tests\Domain;

use Liqster\Bundle\PaymentBundle\Domain\Przelewy24;
use PHPUnit\Framework\TestCase;

/**
 * Class Przelewy24Test
 *
 * @package Liqster\Bundle\PaymentBundle\Tests\Domain
 */
class Przelewy24Test extends TestCase
{
    public function testConstructor()
    {
        $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

        $this->assertInstanceOf(Przelewy24::class, $P24);
    }

    public function testConnection()
    {
        $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);
        $RET = $P24->testConnection();

        $this->assertEquals('0', $RET['error']);
    }

    public function testTransactionRegister()
    {
        $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

        $P24->addValue('p24_session_id', md5((new \DateTime('now'))->format('Y-m-d H:i:s')));
        $P24->addValue('p24_amount', 1);
        $P24->addValue('p24_currency', 'PLN');
        $P24->addValue('p24_email', 'sample_user@example.com');
        $P24->addValue('p24_country', 'PL');
        $P24->addValue('p24_phone', '+48500600700');
        $P24->addValue('p24_language', 'pl');
        $P24->addValue('p24_method', '1');
        $P24->addValue('p24_url_return', 'http://example.com');
        $P24->addValue('p24_url_status', 'http://example.com');
        $P24->addValue('p24_time_limit', 0);

        $RET = $P24->trnRegister();

        $this->assertEquals('0', $RET['error']);
        $this->assertEquals(true, is_string($RET['token']));
    }

    public function testTransactionVerificate()
    {
        $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

        $id = md5((new \DateTime('now'))->format('Y-m-d H:i:s'));

        $P24->addValue('p24_session_id', $id);
        $P24->addValue('p24_amount', 100);
        $P24->addValue('p24_currency', 'PLN');
        $P24->addValue('p24_email', 'sample_user@example.com');
        $P24->addValue('p24_country', 'PL');
        $P24->addValue('p24_phone', '+48500600700');
        $P24->addValue('p24_language', 'pl');
        $P24->addValue('p24_method', '1');
        $P24->addValue('p24_url_return', 'http://example.com');
        $P24->addValue('p24_url_status', 'http://example.com');
        $P24->addValue('p24_time_limit', 0);

        $RET = $P24->trnRegister();

        $order_id = $RET['token'];

        $P24_1 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

        $P24_1->addValue('p24_session_id', $id);
        $P24_1->addValue('p24_order_id', $order_id);
        $P24_1->addValue('p24_currency', 'PLN');
        $P24_1->addValue('p24_amount', 100);

        $VER = $P24_1->trnVerify();

        $this->assertEquals('0', $VER['error']);
    }
}
