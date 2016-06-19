<?php


namespace Retrinko\Scylla\Curl;


class PhpCurlHandler
{
    /**
     * @var resource
     */
    protected $phpCurlHandler;

    /**
     * CurlHandler constructor.
     */
    public function __construct()
    {

    }
    
    public function __destruct()
    {
        $this->close();
    }

    /**
     * Init curl resource
     */
    protected function init()
    {
        // Init resource
        $this->phpCurlHandler = curl_init();
    }

    /**
     * @return resource
     */
    public function getHandler()
    {
        if (!is_resource($this->phpCurlHandler))
        {
            $this->init();
        }

        return $this->phpCurlHandler;
    }

    /**
     * @param int $option
     * @param mixed $value
     */
    public function setOption($option, $value)
    {
        curl_setopt($this->getHandler(), $option, $value);
    }

    /**
     * @return string
     */
    public function getLastError()
    {
        return curl_error($this->getHandler());
    }

    /**
     * Close curl resource
     */
    public function close()
    {
        curl_close($this->getHandler());
    }

    /**
     * @return mixed
     */
    public function exec()
    {
        ob_start();
        $result = curl_exec($this->getHandler());
        ob_end_clean();

        return $result;
    }
}