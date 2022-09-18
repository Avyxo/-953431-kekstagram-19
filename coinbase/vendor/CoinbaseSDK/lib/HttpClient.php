
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
        $opts[CURLOPT_RETURNTRANSFER] = true;
        $opts[CURLOPT_CONNECTTIMEOUT] = $this->connectTimeout;
        $opts[CURLOPT_TIMEOUT] = $this->timeout;
        $opts[CURLOPT_HEADERFUNCTION] = $headerCallback;
        $opts[CURLOPT_HTTPHEADER] = $headers;

        list($rbody, $rcode) = $this->executeRequestWithRetries($opts, $absUrl);

        return new ApiResponse($rbody, $rcode, $rheaders);
    }

    /**
     * @param array $opts cURL options
     */
    private function executeRequestWithRetries($opts, $absUrl)
    {
        $numRetries = 0;

        while (true) {
            $rcode = 0;
            $errno = 0;

            $curl = curl_init();
            curl_setopt_array($curl, $opts);
            $rbody = curl_exec($curl);

            if ($rbody === false) {
                $errno = curl_errno($curl);
                $message = curl_error($curl);
            } else {
                $rcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            }
            curl_close($curl);

            if ($this->shouldRetry($errno, $rcode, $numRetries)) {
                $numRetries += 1;
                usleep(intval(0.1 * 1000000));
            } else {
                break;