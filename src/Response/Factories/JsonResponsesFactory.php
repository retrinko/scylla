<?php


namespace Scylla\Response\Factories;

use Scylla\Response\Responses\JsonResponse;
use Scylla\Response\ResponsesFactoryInterface;

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