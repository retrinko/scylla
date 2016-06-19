<?php

namespace Retrinko\Scylla\Request\Requests;

use Retrinko\Scylla\Request\AbstractRequest;

class DefaultRequest extends AbstractRequest
{

    /**
     * @return string
     */
    public function getEncodedParams()
    {
        return http_build_query($this->getParams());
    }

    /**
     * @return void
     */
    public function initHeaders()
    {
        // No default headers.
    }
}