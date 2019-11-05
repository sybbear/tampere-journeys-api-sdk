<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit\Utilities;

use Vikingmaster\TampereJourneysApiSdk\Messages\Requests\BaseRequest;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;
use Vikingmaster\TampereJourneysApiSdk\Utilities\CanAskToExcludeFields;

class CanAskToExcludeFieldsTest extends TestCase
{
    public function test_excluding_fields()
    {
        $api = $this->getApiClientMock();
        $request = new DummyRequestExcludingFields($api);
        $request->excludeFields(['a', 'b']);

        $httpRequest = $request->toHttpRequest();
        $url         = $httpRequest->getUri()->__toString();
        $this->assertHasQueryParam($url, 'exclude-fields', 'a,b');
    }
}

class DummyRequestExcludingFields extends BaseRequest {
    use CanAskToExcludeFields;

    public function send(){}
}
