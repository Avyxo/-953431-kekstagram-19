
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