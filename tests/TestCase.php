<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;
use Vikingmaster\TampereJourneysApiSdk\TampereJourneysApiClient;
use function GuzzleHttp\Psr7\build_query;
use function GuzzleHttp\Psr7\parse_query;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function assertThrows($exception, callable $callback, callable $catch = null)
    {
        try {
            $callback();
        } catch (\Exception $e) {
            if ($e instanceof $exception) {
                $this->addToAssertionCount(1);
                if ($catch) {
                    $catch($e);
                }
                return;
            }
            $class = get_class($e);
            $this->fail("Wrong exception type thrown. Expected: {$exception}, actual: {$class}: {$e->getMessage()}");
            return;
        }
        $this->fail("No exception has been thrown, expected {$exception}.");
    }

    protected function assertHasQueryParam($url, $param, $value = null)
    {
        $array = parse_query(parse_url($url, PHP_URL_QUERY));
        if (! array_key_exists($param, $array)) {
            $this->fail("Failed to assert, that query in url [{$url}] contains param [{$param}]");
            return;
        }

        if ($value !== null && ($array[$param] != $value)) {
            $this->fail("Fail to assert, that query in url [{$url}] contains param [{$param}] with value [{$value}]");
            return;
        }

        $this->addToAssertionCount(1);
    }

    protected function assertDoesNotHaveQueryParam($url, $name, $value = null)
    {
        $query = parse_url($url, PHP_URL_QUERY);
    }

    /**
     * Create mock MashApi client with predefined responses.
     * @param array $responses
     * @param array $config
     * @return TampereJourneysApiClient
     */
    protected function getApiClientMock(array $responses = [], array $config = null)
    {
        $api = new TampereJourneysApiClient($config ?? [
            'baseUri' => 'http://data.itsfactory.fi/journeys/api',
        ]);
        if ($responses) {
            $mock = new MockHandler($responses);
            $http = new Client([
                'handler' => HandlerStack::create($mock)
            ]);
            $api->setHttp($http);
        }
        return $api;
    }
    /**
     * @param Response[] $responses
     * @return Client
     */
    protected function httpMock(array $responses)
    {
        $mock = new MockHandler([$responses]);
        return new Client([
            'handler' => HandlerStack::create($mock)
        ]);
    }
    protected function dummyRequest(TampereJourneysApiClient $apiClient)
    {
        return new DummyRequest($apiClient);
    }

    protected function getStubContents($path)
    {
        return file_get_contents(__DIR__ . '/stubs/' . ltrim($path, '/'));
    }
}

class DummyRequest extends BaseRequest {
    public function send(){}
}
