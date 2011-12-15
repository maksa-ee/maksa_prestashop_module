<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');

$result = Db::getInstance()->getRow('SELECT id_order, valid FROM '._DB_PREFIX_.'orders WHERE id_cart = '.(int)Tools::getValue('id_cart').' AND secure_key = "'.pSQL(Tools::getValue('key')).'"');

if ($result) {
    $response = array(
        'status'   => $result['valid'] == true ? 'ok' : 'not_ok',
        'id_order' => $result['id_order']
    );
} else {
    $response = array(
        'status'   => 'waiting',
        'id_order' => null
    );
}

echo json_encode($response);


