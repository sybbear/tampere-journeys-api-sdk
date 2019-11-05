<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Responses;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Vikingmaster\TampereJourneysApiSdk\Dto\ApiError;
use Vikingmaster\TampereJourneysApiSdk\Dto\Paging;
use Vikingmaster\TampereJourneysApiSdk\Utilities\Arr;
use Vikingmaster\TampereJourneysApiSdk\Utilities\HasData;

abstract class BaseResponse
{
    use HasData;

    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAIL    = 'fail';

    /** @var ResponseInterface */
    protected $httpResponse;

    public static function fromHttpResponse(ResponseInterface $httpResponse)
    {
        $self = new static();
        $body = (string) $httpResponse->getBody();
        $self->data = json_decode($body, true) ?: [];
        $self->httpResponse = $httpResponse;
        return $self;
    }

    protected function getAttribute($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    protected function getBodyElements()
    {
        return $this->getAttribute('body', []);
    }

    /**
     * @param $value
     * @return array|\stdClass
     */
    protected function toObjects($value)
    {
        return json_decode(json_encode($value));
    }

    public function getStatus()
    {
        return $this->getAttribute('status');
    }

    public function getPaging()
    {
        return Paging::fromData($this->getAttribute('data.headers.paging', []));
    }

    public function getHttpResponse(): ResponseInterface
    {
        return $this->httpResponse;
    }

    /**
     * @return ApiError|null
     */
    public function getApiError()
    {
        if ($this->getStatus() !== static::STATUS_SUCCESS) {
            return new ApiError($this->getAttribute('data'));
        }
        return null;
    }
}
