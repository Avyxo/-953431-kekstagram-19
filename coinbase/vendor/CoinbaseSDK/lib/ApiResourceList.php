
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
    protected $resourceClass;

    /**
     * @var array
     */
    protected $headers = [];

    /**
     * @var array
     */
    protected $params = [];

    /**
     * ApiResourceList constructor.
     * @param array $items
     * @param array $pagination
     */
    public function __construct($resourceClass, $items, $pagination, $params, $headers)
    {
        $this->resourceClass = $resourceClass;
        $this->items = $items;
        $this->pagination = $pagination;
        $this->params = $params;
        $this->headers = $headers;
    }

    /**
     * @param $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param $pagination
     */
    public function setPagination($pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * @return array
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return bool
     */
    public function hasNext()
    {
        return isset($this->pagination[self::CURSOR_PARAM][1]) && null !== $this->pagination[self::CURSOR_PARAM][1];
    }

    /**
     * @return bool
     */
    public function loadNext()
    {
        if (!$this->hasNext()) {
            return false;
        }

        $nextCursor = $this->pagination[self::CURSOR_PARAM][1];
        $params = $this->getParams();
        $params[self::NEXT_CURSOR] = $nextCursor;
        $this->loadPage($params);

        return true;
    }

    /**
     * @return bool
     */
    public function hasPrev()
    {
        return isset($this->pagination[self::CURSOR_PARAM][0]) && null !== $this->pagination[self::CURSOR_PARAM][0];
    }
