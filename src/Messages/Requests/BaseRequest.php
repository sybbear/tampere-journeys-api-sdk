<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Requests;

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;
use Vikingmaster\TampereJourneysApiSdk\Utilities\Arr;
use function GuzzleHttp\Psr7\build_query;

abstract class BaseRequest
{
    protected $endpoint = '';
    protected $method  = 'GET';
    protected $headers = [
        'Content-Type' => 'application/json'
    ];

    protected $queryParams = [];
    protected $bodyParams  = [];

    /** @var TampereJourneysApiClient */
    protected $apiClient;

    public function __construct(TampereJourneysApiClient $apiClient = null)
    {
        $this->apiClient = $apiClient;
    }

    public function setIndent(bool $value)
    {
        return $this->setQueryParam('indent', $value ? 'yes' : null);
    }

    public function setStartIndex(int $value)
    {
        return $this->setQueryParam('startIndex', $value);
    }

    /**
     * @param $key
     * @param $value
     * @return static
     */
    public function setQueryParam($key, $value)
    {
        $this->queryParams[$key] = $value;
        return $this;
    }

    public function setBodyParam($key, $value)
    {
        Arr::set($this->bodyParams, $key, $value);
        return $this;
    }

    public function getBodyParam(string $key, $default = null)
    {
        return Arr::get($this->bodyParams, $key, $default);
    }

    public function getBodyParams()
    {
        return $this->bodyParams;
    }

    /**
     * @return BaseResponse
     */
    public abstract function send();

    /**
     * @return RequestInterface
     */
    public function toHttpRequest()
    {
        $apiClient = $this->apiClient;
        $uri       = $apiClient ? $apiClient->getEndpointUrl($this->endpoint) : $this->endpoint;

        $query     = build_query($this->queryParams);
        $url       = $query ? "{$uri}?{$query}" : $uri;

        $headers = $apiClient ? array_merge($this->headers, $apiClient->getDefaultHttpHeaders()) : $this->headers;

        $request = new Request($this->method, $url, $headers);
        return $request;
    }
}
