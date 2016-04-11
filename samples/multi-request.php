<?php

use Scylla\Client;
use Scylla\Request\Requests\JsonRequest;
use Scylla\Request\RequestsCollection;
use Scylla\Response\Factories\JsonResponsesFactory;
use Scylla\Response\ResponseInterface;
use Scylla\Util\HttpCodes;

require_once __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('UTC');

$logger = new Monolog\Logger('test');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

try
{
    // Instance new client
    $client = new Client();
    // Configure your client
    $client->setLogger($logger);
    $client->setResponsesFactory(new JsonResponsesFactory());
    $client->usePipelining(true);

    // Create requests
    $urls = ['ip' => 'https://httpbin.org/ip',
             'userAgent' => 'https://httpbin.org/user-agent',
             'get' => 'https://httpbin.org/get'];
    $requestsCollection = new RequestsCollection();
    foreach ($urls as $id => $url)
    {
        // Configure request
        $request = new JsonRequest($url);
        $request->setId($id);
        // Add request to requests collecction
        $requestsCollection->add($request);
    }

    // Execute requests
    $responsesCollection = $client->exec($requestsCollection);
    // Get responses
    foreach ($responsesCollection as $requestId => $response)
    {
        /** @var ResponseInterface $response */
        // Check http code and read response data or error message
        $code = $response->getCode();
        if (false == HttpCodes::isError($code))
        {
            $logger->notice('Execution success!', ['requestId' => $requestId]);
            // Get response body
            $data = $response->getDecodedContent();
            var_dump($data);
        }
        else
        {
            // Get response message (error)
            $logger->error($response->getMessage(), ['requestId' => $requestId]);
        }
    }

}
catch (\Exception $e)
{
    $logger->alert($e->getMessage());
}
