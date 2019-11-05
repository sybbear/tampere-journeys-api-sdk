<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit\Utilities;

use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;
use Vikingmaster\TampereJourneysApiSdk\Utilities\Arr;

/**
 * @coversDefaultClass \Vikingmaster\TampereJourneysApiSdk\Utilities\Arr
 */
class ArrTest extends TestCase
{
    /**
     * @covers ::get
     */
    public function test_get()
    {
        $array = ['product' => ['id' => 5]];
        $this->assertEquals(5, Arr::get($array, 'product.id'));
    }
}
