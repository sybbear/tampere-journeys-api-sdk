<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Feature;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Vikingmaster\TampereJourneysApiSdk\Dto\ApiError;
use Vikingmaster\TampereJourneysApiSdk\Dto\Line;
use Vikingmaster\TampereJourneysApiSdk\Dto\Paging;
use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;

class GetLinesFeatureTest extends TestCase
{
    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetLinesRequest
     *
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest::toHttpRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest::setDescription
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest::setStartIndex
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest::setIndent
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest::send
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse::getStatus
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse::getPaging
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse::getApiError
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetLinesResponse::getLines
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Line::getName
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Line::getDescription
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Line::getUrl
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Paging::getStartIndex
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Paging::getPageSize
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Paging::hasMoreData
     */
    public function test_successful_request()
    {
        $api = $this->getApiClientMock([
            new Response(200, [], $this->getStubContents('response-lines-success.json'))
        ], ['baseUri' => 'http://data.itsfactory.fi/journeys/api']);

        $request = $api->makeGetLinesRequest();
        $this->assertInstanceOf(GetLinesRequest::class, $request);

        $request
            ->setStartIndex(10)
            ->setDescription('MyDescription');

        $httpRequest = $request->toHttpRequest();
        $this->assertInstanceOf(Request::class, $httpRequest);
        $uri = $httpRequest->getUri()->__toString();
        $this->assertEquals('/journeys/api/1/lines', $httpRequest->getUri()->getPath());
        $this->assertHasQueryParam($uri, 'startIndex', 10);
        $this->assertHasQueryParam($uri, 'description', 'MyDescription');

        $response = $request->send();
        $this->assertInstanceOf(GetLinesResponse::class, $response);

        $this->assertEquals(BaseResponse::STATUS_SUCCESS, $response->getStatus());
        $this->assertNull($response->getApiError());
        $paging = $response->getPaging();
        $this->assertInstanceOf(Paging::class, $response->getPaging());
        $this->assertEquals(151, $paging->getPageSize());
        $this->assertEquals(0, $paging->getStartIndex());
        $this->assertFalse($paging->hasMoreData());

        $lines = $response->getLines();
        $this->assertIsArray($lines);

        $line = $lines[0];
        $this->assertInstanceOf(Line::class, $line);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/lines/1', $line->getUrl());
        $this->assertEquals('1', $line->getName());
        $this->assertEquals('Vatiala - Pirkkala', $line->getDescription());
    }

    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetLinesRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiClient
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getResponse
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiError
     */
    public function test_failed_response_with_200_code()
    {
        $api = $this->getApiClientMock([
            new Response(200, [], $this->getStubContents('response-failed.json'))
        ], ['baseUri' => 'http://data.itsfactory.fi/journeys/api']);

        $request = $api->makeGetLinesRequest();
        $this->assertThrows(TampereJourneyApiException::class, function () use ($request) {
            $request->send();
        }, function (TampereJourneyApiException $e) {
            $this->assertInstanceOf(TampereJourneysApiClient::class, $e->getApiClient());
            $this->assertInstanceOf(GetLinesRequest::class, $e->getRequest());
            $this->assertInstanceOf(GetLinesResponse::class, $e->getResponse());
            $this->assertInstanceOf(ApiError::class, $apiError = $e->getApiError());
            $this->assertEquals('Coordinates', $apiError->getTitle());
            $this->assertEquals('Illegal format: must be latitude,longitude', $apiError->getMessage());
        });
    }

    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetLinesRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiClient
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getResponse
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiError
     */
    public function test_failed_response_with_400_code()
    {
        $api = $this->getApiClientMock([
            new Response(400, [], $this->getStubContents('response-failed.json'))
        ], ['baseUri' => 'http://data.itsfactory.fi/journeys/api']);

        $request = $api->makeGetLinesRequest();
        $this->assertThrows(TampereJourneyApiException::class, function () use ($request) {
            $request->send();
        }, function (TampereJourneyApiException $e) {
            $this->assertInstanceOf(TampereJourneysApiClient::class, $e->getApiClient());
            $this->assertInstanceOf(GetLinesRequest::class, $e->getRequest());
            $this->assertInstanceOf(GetLinesResponse::class, $e->getResponse());
            $this->assertInstanceOf(ApiError::class, $apiError = $e->getApiError());
            $this->assertEquals('Coordinates', $apiError->getTitle());
            $this->assertEquals('Illegal format: must be latitude,longitude', $apiError->getMessage());
        });
    }
}
