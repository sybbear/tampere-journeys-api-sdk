<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Feature;

use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use Vikingmaster\TampereJourneysApiSdk\Dto\ApiError;
use Vikingmaster\TampereJourneysApiSdk\Dto\Paging;
use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetJourneyPatternsResponse;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;

class GetJourneyPatternsFeatureTest extends TestCase
{
    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetLinesRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setName
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setLineId
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setStartIndex
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setIndent
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setFirstStopPointId
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::setLastStopPointId
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest::send
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\GetJourneyPatternsResponse::getJourneyPatterns
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Paging::getStartIndex
     * @covers \Vikingmaster\TampereJourneysApiSdk\Dto\Paging::getPageSize
     */
    public function test_successful_request()
    {
        $api = $this->getApiClientMock([
            new Response(200, [], $this->getStubContents('response-journey-patterns.json'))
        ], ['baseUri' => 'http://data.itsfactory.fi/journeys/api']);

        $request = $api->makeGetJourneyPatternsRequest();
        $this->assertInstanceOf(GetJourneyPatternsRequest::class, $request);
        $request
            ->setStartIndex(12)
            ->setFirstStopPointId(10)
            ->setIndent(true)
            ->setLastStopPointId(5)
            ->setLineId(17)
            ->setName('PatternName')
        ;

        $httpRequest = $request->toHttpRequest();
        $this->assertInstanceOf(Request::class, $httpRequest);
        $uri = $httpRequest->getUri()->__toString();
        $this->assertEquals('/journeys/api/1/journey-patterns', $httpRequest->getUri()->getPath());
        $this->assertHasQueryParam($uri, 'startIndex', 12);
        $this->assertHasQueryParam($uri, 'indent', 'yes');
        $this->assertHasQueryParam($uri, 'firstStopPointId', 10);
        $this->assertHasQueryParam($uri, 'lastStopPointId', 5);
        $this->assertHasQueryParam($uri, 'lineId', 17);
        $this->assertHasQueryParam($uri, 'name', 'PatternName');

        $response = $request->send();
        $this->assertInstanceOf(GetJourneyPatternsResponse::class, $response);

        $this->assertEquals(BaseResponse::STATUS_SUCCESS, $response->getStatus());
        $this->assertNull($response->getApiError());
        $paging = $response->getPaging();
        $this->assertInstanceOf(Paging::class, $response->getPaging());
        $this->assertEquals(1, $paging->getPageSize());
        $this->assertEquals(0, $paging->getStartIndex());

        $patterns = $response->getJourneyPatterns();
        $this->assertIsArray($patterns);

        $pattern = $patterns[0];
        $this->assertInstanceOf(\stdClass::class, $pattern);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/journey-patterns/21450', $pattern->url);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/routes/21991', $pattern->routeUrl);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/lines/73', $pattern->lineUrl);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/stop-points/8524', $pattern->originStop);
        $this->assertEquals('http://178.217.134.14/journeys/api/1/stop-points/8625', $pattern->destinationStop);
        $this->assertEquals('Nokian asema C - Keho', $pattern->name);
        $this->assertEquals('1', $pattern->direction);

        $this->assertIsArray($pattern->stopPoints);
        $stopPoint = $pattern->stopPoints[0];

        $this->assertEquals('http://178.217.134.14/journeys/api/1/stop-points/8610', $stopPoint->url);
        $this->assertEquals('61.46852,23.51313', $stopPoint->location);
        $this->assertEquals('Viholanraitti', $stopPoint->name);
        $this->assertEquals('8610', $stopPoint->shortName);
        $this->assertEquals('C', $stopPoint->tariffZone);

        $municipality = $stopPoint->municipality;
        $this->assertEquals('http://178.217.134.14/journeys/api/1/municipalities/536', $municipality->url);
        $this->assertEquals('536', $municipality->shortName);
        $this->assertEquals('Nokia', $municipality->name);
    }

    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetJourneyPatternsRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiClient
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getResponse
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::getApiError
     * @covers \Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse::getApiError
     */
    public function test_failed_response_with_200_code()
    {
        $api = $this->getApiClientMock([
            new Response(200, [], $this->getStubContents('response-failed.json'))
        ], ['baseUri' => 'http://data.itsfactory.fi/journeys/api']);

        $request = $api->makeGetJourneyPatternsRequest();
        $this->assertThrows(TampereJourneyApiException::class, function () use ($request) {
            $request->send();
        }, function (TampereJourneyApiException $e) {
            $this->assertInstanceOf(TampereJourneysApiClient::class, $e->getApiClient());
            $this->assertInstanceOf(GetJourneyPatternsRequest::class, $e->getRequest());
            $this->assertInstanceOf(GetJourneyPatternsResponse::class, $e->getResponse());
            $this->assertInstanceOf(ApiError::class, $apiError = $e->getApiError());
            $this->assertEquals('Coordinates', $apiError->getTitle());
            $this->assertEquals('Illegal format: must be latitude,longitude', $apiError->getMessage());

            $response = $e->getResponse();
            $this->assertInstanceOf(ApiError::class, $apiError = $response->getApiError());
        });
    }

    /**
     * @covers \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient::makeGetJourneyPatternsRequest
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

        $request = $api->makeGetJourneyPatternsRequest();
        $this->assertThrows(TampereJourneyApiException::class, function () use ($request) {
            $request->send();
        }, function (TampereJourneyApiException $e) {
            $this->assertInstanceOf(TampereJourneysApiClient::class, $e->getApiClient());
            $this->assertInstanceOf(GetJourneyPatternsRequest::class, $e->getRequest());
            $this->assertInstanceOf(GetJourneyPatternsResponse::class, $e->getResponse());
            $this->assertInstanceOf(ApiError::class, $apiError = $e->getApiError());
            $this->assertEquals('Coordinates', $apiError->getTitle());
            $this->assertEquals('Illegal format: must be latitude,longitude', $apiError->getMessage());
        });
    }
}
