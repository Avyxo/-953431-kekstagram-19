<?php

if (!defined('_PS_VERSION_')) {
    exit();
}

/**
 * For testing purposes we wrap the Configuration in a wrapper class
 * so that we can easily mock it.
 */
class ConfigManager
{
    public function addFields()
    {
        $orderNew = $this->createOrderStatus('Coinbase awaiting status', '#D0CA64');
        $orderPending = $this->createOrderStatus('Coinbase pending status', '#007FFF');

        if (
            Configuration::updateValue('COINBASE_API_KEY', null)
            && Configuration::updateValue('COINBASE_SANDBOX', null)
            && Configuration::updateValue('COINBASE_SHARED_SECRET', null)
            && Configuration::updateValue('COINBASE_NEW', $orderNew->id)
            && Configuration::updateValue('COINBASE_P