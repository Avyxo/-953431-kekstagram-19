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
      