<?php


namespace Retrinko\Scylla\Response;


class ResponsesCollection implements \Countable, \Iterator
{
    /**
     * @var ResponseInterface[]
     */
    protected $responses = [];

    /**
     * ResponsesCollection constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $id
     * @param ResponseInterface $response
     */
    public function add($id, ResponseInterface $response)
    {
        $this->responses[$id] = $response;
    }

    /**
     * Count elements of an object
     *
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return count($this->responses);
    }

    /**
     * Return the current element.
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->responses);
    }

    /**
     * @param string $responseId
     * @param mixed $defaultValue
     *
     * @return ResponseInterface|mixed
     */
    public function get($responseId, $defaultValue = null)
    {
        return array_key_exists($responseId, $this->responses)
            ? $this->responses[$responseId] : $defaultValue;
    }

    /**
     * Return the key of the current element.
     *
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->responses);
    }

    /**
     * Move forward to next element.
     *
     * @return void
     */
    public function next()
    {
        next($this->responses);
    }
    
    /**
     * Checks if current position is valid.
     *
     * @return boolean Returns true on success or false on failure.
     */
    public function valid()
    {
        return !is_null(key($this->responses));
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @return void
     */
    public function rewind()
    {
        reset($this->responses);
    }


}