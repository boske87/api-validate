<?php

namespace Performance\Validate;


class PerformanceValidateResponse
{
    /**
     * @var string $service
     */
    public $service;

    /**
     * Error field is same for all services.
     *
     * 1 = Error (invalid data)
     * 0 = All ok / Handled error (eg. Performance 500, but normal response)
     * -1 = Maintenance
     * -2 = System error (Exception handling)
     *
     * @var int $error
     */
    public $error;


    /**
     * @var bool $failValidation If set to true, isValid will be set to false and field validation will fail
     */
    public $failValidationOnException;

//* @var int $responseCode

    public $responseCode;

    /**
     * @var string $message
     */
    public $message;

    /**
     * If field validation should pass or fail
     *
     * @var boolean Is valid
     */
    public $isValid;

    /**
     * If result should be added to the database
     * This is true when service is unavailable for example, but we passed the lead.
     * This is false when service is working as intended, regardless of the validation outcome.
     *
     * @var boolean Is valid
     */
    public $isFlagged;

    /**
     * Raw json response
     *
     * @var string
     */
    public $rawResponse;


    /**
     * PerformanceResponse constructor.
     * @param string $service
     * @param null|string $rawResponse JSON raw response, or null if manually setting response (eg. request timeout or other exception)
     * @param bool $failValidationOnException
     */
    public function __construct(string $service, $rawResponse = null, $failValidationOnException = false)
    {
        $this->service = $service;
        if($rawResponse === null)
            return;


        $this->rawResponse = $rawResponse;

        $this->resolveResponse(json_decode($rawResponse, true));
    }


    /**
     * This method updates error, responseCode, message and isValid fields.
     * If field is makred "isValid = false", it'll fail validation, and user will be shown an error.
     * If field is marked as "isFlagged = true", it'll pass validation, but it'll be logged in flags table.
     *
     * @param array $response
     */
    private function resolveResponse(array $response)
    {
        $this->responseCode = $response['success'];
        $this->isValid = true;
        $this->isFlagged = false;

        switch($this->service)
        {
            case PerformanceValidateService::ADDRESS:
                // If outright rude, fail and dont flag...
                if ($this->responseCode === false)
                {
                    $this->isFlagged = false;
                    $this->isValid = false;
                    break;
                }

                break;

        }
    }

    /**
     * Returns if values entered are valid
     * @return bool
     */
    public function isValid()
    {
        return $this->isValid;
    }

    /**
     * Returns if values entered are valid
     * @return bool
     */
    public function isFlagged()
    {
        return $this->isFlagged;
    }

    /**
     * Handles exception and sets appropriate codes.
     *
     * @param \Exception $e
     * @return PerformanceValidateResponse
     */
    public function handleException(\Exception $e)
    {

        $this->isValid = false;
        $this->message = $e->getMessage();
        $this->error = -2;

        return $this;

    }
}