
<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Exceptions\CurlErrorException;
use CoinbaseSDK\ApiResponse;

class HttpClient
{
    // USER DEFINED TIMEOUTS

    const REQUEST_RETRIES = 2;
    const DEFAULT_TIMEOUT = 80;