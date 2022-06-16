
<?php
if (!defined('_PS_VERSION_')) {
    exit();
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'coinbase/vendor/CoinbaseSDK/init.php';
    require_once _PS_MODULE_DIR_ . 'coinbase/vendor/CoinbaseSDK/const.php';
}

class CoinbaseWebhookModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $event = $this->constructEvent();
        \CoinbaseSDK\ApiClient::init(Configuration::get('COINBASE_API_KEY'));
        $chargeId = $event->data->id;
        $chargeObj = \CoinbaseSDK\Resources\Charge::retrieve($chargeId);
        if (empty($chargeObj->timeline)) {
            throw new Exception('Invalid charge');
        }
        $lastTimeLine = end($chargeObj->timeline);
        $orderId = (int)$chargeObj->getMetadataParam(METADATA_INVOICE_ID_PARAM);
        $cartId = (int)$chargeObj->getMetadataParam(METADATA_CART_ID_PARAM);
        $order = new Order($orderId);

        if (!$order || $order->id_cart != $cartId) {
            throw new Exception('Order not exists');
        }

        $status = $this->getStatusByTimeLine($lastTimeLine);

        if (is_null($status)) {
            throw new Exception('Invalid status');
        }
