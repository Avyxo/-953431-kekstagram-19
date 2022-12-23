
<?php
namespace CoinbaseSDK\Resources;

use CoinbaseSDK\ApiClient;
use CoinbaseSDK\ApiResponse;
use CoinbaseSDK\Util;

class ApiResource extends \ArrayObject
{
    protected static $client;

    protected $attributes = [];

    protected $initialData = [];

    public function __construct($data = [])
    {
        $data = $data ?: [];
        $this->refreshFrom($data);
    }

    protected function refreshFrom($data)
    {
        $this->clearAttributes();

        if ($data instanceof ApiResponse) {
            $data = isset($data->bodyArray['data']) ? $data->bodyArray['data'] : null;
        }

        foreach ($data as $key => $value) {
            $value = Util::convertToApiObject($value);
            $this->attributes[$key] = is_array($value) ? new \ArrayObject($value) : $value;
            $this->initialData[$key] = $value;
        }
    }

    protected function clearAttributes()
    {
        $this->attributes = [];
        $this->initialData = [];