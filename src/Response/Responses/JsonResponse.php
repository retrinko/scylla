<?php


namespace Scylla\Response\Responses;

use Scylla\Response\AbstractResponse;

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