<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');

include_once(_PS_MODULE_DIR_.'maksa/maksa.php');
include_once(_PS_MODULE_DIR_.'maksa/payment/UlinkService.php');

$secureKey = Tools::getValue('secure_key');
$rawData   = Tools::getValue('signedResponse');
$response  = array('status' => 'NOTOK');

if ($rawData) {
    $maksa = new Maksa();

    $ulinkService = new UlinkService(
        $maksa->getClientId(),
        $maksa->getPublicKey(),
        $maksa->getPrivateKey()
    );

    try {
        $responseData = $ulinkService->decrypt($rawData);

        $testPayment = true;
        if (isset($responseData['isTest']) && false === $responseData['isTest']) {
            // normal payment
            $testPayment = false;
        }
        $response['isTest'] = $testPayment;

        $cart = new Cart((int)$responseData['clientTransactionId']);
        if (Validate::isLoadedObject($cart)) {

            // payment success
            if ($responseData['success']) {
                $maksa->validate(
                    (int)$responseData['clientTransactionId'],
                    Configuration::get('PS_OS_PAYMENT'),
                    (float)$responseData['amount'],
                    $testPayment ? $maksa->l('Test OK') : $maksa->l('Payment OK'),
                    $secureKey
                );
                $response['msg'] = 'Payment success.';
            }

            // payment failure
            else {
                $maksa->validate(
                    (int)$responseData['clientTransactionId'],
                    Configuration::get('PS_OS_ERROR'),
                    0,
                    ($testPayment ? $maksa->l('Test Failure') : $maksa->l('Payment Failure')) . ': errors = { ' . implode(', ', $responseData['errors']) . ' }',
                    $secureKey
                );
                $response['msg'] = 'Payment failure.';
            }

            $response['order_id'] = Order::getOrderByCartId((int)$responseData['clientTransactionId']);
            $response['status'] = 'OK';
        }
    } catch (UlinkException $e) {
        // error
        $response['msg'] = $e->getMessage();
    }
}

echo json_encode($response);
