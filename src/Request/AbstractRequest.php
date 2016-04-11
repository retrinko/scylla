<?php

namespace Scylla\Request;


abstract class AbstractRequest implements RequestInterface
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $url;
    /**
     * @var string
     */
    protected $requestMethod = self::REQUEST_METHOD_GET;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var string
     */
    protected $user;
    /**
     * @var string
     */
    protected $pass;
    /**
     * @var string
     */
    protected $userAgent = 'Scylla HTTP Client';
    /**
     * @var array
     */
    protected $headers = [];
    /**
     * @var bool
     */
    protected $peersSSLCertificateVerificationIsRequired = false;
    /**
     * @var int seconds
     */
    protected $timeout = 30;
    /**
     * @var int seconds
     */
    protected $connectTimeout = 5;

    /**
     * @return string
     */
    abstract public function getEncodedParams();

    /**
     * @return void
     */
    abstract public function initHeaders();

    /**
     * Request constructor.
     *
     * @param string $url
     * @param string $requestType
     * @param array $params
     */
    public function __construct($url, $requestType = self::REQUEST_METHOD_GET, $params = [])
    {
        $this->id = uniqid('request-');
        $this->url = $url;
        $this->requestMethod = $requestType;
        $this->params = $params;
        $this->initHeaders();
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     *
     * @return $this
     */
    public function addParam($name, $value)
    {
        $this->params[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function removeParam($name)
    {
        if (array_key_exists($name, $this->params))
        {
            unset($this->params[$name]);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParams()
    {
        return count($this->getParams()) > 0;
    }

    /**
     * @return string
     */
    public function getRequestMethod()
    {
        return $this->requestMethod;
    }

    /**
     * @param string $requestMethod
     *
     * @return $this
     */
    public function setRequestMethod($requestMethod)
    {
        $this->requestMethod = $requestMethod;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $user
     * @param string $pass
     *
     * @return $this
     */
    public function setAuth($user, $pass)
    {
        $this->user = $user;
        $this->pass = $pass;

        return $this;
    }
    
    /**
     * @return bool
     */
    public function hasAuth()
    {
        return (isset($this->user) && isset($this->pass));
    }

    /**
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @return int Timeout in seconds
     */
    public function getConnectTimeout()
    {
        return $this->connectTimeout;
    }

    /**
     * @param int $connectTimeout Timeout in seconds
     *
     * @return $this
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = $connectTimeout;

        return $this;
    }

    /**
     * @return int Timeout in seconds
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout Timeout in seconds
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;

        return $this;
    }

    /**
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * @param string $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasUserAgent()
    {
        return (is_string($this->getUserAgent()) && !empty($this->getUserAgent()));
    }

    /**
     * @return bool
     */
    public function hasHeaders()
    {
        return (is_array($this->getHeaders()) && count($this->getHeaders()) > 0);
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * @param string $key
     * @param string $value
     *
     * @return $this
     */
    public function addHeader($key, $value)
    {
        $this->headers[] = sprintf('%s: %s', $key, $value);

        return $this;
    }


    /**
     * @return boolean
     */
    public function peersSSLCertificateVerificationIsRequired()
    {
        return $this->peersSSLCertificateVerificationIsRequired;
    }

    /**
     * @param boolean $verificate
     *
     * @return $this
     */
    public function verificatePeersSSLCertificate($verificate)
    {
        $this->peersSSLCertificateVerificationIsRequired = $verificate;

        return $this;
    }
}