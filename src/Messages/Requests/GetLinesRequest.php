<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Requests;

use GuzzleHttp\Exception\GuzzleException;
use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse;

class GetLinesRequest extends BaseRequest
{
    protected $method   = 'GET';
    protected $endpoint = '1/lines';

    public function setDescription($value)
    {
        return $this->setQueryParam('description', $value);
    }

    /**
     * @return GetLinesResponse|BaseResponse
     * @throws GuzzleException
     * @throws TampereJourneyApiException
     */
    public function send()
    {
        return $this->apiClient->sendRequest($this, GetLinesResponse::class);
    }
}
