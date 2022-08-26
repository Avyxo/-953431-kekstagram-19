
<?php
namespace CoinbaseSDK;

class ApiResponse
{
    const REQUEST_ID_HEADER = 'x-request-id';
    /**
     * @var array
     */
    public $headers;
    /**
     * @var string
     */
    public $body;
    /**
     * @var mixed
     */
    public $bodyArray;
    /**
     * @var integer
     */
    public $code;
    /**
     * @var mixed|null
     */
    public $requestId;

    /**
     * ApiResponse constructor.
     */
    public function __construct($body, $code, $headers)