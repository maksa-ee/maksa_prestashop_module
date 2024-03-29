<?php
 
class Maksa extends PaymentModule
{
    private $_html       = '';
    private $_postErrors = array();

    protected $paymentUrl         = 'http://maksa.ee/pay';
    protected $clientId           = null;
    protected $publicKey          = null;
    protected $privateKey         = null;
    protected $defaultCurrency    = 'EUR';

    public function getPaymentUrl()
    {
        return $this->paymentUrl;
    }

    public function getClientId()
    {
        return $this->clientId;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    public function getDefaultCurrency()
    {
        return $this->defaultCurrency;
    }

    public function getDefaultGoBackUrl(Cart $cart)
    {
        return implode(
            array(
                'http://'.Configuration::get('PS_SHOP_DOMAIN').__PS_BASE_URI__,
                'modules/maksa/payment/',
                'confirmation.php',
                '?id_cart='.$cart->id,
            )
        );
    }

    public function getDefaultResponseUrl(Cart $cart)
    {
        return implode(
            array(
                'http://'.Configuration::get('PS_SHOP_DOMAIN').__PS_BASE_URI__,
                'modules/maksa/payment/',
                'response.php',
                '?secure_key='.$cart->secure_key,
            )
        );
    }

    protected $configurationKeys  = array(
        'MAKSA_PAYMENT_URL',
        'MAKSA_CLIENT_ID',
        'MAKSA_PUBLIC_KEY',
        'MAKSA_PRIVATE_KEY',
    );

    protected $maksaTrans = array();

    public function __construct()
    {
        $this->name    = 'maksa';
        $this->tab     = 'payments_gateways';
        $this->author  = 'Cravler';
        $this->version = '0.1';

        $this->initConfig();
        parent::__construct();

        $this->page = basename(__FILE__, '.php');
        $this->displayName = $this->l('Maksa.ee');
        $this->description = $this->l('Maksa.ee ...');
    }

    protected function getSmarty()
    {
        global $smarty;

        return $smarty;
    }

    protected function initConfig()
    {
        $config = Configuration::getMultiple($this->configurationKeys);

        $this->maksaTrans = array(
            'payment_url' => $this->l('Payment URL'),
            'client_id'   => $this->l('Client ID'),
            'public_key'  => $this->l('Maksa Public Key'),
            'private_key' => $this->l('Your Private Key'),
        );

        foreach ($this->configurationKeys as $key) {
            $valKey = strtolower(str_replace('MAKSA_', '', $key));
            $defKey = explode('_', $valKey);
            foreach($defKey as $index => &$str) {
                if ($index) {
                    $str = ucfirst($str);
                }
            };
            $defKey = implode($defKey);

            if (isset($config[$key])) {
                $this->{$defKey} = $config[$key];
            }
        }
    }

    public function install()
    {
        if (
            !parent::install()
            OR !$this->registerHook('payment')
            OR !$this->registerHook('orderConfirmation')
        ) {
            return false;
        }

        return true;
    }

    public function uninstall()
    {
        /* Delete all configurations */
        foreach ($this->configurationKeys as $key) {
            Configuration::deleteByName($key);
        }

        return parent::uninstall();
    }

    public function hookPayment($params)
    {
        if (!$this->active) {
            return ;
        }

        $this->getSmarty()->assign('logo', _MODULE_DIR_.$this->name.'/maksa30.png');
        $this->getSmarty()->assign('js_path', _MODULE_DIR_.$this->name.'/js');
        $this->getSmarty()->assign('images_path', _MODULE_DIR_.$this->name.'/images');
        return $this->display(__FILE__, 'maksa.tpl');
    }

    public function execPayment($bank,$paymentUrl, $signedRequest)
    {
        if (!$this->active) {
            return;
        }

        if (!preg_match('/^[a-z]+$/', $bank)) {
            $bank = '';
        }

        $this->getSmarty()->assign(
            array(
                'bank' => $bank,
                'maksa_url'      => $paymentUrl,
                'signed_request' => $signedRequest,
            )
        );

        return $this->display(__FILE__, 'payment/payment.tpl');
    }

    public function validate($id_cart, $id_order_state, $amount, $message = '', $secure_key)
    {
        $this->validateOrder(
            (int)$id_cart,
            $id_order_state,
            $amount,
            $this->displayName,
            $message,
            NULL,
            NULL,
            true,
            pSQL($secure_key)
        );
    }

    /*
     * Settings
     */
    private function _postValidation()
    {
        if (Tools::isSubmit('btnSubmit')) {
            foreach ($this->configurationKeys as $key) {
                $valKey = strtolower(str_replace('MAKSA_', '', $key));
                if (!Tools::getValue($valKey)) {
                    $this->_postErrors[] = $this->l('Required: ') . (isset($this->maksaTrans[$valKey]) ? $this->maksaTrans[$valKey] : $valKey);
                    return;
                }
            }
        }
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('btnSubmit')) {
            foreach ($this->configurationKeys as $key) {
                $valKey = strtolower(str_replace('MAKSA_', '', $key));
                Configuration::updateValue($key, Tools::getValue($valKey));
            }
        }

        $this->_html .= '<div class="conf confirm"><img src="../img/admin/ok.gif" alt="'.$this->l('ok').'" /> '.$this->l('Settings updated').'</div>';
    }

