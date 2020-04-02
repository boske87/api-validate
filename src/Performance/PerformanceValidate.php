<?php
namespace Performance\Validate;

use GuzzleHttp\Client;
use Performance\Validate\Exceptions\ServiceException;

class PerformanceValidate
{
    /**
     * @var string
     */
    protected $name = 'Validate';

    /**
     * @var string
     */
    protected $format = 'json';

    /**
     * @var string
     */
    protected $url = 'https://interview.performance-technologies.de/';

    protected $token;

    /**
     * @var Client
     */
    private $client;

    /**
     * Validate constructor.
     */
    public function __construct($token)
    {
        $this->token = $token;
        // Performance request
        $this->client = new Client([
            'base_uri' => $this->url,
            'timeout' => 15,
        ]);
    }

    /**
     * This method resolves request by service name
     * @param string $field
     * @param $value
     * @return PerformanceValidateResponse|null - Null if unknown field / not detected
     */
    public function verify(string $field, $value)
    {
        switch ($field)
        {
            case 'address':
                return $this->address($value);

            default:
                return $this->$field($value);
        }

    }


    /**
     * Verify only name of all Performance services
     *
     * @param $firstname
     * @return PerformanceValidateResponse
     */
    public function address(array $address)
    {
        return $this->request(PerformanceValidateService::ADDRESS, $address);
    }

    /**
     * make url for service
     * @param string $service
     * @param array $params
     * @return string
     * @throws ServiceException
     */
    private function makeUrl(string $service, array $params)
    {

        // check service
        if (!PerformanceValidateService::isValidValue($service))
            throw new ServiceException($this->name, 'Invalid service name: "'.$service.'"');

        $url = '/api/'.$service . '?token='.$this->token;

        foreach ($params as $key => $one) {
            $url .= '&' . $key . '=' . $one;
        }
        echo $url;
        return $url;
    }
    /**
     * Call Performance technologie api.
     * Handles exceptions and creates appropriate Performance technologie as error handling mechanism
     *
     * @see PerformanceResponse::resolveResponse()
     * @param string $service
     * @param array $params
     *
     * @return PerformanceValidateResponse
     */
    public function request(string $service, array $params)
    {

        try
        {
            // Make url from params and service
            $url = $this->makeUrl($service, $params);
            $responseRaw = $this->client->get($url);
            $response = new PerformanceValidateResponse($service, $responseRaw->getBody());
        }
            // Catch ServiceException from make url (in case service is wrong for eg.)
        catch (ServiceException $e)
        {
            $response = (new PerformanceValidateResponse($service))->handleException($e);
        }
            // Catch all system exceptions to make sure validation never tragically fails
        catch (\Exception $e)
        {
            $response = (new PerformanceValidateResponse($service))->handleException($e);
        }

        return $response;
    }

}