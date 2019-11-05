<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetJourneyPatternsRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\GetLinesRequest;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;

/**
 * @coversDefaultClass \Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient
 */
class TampereJourneysApiClientTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::validateConfig
     */
    public function test_throws_exception_on_missing_config()
    {
        $this->assertThrows(\InvalidArgumentException::class, function () {
            new TampereJourneysApiClient([]);
        });
    }

    /**
     * @covers ::setHttp
     * @covers ::getHttp
     */
    public function test_setting_and_getting_http()
    {
        $api = $this->getApiClientMock();
        $this->assertInstanceOf(Client::class, $api->getHttp());

        $newHttp = new Client();
        $this->assertTrue($api === $api->setHttp($newHttp));
        $this->assertTrue($newHttp === $api->getHttp());
    }

    /**
     * @covers ::makeGetLinesRequest
     * @covers ::makeGetJourneyPatternsRequest
     */
    public function test_returns_correct_request_classes()
    {
        $api = $this->getApiClientMock();
        $this->assertInstanceOf(GetJourneyPatternsRequest::class, $api->makeGetJourneyPatternsRequest());
        $this->assertInstanceOf(GetLinesRequest::class,           $api->makeGetLinesRequest());
    }

    /**
     * @covers ::getEndpointUrl
     */
    public function test_get_endpoint_url()
    {
        $api = new TampereJourneysApiClient(['baseUri' => 'http://data.itsfactory.fi/journeys/api']);
        $this->assertEquals('http://data.itsfactory.fi/journeys/api', $api->getEndpointUrl());
        $this->assertEquals('http://data.itsfactory.fi/journeys/api/1/resource', $api->getEndpointUrl('1/resource'));
        $this->assertEquals('http://data.itsfactory.fi/journeys/api/1/resource', $api->getEndpointUrl('/1/resource'));
        $this->assertEquals('http://data.itsfactory.fi/journeys/api/1/resource/', $api->getEndpointUrl('/1/resource/'));
    }

    /**
     * @covers ::getDefaultHttpHeaders
     */
    public function test_get_default_headers()
    {
        $api = $this->getApiClientMock([], [
            'baseUri'   => 'http://data.itsfactory.fi/journeys/api/1/resource',
            'userAgent' => 'PHPUnit'
        ]);
        $headers = $api->getDefaultHttpHeaders();
        $this->assertIsArray($headers);
        $this->assertEquals('PHPUnit', $headers['User-Agent']);
    }

    /**
     * @covers ::sendRequest
     * @covers \Vikingmaster\TampereJourneysApiSdk\Exceptions\TampereJourneyApiException::__construct
     */
    public function test_send_request()
    {
        $api = $this->getApiClientMock([
            new Response(200, [], json_encode(['status' => 'success']))
        ]);
        $request = new class extends BaseRequest {
            public function send(){}
        };
        $responseClass = get_class(new class extends BaseResponse {});

        $response = $api->sendRequest($request, $responseClass);
        $this->assertInstanceOf($responseClass, $response);

        //Test failure
        $api = $this->getApiClientMock([new Response(200, [], json_encode(['status' => 'fail']))]);
        $this->assertThrows(TampereJourneyApiException::class, function () use ($api, $request, $responseClass) {
            $api->sendRequest($request, $responseClass);
        });

        //Test another failure
        $api = $this->getApiClientMock([new Response(400, [], json_encode(['status' => 'fail']))]);
        $this->assertThrows(TampereJourneyApiException::class, function () use ($api, $request, $responseClass) {
            $api->sendRequest($request, $responseClass);
        });
    }
}
