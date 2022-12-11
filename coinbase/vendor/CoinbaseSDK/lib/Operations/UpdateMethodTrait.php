<?php
namespace CoinbaseSDK\Operations;

use CoinbaseSDK\Util;

trait UpdateMethodTrait
{
    public function update($headers = [])
    {
        $id = $this->getPrimaryKeyValue();

        if (!\is_scalar($id) || null === $id) {
            throw new \Exception('id is not set.');
        }

        $client = static::getClient();
        $path = Util::joinPath(static::getResourcePath(), $id);
        $body = $this->getDirtyAttributes();
        unset($body[$this::getPrimaryKeyName()]);
        $response = $client->put($path, $body, $headers);

        $this->refreshFrom($response);
    }

    public static function updateById($id, $body, $headers = [])
    {
        if (!\is_scalar($id)) {
