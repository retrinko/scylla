<?php


namespace Retrinko\Scylla\Response\Responses;

use Retrinko\Scylla\Response\AbstractResponse;

class JsonResponse extends AbstractResponse
{
    /**
     * @return array
     */
    public function getDecodedContent()
    {
        return json_decode($this->getContent(), true);
    }
}