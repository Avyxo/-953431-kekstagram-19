
<?php
namespace CoinbaseSDK;

class ApiResourceList extends \ArrayObject
{
    const CURSOR_PARAM = 'cursor_range';
    const PREV_CURSOR = 'ending_before';
    const NEXT_CURSOR = 'starting_after';

    private static $apiClient;

    /**
     * @var array
     */
    protected $items = [];

    /**
     * @var array
     */
    protected $pagination = [];

    /**
     * @var string
     */