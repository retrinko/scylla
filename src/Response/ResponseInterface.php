<?php


namespace Retrinko\Scylla\Response;


interface ResponseInterface
{
    /**
     * @return int
     */
    public function getCode();

    /**
     * @param int $code
     *
     * @return ResponseInterface
     */
    public function setCode($code);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     *
     * @return ResponseInterface
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getContent();

    /**
     * @param string $content
     *
     * @return ResponseInterface
     */
    public function setContent($content);

    /**
     * @return mixed
     */
    public function getDecodedContent();
    
}