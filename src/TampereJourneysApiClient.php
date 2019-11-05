<?php

namespace Vikingmaster\TampereJourneysApiSdk;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest;

class TampereJourneysApiClient
{
    protected $config;
    protected $http;

    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = [
            'baseUri'   => rtrim($config['baseUri'], '/'),
            'timeout'   => $config['timeout'] ?? 30,
            'userAgent' => $config['userAgent'] ?? null,
        ];
        $this->http = new Client([
            'base_uri' => $this->config['baseUri']
        ]);
    }

    protected function validateConfig(array $config)
    {
        $required = [
            'baseUri'
        ];
        foreach ($required as $key) {
            if (! array_key_exists($key, $config)) {
                throw new \InvalidArgumentException("Missing or invalid config value [{$key}]");
            }
        }
    }

    /**
     * @return Client
     */
    public function getHttp()
    {
        return $this->http;
    }

    /**
     * @param Client $http
     * @return $this
     */
    public function setHttp(Client $http)
    {
        $this->http = $http;
        return $this;
    }

    /**
     * @return GetLinesRequest
     */
    public function makeGetLinesRequest()
    {
        return new GetLinesRequest($this);
    }

    /**
     * @return GetJourneyPatternsRequest
     */
    public function makeGetJourneyPatternsRequest()
    {
        return new GetJourneyPatternsRequest($this);
    }


    /**
     * @param BaseRequest $request
     * @param string|BaseResponse $responseClass
     * @return BaseResponse
     * @throws GuzzleException
     * @throws TampereJourneyApiException
     */
    public function sendRequest(BaseRequest $request, $responseClass)
    {
        $httpRequest = $request->toHttpRequest();
        try {
            $httpResponse = $this->http->send($httpRequest);
            $response = $responseClass::fromHttpResponse($httpResponse);
            if ($response->getStatus() !== BaseResponse::STATUS_SUCCESS) {
                throw new TampereJourneyApiException($this, $request, $response);
            }
            return $response;
        } catch (ClientException $e) {
            $httpResponse = $e->getResponse();
            $response = $httpResponse ? $responseClass::fromHttpResponse($httpResponse) : null;
            throw new TampereJourneyApiException($this, $request, $response, $e);
        }
    }

    public function getEndpointUrl(string $endpoint = '')
    {
        static $base;
        if (! $base) {
            $base = $this->config['baseUri'];
        }
        $endpoint = ltrim($endpoint, '/');
        return $endpoint ? "{$base}/{$endpoint}" : $base;
    }

    public function getDefaultHttpHeaders()
    {
        $headers = [];
        if ($this->config['userAgent'] ?? null) {
            $headers['User-Agent'] = $this->config['userAgent'];
        }
        return $headers;
    }
}
