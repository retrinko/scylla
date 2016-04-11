<?php

namespace Scylla\Request\Requests;

use Scylla\Request\AbstractRequest;

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