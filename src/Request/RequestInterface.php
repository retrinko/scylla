<?php


namespace Retrinko\Scylla\Request;


interface RequestInterface
{

    const REQUEST_METHOD_CONNECT = 'CONNECT';
    const REQUEST_METHOD_DELETE  = 'DELETE';
    const REQUEST_METHOD_GET     = 'GET';
    const REQUEST_METHOD_HEAD    = 'HEAD';
    const REQUEST_METHOD_OPTIONS = 'OPTIONS';
    const REQUEST_METHOD_PATCH   = 'PATCH';
    const REQUEST_METHOD_POST    = 'POST';
    const REQUEST_METHOD_PUT     = 'PUT';
    const REQUEST_METHOD_TRACE   = 'TRACE';

    /**
     * @return string
     */
    public function getId();

    /**
     * @return array
     */
    public function getParams();

    /**
     * @return bool
     */
    public function hasParams();

    /**
     * @return string
     */
    public function getRequestMethod();

    /**
     * @return string
     */
    public function getUrl();

    /**
     * @return bool
     */
    public function hasAuth();

    /**
     * @return string
     */
    public function getUser();

    /**
     * @return string
     */
    public function getPass();

    /**
     * @return string
     */
    public function getEncodedParams();

    /**
     * Maximum  time  in  seconds  that you allow the connection to the server to take.
     * This only limits  the  connection  phase,  once curl has connected this option is of no
     * more use.
     * @return int Timeout in seconds
     */
    public function getConnectTimeout();

    /**
     * Maximum  time  in  seconds that you allow the whole operation to take.
     * @return int Timeout in seconds
     */
    public function getTimeout();

    /**
     * @return string
     */
    public function getUserAgent();

    /**
     * @return bool
     */
    public function hasHeaders();

    /**
     * @return array
     */
    public function getHeaders();

    /**
     * @return bool
     */
    public function hasUserAgent();

    /**
     * @return boolean
     */
    public function peersSSLCertificateVerificationIsRequired();

}