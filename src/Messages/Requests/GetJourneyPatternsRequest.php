<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Requests;

use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetJourneyPatternsResponse;
use Vikingmaster\TampereJourneysApiSdk\Utilities\CanAskToExcludeFields;

class GetJourneyPatternsRequest extends BaseRequest
{
    use CanAskToExcludeFields;

    protected $method = 'GET';
    protected $endpoint   = '1/journey-patterns';

    public function setLineId($value)
    {
        return $this->setQueryParam('lineId', $value);
    }

    public function setName($value)
    {
        return $this->setQueryParam('name', $value);
    }

    public function setFirstStopPointId($value)
    {
        return $this->setQueryParam('firstStopPointId', $value);
    }

    public function setLastStopPointId($value)
    {
        return $this->setQueryParam('lastStopPointId', $value);
    }

    /**
     * @return BaseResponse|GetJourneyPatternsResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws TampereJourneyApiException
     */
    public function send()
    {
        return $this->apiClient->sendRequest($this, GetJourneyPatternsResponse::class);
    }
}
