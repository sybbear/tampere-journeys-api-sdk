<?php

namespace Vikingmaster\TampereJourneysApiSdk\Exceptions;

use Vikingmaster\TampereJourneysApiSdk\Dto\ApiError;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;

class TampereJourneyApiException extends \Exception
{
    protected $request;
    protected $response;
    protected $apiClient;

    public function __construct(TampereJourneysApiClient $apiClient, BaseRequest $req, BaseResponse $res = null, \Throwable $prev = null)
    {
        parent::__construct($prev ? $prev->getMessage() : 'TampereJourneyApiException', $prev ? $prev->getCode() : 0, $prev);
        $this->apiClient = $apiClient;
        $this->request  = $req;
        $this->response = $res;
    }

    /**
     * @return ApiError|null
     */
    public function getApiError()
    {
        return $this->response ? $this->response->getApiError() : null;
    }

    /**
     * @return BaseRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return BaseResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getApiClient()
    {
        return $this->apiClient;
    }
}
