
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
            $opts[CURLOPT_HTTPGET] = 1;
            if (count($params) > 0) {
                $encoded = \CoinbaseSDK\Util::urlEncode($params);
                $absUrl = "$absUrl?$encoded";
            }
        } elseif ($method == 'post') {
            $opts[CURLOPT_POST] = 1;
            $body = json_encode($body);
            $opts[CURLOPT_POSTFIELDS] = $body;
            $headers[] = 'Content-Length: ' . strlen($body);
        } elseif ($method == 'put') {
            $opts[CURLOPT_PUT] = 1;
            $body = json_encode($body);
            $opts[CURLOPT_POSTFIELDS] = $body;
            $headers[] = 'Content-Length: ' . strlen($body);

        } elseif ($method == 'delete') {
            $opts[CURLOPT_CUSTOMREQUEST] = 'DELETE';
            if (count($params) > 0) {
                $encoded = \CoinbaseSDK\Util::urlEncode($params);
                $absUrl = "$absUrl?$encoded";
            }
        }

        // Create a callback to capture HTTP headers for the response
        $rheaders = [];
        $headerCallback = function ($curl, $header_line) use (&$rheaders) {
            // Ignore the HTTP request line (HTTP/1.1 200 OK)
            if (strpos($header_line, ":") === false) {
                return strlen($header_line);
            }
            list($key, $value) = explode(":", trim($header_line), 2);
            $rheaders[trim($key)] = trim($value);
            return strlen($header_line);
        };

        $opts[CURLOPT_URL] = $absUrl;