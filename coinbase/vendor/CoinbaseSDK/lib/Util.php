
<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Resources\Charge;
use CoinbaseSDK\Resources\Checkout;
use CoinbaseSDK\Resources\Event;

class Util
{
    private static $mapResourceByName = [];

    /**
     * @param mixed $response
     */
    public static function convertToApiObject($response)
    {
        if ($response instanceof ApiResponse) {
            $response = isset($response->bodyArray['data']) ? $response->bodyArray['data'] : null;
        }

        if (is_array($response)) {
            array_walk(
                $response,
                function (&$item) {
                    $item = self::convertToApiObject($item);
                }
            );
        }

        if (isset($response['resource']) && $resourceClass = self::getResourceClassByName($response['resource'])) {
            return new $resourceClass($response);
        }

        return $response;
    }

    public static function getResourceClassByName($name)
    {
        if (empty(self::$mapResourceByName)) {
            self::$mapResourceByName = [
                'checkout' => Checkout::getClassName(),
                'charge' => Charge::getClassName(),
                'event' => Event::getClassName()
            ];
        }

        return isset(self::$mapResourceByName[$name]) ? self::$mapResourceByName[$name] : null;
    }