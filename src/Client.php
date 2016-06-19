<?php

namespace Retrinko\Scylla;

use Retrinko\Scylla\Curl\PhpCurlHandler;
use Retrinko\Scylla\Request\RequestInterface;
use Retrinko\Scylla\Exceptions\Exception;
use Retrinko\Scylla\Request\RequestsCollection;
use Retrinko\Scylla\Response\Factories\DefaultResponsesFactory;
use Retrinko\Scylla\Response\ResponsesCollection;
use Retrinko\Scylla\Response\ResponsesFactoryAwareTrait;
use Retrinko\Scylla\Response\ResponsesFactoryInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;

class Client
{
    use LoggerAwareTrait;
    use ResponsesFactoryAwareTrait;

    /**
     * @var bool
     */
    protected $usePipelining = false;
    /**
     * @var array
     */
    protected $errors = [];

    
    public function __construct()
    {
        $this->logger = new NullLogger();
        $this->responsesFactory = new DefaultResponsesFactory();
    }

    /**
     * @return ResponsesFactoryInterface
     */
    public function getResponsesFactory()
    {
        return $this->responsesFactory;
    }

    /**
     * @param bool $usePipelining
     *
     * @return $this
     */
    public function usePipelining($usePipelining = true)
    {
        $this->usePipelining = $usePipelining && $this->isPipelinigAvailable();

        return $this;
    }


    /**
     * Checks if pipelining is available for the running version of php.
     * @return bool
     */
    protected function isPipelinigAvailable()
    {
        $available = false;
        list($majorVersion, $minorVersion,) = explode('.', phpversion(), 3);
        if ($majorVersion > 5 || ($majorVersion == 5 && $minorVersion >= 5))
        {
            $available = true;
        }
        $this->logger->debug(sprintf('Pipelining available: %s', $available ? 'Y' : 'N'));

        return $available;
    }

    /**
     * @param RequestsCollection|RequestInterface $requests
     *
     * @return ResponsesCollection
     * @throws Exception
     */
    public function exec($requests)
    {
        if ($requests instanceof RequestInterface)
        {
            $request = $requests;
            $requests = new RequestsCollection();
            $requests->add($request);
        }

        if (false == $requests instanceof RequestsCollection)
        {
            throw new Exception('Invalid requests! Param $requests must be an instance of ' .
                                'RequestInterface or a RequestsCollection.');
        }

        $curlMultiHandler = curl_multi_init();
        if (true == $this->usePipelining)
        {
            curl_multi_setopt($curlMultiHandler, CURLMOPT_PIPELINING, 1);
        }
        $curlHandlers = [];
        $totalRequest = count($requests);
        $auxCtr = 0;
        $this->logger->debug(sprintf('Adding curl handlers for %s request...', $totalRequest));
        /** @var RequestInterface $request */
        foreach ($requests as $request)
        {
            $auxCtr++;
            $requestCurlHandler = new PhpCurlHandler();
            $requestCurlHandler->setOption(CURLOPT_URL, $request->getUrl());
            $requestCurlHandler->setOption(CURLOPT_RETURNTRANSFER, true);
            $requestCurlHandler->setOption(CURLOPT_FOLLOWLOCATION, false);
            $requestCurlHandler->setOption(CURLOPT_CUSTOMREQUEST,
                                           strtoupper($request->getRequestMethod()));
            $requestCurlHandler->setOption(CURLOPT_TIMEOUT, $request->getTimeout());
            $requestCurlHandler->setOption(CURLOPT_CONNECTTIMEOUT, $request->getConnectTimeout());
            $requestCurlHandler->setOption(CURLOPT_SSL_VERIFYPEER,
                                           $request->peersSSLCertificateVerificationIsRequired());
            // Set headers
            if (true == $request->hasHeaders())
            {
                $requestCurlHandler->setOption(CURLOPT_HTTPHEADER, $request->getHeaders());
            }
            // Set request user and pass if needed
            if (true == $request->hasAuth())
            {
                $requestCurlHandler->setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
                $requestCurlHandler->setOption(CURLOPT_USERPWD,
                                               $request->getUser() . ':' . $request->getPass());
            }
            // Set user agent
            if (true == $request->hasUserAgent())
            {
                $requestCurlHandler->setOption(CURLOPT_USERAGENT, $request->getUserAgent());
            }
            // Set request params
            if (true == $request->hasParams())
            {
                $requestCurlHandler->setOption(CURLOPT_POST, 1);
                $requestCurlHandler->setOption(CURLOPT_POSTFIELDS, $request->getEncodedParams());
            }

            $this->logger->debug(sprintf('Adding curl handler for request %s of %s (id:%s): [%s] %s ',
                                         $auxCtr,
                                         $totalRequest,
                                         $request->getId(),
                                         $request->getRequestMethod(),
                                         $request->getUrl()),
                                 ['params' => $request->getParams(),
                                  'headers' => $request->getHeaders()]);

            $curlHandlers[$request->getId()] = $requestCurlHandler->getHandler();
            curl_multi_add_handle($curlMultiHandler, $requestCurlHandler->getHandler());
        }

        $this->logger->debug(sprintf('Executing curl multi request (%s requests)...',
                                     $totalRequest));
        $initTime = microtime(true);
        $active = null;
        do
        {
            $status = curl_multi_exec($curlMultiHandler, $active);
            $info = curl_multi_info_read($curlMultiHandler);
            if ($status > 0)
            {
                throw new Exception(sprintf('Curl error "%s"!', $status));
            }
            if (false !== $info)
            {
                if (isset($info['result']) && 0 < $info['result'])
                {
                    $url = '';
                    $errorMsg = '';
                    if (isset($info['handle']))
                    {
                        $errorMsg = curl_error($info['handle']);
                        $url = curl_getinfo($info['handle'], CURLINFO_EFFECTIVE_URL);
                    }
                    throw new Exception(sprintf('Error [%s] calling URL "%s" (%s)!',
                                                $info['result'], $url, $errorMsg));
                }
            }
        }
        while ($status === CURLM_CALL_MULTI_PERFORM || $active);
        $this->logger->debug(sprintf('Executed %s requests in %ss...',
                                     $totalRequest, microtime(true) - $initTime));
        $responses = new ResponsesCollection();
        $this->logger->debug('Composing responses...');
        $auxCtr = 0;
        foreach ($curlHandlers as $requestId => $curlHandler)
        {
            $auxCtr++;
            $this->logger->debug(sprintf('Composing response for request %s (%s of %s)...',
                                         $requestId, $auxCtr, $totalRequest));
            $httpCode = curl_getinfo($curlHandler, CURLINFO_HTTP_CODE);
            $contents = curl_multi_getcontent($curlHandler);
            $this->logger->debug(sprintf('Response for request %s [code: %s]:  %s',
                                         $requestId,
                                         $httpCode,
                                         $this->mutiLineStringToOneLineString($contents)));
            $responses->add($requestId, $this->getResponsesFactory()
                                             ->create((string)$contents, $httpCode));
            // Remove handler from curl multi handler
            curl_multi_remove_handle($curlMultiHandler, $curlHandler);
        }
        // Close curl multi handler
        curl_multi_close($curlMultiHandler);

        $this->logger->debug(sprintf('Total execution time: %ss)...', microtime(true) - $initTime));

        return $responses;
    }


    /**
     * @param string $string
     *
     * @return mixed
     */
    protected function mutiLineStringToOneLineString($string)
    {
        return str_replace(["\n", "\r"], '[-NEW-LINE-]', $string);
    }
}