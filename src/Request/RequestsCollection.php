<?php


namespace Scylla\Request;


class RequestsCollection implements \Countable, \Iterator
{
    /**
     * @var RequestInterface[]
     */
    protected $requests = [];

    /**
     * ResponsesCollection constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param RequestInterface $request
     */
    public function add(RequestInterface $request)
    {
        $this->requests[$request->getId()] = $request;
    }

    /**
     * Count elements of an object
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->requests);
    }
    
    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->requests);
    }
    
    /**
     * @param string $requestId
     * @param mixed $defaultValue
     *
     * @return RequestInterface|mixed
     */
    public function get($requestId, $defaultValue = null)
    {
        return array_key_exists($requestId, $this->requests)
            ? $this->requests[$requestId] : $defaultValue;
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->requests);
    }
    
    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        next($this->requests);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->requests);
    }
    
    /**
     * Checks if current position is valid.
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return !is_null(key($this->requests));
    }
    
}