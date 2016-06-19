<?php


namespace Retrinko\Scylla\Response\Factories;

use Retrinko\Scylla\Response\Responses\DefaultResponse;
use Retrinko\Scylla\Response\ResponsesFactoryInterface;

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