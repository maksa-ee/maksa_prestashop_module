<?php

include_once(dirname(__FILE__).'/../../../config/config.inc.php');

$result = Db::getInstance()->getRow('SELECT id_order, valid FROM '._DB_PREFIX_.'orders WHERE id_cart = '.(int)Tools::getValue('id_cart').' AND secure_key = "'.pSQL(Tools::getValue('key')).'"');

if ($result) {
    if ($result['valid'] == true) {
        echo 'ok';
    } else {
        echo 'not_ok';
    }

} else {
    echo 'waiting';
}


