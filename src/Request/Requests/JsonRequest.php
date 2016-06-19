<?php

namespace Retrinko\Scylla\Request\Requests;

use Retrinko\Scylla\Request\AbstractRequest;

class JsonRequest extends AbstractRequest
{
    /**
     * @return string
     */
    public function getEncodedParams()
    {
        return json_encode($this->getParams());
    }

    /**
     * @return void
     */
    public function initHeaders()
    {
        $this->addHeader('Content-Type', 'application/json');
    }
}