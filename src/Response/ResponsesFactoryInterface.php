<?php


namespace Scylla\Response;


interface ResponsesFactoryInterface
{
    /**
     * @param string $content
     * @param string $code
     *
     * @return ResponseInterface
     */
    public static function create($content, $code);
}