<?php


namespace Retrinko\Scylla\Response\Responses;

use Retrinko\Scylla\Response\AbstractResponse;

class DefaultResponse extends AbstractResponse
{
    /**
     * @return string
     */
    public function getDecodedContent()
    {
        return $this->getContent();
    }
}