<?php
namespace Performance\Validate\Exceptions;

use Exception;
class ServiceException extends Exception
{
    /**
     * @var string
     */
    private $service;

    /**
     * @var string
     */
    private $request;

    /**
     * @var string
     */
    private $response;

    /**
     * Constructor
     *
     * @param string $service
     * @param string $message
     * @param int $code
     * @param string $request
     * @param string $response
     * @param \Exception|null $previous
     */
    public function __construct(string $service = '', string $message = '', int $code = 0, string $request = '', $response = '', Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->service = $service;
        $this->message = '['.$service.']: '.$message;
        $this->request = $request;
        $this->response = json_encode($response);
        $this->code = $code;
    }

    /**
     * Returns name of service this exception happened in
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Returns full XML Request containing information what was sent to SOAP server
     * @return string
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Returns JSON encoded response
     * @return string
     */
    public function getResponse()
    {
        return $this->response;
    }
}