# retrinko/scylla

Scylla is a curl client that can execute multiple requests at the same time.

##  Installation

Install the latest version with

    $ composer require retrinko/scylla
    
## Dependencies

This library requires the php's curl extension to work.

## Logging

You can use your prefered PSR-3 compliant logger with this library (see Basic usage section).

##  Basic usage

### Simple request

    <?php
    
    use Retrinko\Scylla\Client;
    use Retrinko\Scylla\Request\Requests\DefaultRequest;
    use Retrinko\Scylla\Util\HttpCodes;
    
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
        /** @var \Retrinko\Scylla\Response\ResponseInterface $response */
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
    
### Multi request
    
    <?php
    
    use Retrinko\Scylla\Client;
    use Retrinko\Scylla\Request\Requests\JsonRequest;
    use Retrinko\Scylla\Request\RequestsCollection;
    use Retrinko\Scylla\Response\Factories\JsonResponsesFactory;
    use Retrinko\Scylla\Response\ResponseInterface;
    use Retrinko\Scylla\Util\HttpCodes;
    
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

