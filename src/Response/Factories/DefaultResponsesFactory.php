<?php


namespace Scylla\Response\Factories;

use Scylla\Response\Responses\DefaultResponse;
use Scylla\Response\ResponsesFactoryInterface;

class DefaultResponsesFactory implements ResponsesFactoryInterface
{
    /**
     * @param string $content
     * @param int $code
     *
     * @return DefaultResponse
     */
    public static function create($content, $code)
    {
        return new DefaultResponse($content, $code);
    }
}