    private function _displayForm()
    {
        $this->_html .=
        '<form action="'.Tools::htmlentitiesUTF8($_SERVER['REQUEST_URI']).'" method="post">
            <fieldset>
            <legend><img src="../img/admin/contact.gif" />'.$this->l('Maksa details').'</legend>
                <table border="0" width="500" cellpadding="0" cellspacing="0" id="form">';

                    foreach ($this->configurationKeys as $key) {
                        $valKey = strtolower(str_replace('MAKSA_', '', $key));
                        $defKey = explode('_', $valKey);
                        foreach($defKey as $index => &$str) {
                            if ($index) {
                                $str = ucfirst($str);
                            }
                        };
                        $defKey = implode($defKey);

                        $value  = htmlentities(Tools::getValue($valKey, $this->{$defKey}), ENT_COMPAT, 'UTF-8');

                        if (!in_array($key, array('MAKSA_PUBLIC_KEY', 'MAKSA_PRIVATE_KEY'))) {
                        $this->_html .= '
                    <tr>
                        <td width="130" style="height: 35px;">'.(isset($this->maksaTrans[$valKey]) ? $this->maksaTrans[$valKey] : $valKey).'</td>
                        <td>
                            <input type="text" name="'.$valKey.'" value="'.$value.'" style="width: 300px;" />
                        </td>
                    </tr>';
                        } else {
                        $this->_html .= '
                    <tr>
                        <td width="130" style="vertical-align: top;">'.(isset($this->maksaTrans[$valKey]) ? $this->maksaTrans[$valKey] : $valKey).'</td>
                        <td style="padding-bottom: 15px;">
                            <textarea name="'.$valKey.'" rows="4" cols="53">'.$value.'</textarea>
                        </td>
                    </tr>';
                        }
                    }

                    $this->_html .= '
                    <tr>
                        <td colspan="2" align="center">
                            <input class="button" name="btnSubmit" value="'.$this->l('Update settings').'" type="submit" />
                        </td>
                    </tr>
                </table>
            </fieldset>
        </form>';
    }

    public function getContent()
    {
        $this->_html = '<h2>'.$this->displayName.'</h2>';

        if (Tools::isSubmit('btnSubmit')) {
            $this->_postValidation();
            if (!sizeof($this->_postErrors)) {
                $this->_postProcess();
            }
            else {
                foreach ($this->_postErrors AS $err) {
                    $this->_html .= '<div class="alert error">'. $err .'</div>';
                }
            }
        }
        else {
            $this->_html .= '<br />';
        }

        $this->_displayForm();

        return $this->_html;
    }
}