<?php
namespace CoinbaseSDK\Resources;

use CoinbaseSDK\Operations\CreateMethodTrait;
use CoinbaseSDK\Operations\DeleteMethodTrait;
use CoinbaseSDK\Operations\ReadMethodTrait;
use CoinbaseSDK\Operations\SaveMethodTrait;
use CoinbaseSDK\Operations\UpdateMethodTrait;

class Checkout extends ApiResource implements ResourcePathInterface
{
    use ReadMethodTrait, CreateMethodTrait, UpdateMethodTrait, DeleteMethodTrait, SaveMethodTrait;

    /**
     * @return s