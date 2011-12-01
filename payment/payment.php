<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');

include_once(_PS_MODULE_DIR_.'maksa/maksa.php');
include_once(_PS_MODULE_DIR_.'maksa/payment/UlinkService.php');

include(_PS_ROOT_DIR_.'/header.php');

if (!$cookie->isLogged()) {
    Tools::redirect('authentication.php?back=order.php');
}

$maksa = new Maksa();

$ulinkService = new UlinkService(
    $maksa->getClientId(),
    $maksa->getPublicKey(),
    $maksa->getPrivateKey(),
    $maksa->getDefaultCurrency(),
    $maksa->getDefaultGoBackUrl($cart),
    $maksa->getDefaultResponseUrl($cart)
);

$order = array();
foreach($cart->getProducts() as $key => $product) {
    $item = array(
        'name'         => $product['name'],
        'description'  => strip_tags($product['description_short']),
        'oneItemPrice' => (string)$product['price_wt'],
        'quantity'     => $product['cart_quantity'],
    );
    $order[] = $item;
}

$shipping = $cart->getOrderTotal(true, Cart::ONLY_SHIPPING);
if ($shipping > 0) {
    $order[] = array(
        'name'         => 'Shipping',
        'description'  => '',
        'oneItemPrice' => (string)$shipping,
        'quantity'     => 1,
    );
}

$signedRequest = $ulinkService->encrypt(
    array(
        'clientTransactionId' => $cart->id,
        'amount'              => (string) $cart->getOrderTotal(true, Cart::BOTH),
        'order'               => $order
    )
);

echo $maksa->execPayment($maksa->getPaymentUrl(), $signedRequest);

include(_PS_ROOT_DIR_.'/footer.php');
