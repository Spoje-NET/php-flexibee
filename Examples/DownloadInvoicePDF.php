#!/usr/bin/php -f
<?php
/**
 * AbraFlexi - Example how download PDF (of Invoice)
 *
 * @author     Vítězslav Dvořák <info@vitexsofware.cz>
 * @copyright  (G) 2017 Vitex Software
 */

namespace Example\AbraFlexi;

include_once './config.php';
include_once '../vendor/autoload.php';

include_once './common.php';

$invoiceID = askForFlexiBeeID();

/*
 * AbraFlexi Classes accept this form of initial identifier:
 *
 * (int) 2588
 * (string) ext:ESHOP:oi1978
 * (array) ['varSym'=>'20080015']
 */

$invoice = new \AbraFlexi\FakturaVydana($invoiceID, ['detail' => 'id']);
echo 'invoice saved to: '.$invoice->downloadInFormat('pdf', '/tmp/',
    'fakturaKB$$SUM_BEZ_QR')."\n";

echo 'post money order saved to: '.$invoice->downloadInFormat($format, '/tmp/', 'slozenkaA$$SUM')."\n";

