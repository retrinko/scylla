<?php


namespace Scylla\Response;


trait ResponsesFactoryAwareTrait
{
    /**
     * @var ResponsesFactoryInterface
     */
    protected $responsesFactory;

    public function setResponsesFactory(ResponsesFactoryInterface $responsesFactory)
    {
        $this->responsesFactory = $responsesFactory;
    }
}