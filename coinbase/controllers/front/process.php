<?php

if (!defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'coinbase/classes/OrderManager.php';
    require_once _PS_MODULE_DIR_ . 'coinbase/vendor/CoinbaseSDK/init.php';
    require_once _PS_MODULE_DIR_ . 'coinbase/vendor/CoinbaseSDK/const.php';
}

class CoinbaseProcessModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        // Check that payment module is active, to prevent users from 
        // calling this controller when payment method is inactive. 
        if (!$this->isModuleActive()) {
            die($this->module->l('This payment method is not available.', 'payment'));
        }

        $cart = $this->context->cart;
        $customer = new Customer($cart->id_customer);
        $total = OrderManager::getCartTotal($cart);

        $this->module->validateOrder(
            $cart->id,
            Configuration::get('COINBASE_NEW'),
            $total,
            $this->module->displayName,
            null,
            null,
            (int)$cart->id_currency,
            false,
            $customer->secure_key
        );

        $chargeObj = $this->apiCreateCharge($cart);
        header('Location: ' . $chargeObj->hosted_url);
    }

    /**
     * Check if the current module is an active payment module.
     */
    public function isModuleActive()
    {
        $authorized = false;
        foreach (Module::getPaymentModules() as $module) {
            if ($module['name'] == 'coinbase') {
                $authorized = true;
                break;
            }
        }

        return $authorized;
    }

    