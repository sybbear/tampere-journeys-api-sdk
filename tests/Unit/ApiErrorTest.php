<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit;

use Vikingmaster\TampereJourneysApiSdk\Dto\ApiError;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;

/**
 * @coversDefaultClass \Vikingmaster\TampereJourneysApiSdk\Dto\ApiError
 */
class ApiErrorTest extends TestCase
{
    /**
     * @covers ::getMessage
     */
    public function test_get_message()
    {
        $error = new ApiError(['message' => 'MyMessage']);
        $this->assertEquals('MyMessage', $error->getMessage());
    }

    /**
     * @covers ::getTitle
     */
    public function test_get_title()
    {
        $error = new ApiError(['title' => 'MyTitle']);
        $this->assertEquals('MyTitle', $error->getTitle());
    }
}
