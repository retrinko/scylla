<?php


namespace Retrinko\Scylla\Response\Factories;

use Retrinko\Scylla\Response\Responses\JsonResponse;
use Retrinko\Scylla\Response\ResponsesFactoryInterface;

class JsonResponsesFactory implements ResponsesFactoryInterface
{

    /**
     * @param string $content
     * @param int $code
     *
     * @return JsonResponse
     */
    public static function create($content, $code)
    {
        return new JsonResponse($content, $code);
    }
}