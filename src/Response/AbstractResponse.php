<?php

namespace Scylla\Response;

use Scylla\Util\HttpCodes;

abstract class AbstractResponse implements ResponseInterface
{
    /**
     * @var int
     */
    protected $code;
    /**
     * @var string
     */
    protected $message;
    /**
     * @var string
     */
    protected $content;

    /**
     * Response constructor.
     *
     * @param string $content
     * @param int $code
     */
    public function __construct($content, $code = HttpCodes::HTTP_OK)
    {
        $this->content = $content;
        $this->code = $code;
        // Set default message
        $this->message = HttpCodes::getMessageForCode($code);
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $code
     *
     * @return $this
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return $this
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('%s - %s'.PHP_EOL.PHP_EOL.'%s', $this->getCode(), $this->getMessage(), $this->getContent());
    }

    /**
     * @return mixed
     */
    abstract public function getDecodedContent();
}