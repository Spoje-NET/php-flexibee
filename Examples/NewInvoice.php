#!/usr/bin/php -f
<?php
/**
 * AbraFlexi - Example how to create Invoice
 *
 * @author     Vítězslav Dvořák <info@vitexsofware.cz>
 * @copyright  (G) 2018 Vitex Software
 */

namespace Example\AbraFlexi;

use \AbraFlexi\RO;
use \AbraFlexi\FakturaVydana;

define('EASE_LOGGER', 'syslog|console');
include_once './config.php';
include_once '../vendor/autoload.php';

/**
 * Prepare Testing Invoice
 * 
 * @param array $initialData
 * 
 * @return FakturaVydana
 */
function makeInvoice($initialData = [], $dayBack = 1, $evidence = 'vydana')
{
    $yesterday = new \DateTime();
    $yesterday->modify('-'.$dayBack.' day');
    $testCode  = 'INV_'.\Ease\Sand::randomString();
    $invoice   = new FakturaVydana(null, ['evidence' => 'faktura-'.$evidence]);
    $invoice->takeData(array_merge([
        'kod' => $testCode,
        'varSym' => \Ease\Sand::randomNumber(1111, 9999),
        'specSym' => \Ease\Sand::randomNumber(111, 999),
        'bezPolozek' => true,
        'popis' => 'php-abraflexi-matcher Test invoice',
        'datVyst' => RO::dateToFlexiDate($yesterday),
        'typDokl' => RO::code('FAKTURA')
            ], $initialData));
    if ($invoice->sync()) {
        $invoice->addStatusMessage($invoice->getApiURL().' '.
            RO::uncode($invoice->getDataValue('typDokl')).' '.
            RO::uncode($invoice->getRecordIdent()).' '.
            RO::uncode($invoice->getDataValue('sumCelkem')).' '.
            RO::uncode($invoice->getDataValue('mena')), 'success');
    } else {
        $invoice->addStatusMessage(json_encode($invoice->getData()), 'debug');
    }

    return $invoice;
}
$invoice = makeInvoice(['id' => 'ext:example:1']);


//Set due date by DateTime object
$nextMonth = new \DateTime();
$nextMonth->modify('+1 month');
$invoice->setDataValue('datSplat', $nextMonth);

$invoice->addStatusMessage(_('Invoice').': '.$invoice->getRecordIdent().' '.$invoice->getRecordCode(),
    $invoice->sync() ? 'success' : 'error' );
