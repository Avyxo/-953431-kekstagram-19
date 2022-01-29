
<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

if (defined('_PS_MODULE_DIR_')) {
    require_once _PS_MODULE_DIR_ . 'coinbase/classes/ConfigManager.php';
}

class Coinbase extends PaymentModule
{

    private $configManager;

    public function __construct()
    {
        $this->name = 'coinbase';
        $this->tab = 'payments_gateways';
        $this->version = '1.0.0';
        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->author = 'Coinbase';
        $this->controllers = array('process', 'cancel', 'webhook');
        $this->is_eu_compatible = 1;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Coinbase Commerce');
        $this->description = $this->l('Payment module to handle transactions using Coinbase Commerce.');

        // Since Prestashop do not use Dependency Injection, make sure that we can change 
        // which class that handle certain behavior, so we can easily mock it in tests.
        $this->setConfigManager(new ConfigManager());
    }

    /**
     * Executes when installing module. Validates that required hooks exists
     * and initiate default values in the database.
     */
    public function install()
    {
        // If anything fails during installation, return false which will 
        // raise an error to the user.
        if (
            !parent::install() ||
            !$this->registerHook('payment') ||
            !$this->registerHook('paymentOptions') ||
            !$this->registerHook('paymentReturn') ||
            !$this->configManager->addFields()
        ) {
            return false;
        }
        return true;
    }

    /**
     * Executes when uninstalling the module. Cleanup DB fields
     * and raise error if something goes wrong.
     */
    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->configManager->deleteFields()
        ) {
            return false;
        }

        return true;
    }

    /**
     * Hook in to the list of payment options on checkout page.
     * @return PaymentOption[]
     */
    public function hookPaymentOptions($params)
    {
        if (!$this->active) {
            return;
        }