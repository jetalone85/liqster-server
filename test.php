<?php

require_once __DIR__ . '/vendor/autoload.php';
error_reporting(E_ERROR);
ini_set('display_errors', 1);

use Liqster\PaymentBundle\Domain\Przelewy24;

$id = '23446123456f234f';

if (!isset($_GET['ok'])) {
    $P24 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

    $P24->addValue('p24_session_id', $id);
    $P24->addValue('p24_amount', 1000000);
    $P24->addValue('p24_currency', 'PLN');
    $P24->addValue('p24_email', 'sample_user@example.com');
    $P24->addValue('p24_country', 'PL');
    $P24->addValue('p24_phone', '+48500600700');
    $P24->addValue('p24_language', 'pl');
    $P24->addValue('p24_method', '1');
    $P24->addValue('p24_url_return', 'http://localhost:8080/test.php?&ok=1');
    $P24->addValue('p24_url_status', 'http://localhost:8080/test.php?&ok=2');
    $P24->addValue('p24_time_limit', 0);

    $RET = $P24->trnRegister(true);
    $order_id = $RET['token'];

} elseif ($_GET['ok'] === '1') {

    var_dump($_GET);
    var_dump($_POST);

} elseif ($_GET['ok'] === '2') {

    var_dump($_GET);
    var_dump($_POST);

} else {

    $P24_1 = new Przelewy24(61791, 61791, 'c751931d7ae41926', true);

    var_dump($_GET);
    var_dump($_POST);

    $P24_1->addValue('p24_session_id', $id);
    $P24_1->addValue('p24_order_id', $_GET['token']);
    $P24_1->addValue('p24_currency', 'PLN');
    $P24_1->addValue('p24_amount', 1000000);

    $VER = $P24_1->trnVerify();
}
