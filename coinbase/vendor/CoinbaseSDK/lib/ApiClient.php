
<?php
namespace CoinbaseSDK;

class ApiClient
{
    const API_KEY_PARAM = 'apiKey';
    const BASE_API_URL_PARAM = 'baseApiUrl';
    const API_VERSION_PARAM = 'apiVersion';
    const TIMEOUT_PARAM = 'timeout';

    /**
     * @var array
     */
    private $params = [
        self::API_VERSION_PARAM => null,
        self::BASE_API_URL_PARAM => 'https://api.commerce.Coinbase.com/',
        self::API_VERSION_PARAM => '2018-03-22',
        self::TIMEOUT_PARAM => 3
    ];

    /**
     * @var ApiClient
     */
    private static $instance;

    /**
     * @var
     */
    private $logger;

    /**
     * @var mixed
     */
    private $response;

    /**
     * @var
     */
    private $httpClient;

    /**
     * ApiClient constructor.
     */
    private function __construct()
    {
    }

    private function __clone()
    {
    }

    /**
     * @param string $apiKey
     * @param null|string $baseUrl
     * @param null|string $apiVersion
     * @param null|integer $timeout
     * @return ApiClient
     */
    public static function init($apiKey, $baseUrl = null, $apiVersion = null, $timeout = null)
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        self::$instance->setApiKey($apiKey)
            ->setBaseUrl($baseUrl)
            ->setApiVersion($apiVersion)
            ->setTimeout($timeout);

        return self::$instance;
    }

    /**
     * @return ApiClient
     * @throws \Exception
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            throw new \Exception('Please init client first.');
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @return mixed
     */
    private function getParam($key)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    private function setParam($key, $value)
    {
        $this->params[$key] = $value;
        return $this;
    }
