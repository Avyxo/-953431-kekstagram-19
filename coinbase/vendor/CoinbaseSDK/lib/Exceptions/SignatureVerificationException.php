<?php
namespace CoinbaseSDK\Exceptions;

class SignatureVerificationException extends CoinbaseException
{
    public function __construct($signature, $payload)
    {
        $message = sprintf('No signatures fou