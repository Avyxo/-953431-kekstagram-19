<?php
namespace CoinbaseSDK;

use CoinbaseSDK\Exceptions\AuthenticationException;
use CoinbaseSDK\Exceptions\InternalServerException;
use CoinbaseSDK\Exceptions\InvalidRequestException;
use CoinbaseSDK\Exceptions\ParamRequiredException;
use CoinbaseSDK\Exceptions\RateLimitExceededException;
use CoinbaseSDK\Exceptions\ResourceNotFoundException;
use CoinbaseSDK\Exceptions\ServiceUnavailableException;
use CoinbaseSDK\Exceptions\ValidationException;
use CoinbaseSDK\Exceptions\ApiException;

class ApiErrorFactory
{
    /**
     * @var array
     */
    private static $mapErrorMessageToClass = [];

    /**
     * @var array
     */
    private static $mapErrorCodeToClass = [];

    /**
     * @param $message
     * @return mixed|null
     */
    public static function getErrorClassByMessage($message)
    {
        if (empty(self::$mapErrorMessageToClass)) {
            self::$m