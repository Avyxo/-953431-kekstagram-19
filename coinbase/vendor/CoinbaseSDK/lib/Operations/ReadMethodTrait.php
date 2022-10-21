<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;
use CoinbaseSDK\ApiResourceList;

trait ReadMethodTrait
{
    public static function retrieve($id, $headers = [])
    {
        if (!\is_scalar($id)) {
            throw new \Exception('Invalid id provided.');
        }

        $client = static::getClient();
        $path = Util::joinPath(static::getResourcePath(), $id);
        $responseData = $client->get($path, [], $headers);

        return Util::convertToApiObject($responseData);
    }

    public function refresh($headers = [])
    {
        $id = $this->getPrimaryKeyValue();

        if (!\is_scalar($id)) {
            throw new \Exception('Invalid id provided');
        }

        $client = static::getClient();
        $path = Util::joinPath(static::getResourcePath(), $id);
        $response = $client->get($path, [], $headers);

        $this->refreshFrom($response);
    }

    public static function getList($params = [], $headers = [])
    {
        $path = static::getResourcePath();
        $client = static::getClient();
        $response = $client->get($path, $params, $headers);
        $respon