
<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Exceptions\CurlErrorException;
use CoinbaseSDK\ApiResponse;

class HttpClient
{
    // USER DEFINED TIMEOUTS

    const REQUEST_RETRIES = 2;
    const DEFAULT_TIMEOUT = 80;
    const DEFAULT_CONNECT_TIMEOUT = 30;

    private static $successCodes = [200, 201, 204];
    private $timeout = self::DEFAULT_TIMEOUT;
    private $connectTimeout = self::DEFAULT_CONNECT_TIMEOUT;


    private static $instance;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function setTimeout($seconds)
    {
        $this->timeout = (int) max($seconds, 0);
        return $this;
    }

    public function setConnectTimeout($seconds)
    {
        $this->connectTimeout = (int) max($seconds, 0);
        return $this;
    }

    public function getTimeout()
    {
        return $this->timeout;
    }

    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    // END OF USER DEFINED TIMEOUTS

    public function request($method, $absUrl, $params, $body, $headers)
    {
        $method = strtolower($method);
        $opts = [];

        if ($method == 'get') {