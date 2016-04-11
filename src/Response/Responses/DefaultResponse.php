<?php


namespace Scylla\Response\Responses;

use Scylla\Response\AbstractResponse;

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