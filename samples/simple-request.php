<?php

use Scylla\Client;
use Scylla\Request\Requests\DefaultRequest;
use Scylla\Util\HttpCodes;

require_once __DIR__.'/../vendor/autoload.php';
date_default_timezone_set('UTC');

$logger = new Monolog\Logger('test');
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout'));

try
{
    // Instance new client
    $client = new Client();
    // Configure your client
    $client->setLogger($logger);
    // Configure your requests
    $sampleRequest = new DefaultRequest('https://httpbin.org/html');
    $sampleRequest->verificatePeersSSLCertificate(true);
    // Execute requests
    $responsesCollection = $client->exec($sampleRequest);
    // Get current response
    /** @var \Scylla\Response\ResponseInterface $response */
    $response = $responsesCollection->current();
    // Check http code and read response data or error message
    $code = $response->getCode();
    if (false == HttpCodes::isError($code))
    {
        $logger->notice('Execution success!');
        // Get response body
        $resposeBody = $response->getDecodedContent();
        var_dump($resposeBody);
    }
    else
    {
        // Get response message (error)
        $logger->error($response->getMessage());
    }
}
catch (\Exception $e)
{
    $logger->alert($e->getMessage());
}
