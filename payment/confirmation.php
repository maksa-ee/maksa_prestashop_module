<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');
include_once(dirname(__FILE__).'/../../../init.php');
include_once(_PS_MODULE_DIR_.'maksa/maksa.php');
include(_PS_ROOT_DIR_.'/header.php');

$maksa = new Maksa();
$id_module = $maksa->id;
$id_cart   = Tools::getValue('id_cart');

$key = Db::getInstance()->getValue(
    'SELECT secure_key FROM '._DB_PREFIX_.'customer WHERE id_customer = '.(int)$cookie->id_customer
);

$link = new Link();

$smarty->assign(
    array(
        'id_module'  => $id_module,
        'id_cart'    => $id_cart,
        'key'        => $key,
        'maksa_link' => (
            method_exists($link, 'getPageLink')
            ?
            $link->getPageLink('order-detail.php')
            :
            _PS_BASE_URL_.'order-detail.php'
        )
    )
);
echo $maksa->display('maksa', 'payment/waiting.tpl');

include(_PS_ROOT_DIR_.'/footer.php');
