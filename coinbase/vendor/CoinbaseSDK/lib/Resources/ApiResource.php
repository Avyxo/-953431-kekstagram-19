
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
    }

    public static function getPrimaryKeyName()
    {
        return 'id';
    }

    public static function getClassName()
    {
        return get_called_class();
    }

    public function getPrimaryKeyValue()
    {
        return isset($this->attributes[static::getPrimaryKeyName()]) ? $this->attributes[static::getPrimaryKeyName()] : null;
    }

    public function __set($key, $value)
    {
        if (\is_string($key)) {
            $this->attributes[$key] = is_array($value) ? new \ArrayObject($value) : $value;
        }
    }

    public function __get($key)
    {
        if (\is_string($key) && isset($this->attributes[$key])) {
            return $this->attributes[$key];
        }
    }

    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    public function __unset($key)
    {
        unset($this->attributes[$key]);
    }