<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit;

use Vikingmaster\TampereJourneysApiSdk\Dto\BaseDto;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;

/**
 * @coversDefaultClass \Vikingmaster\TampereJourneysApiSdk\Dto\BaseDto
 */
class BaseDtoTest extends TestCase
{
    /**
     *
     */
    public function test_constructor()
    {
        $data = ['key' => 'value'];
        $dto = new class($data) extends BaseDto {};
        $this->assertEquals($data, $dto->getData());
    }

    /**
     * @covers ::fromData
     */
    public function test_static_from_data()
    {
        $data = ['key' => 'value'];
        $dto = (new class() extends BaseDto {})::fromData($data);
        $this->assertEquals($data, $dto->getData());
    }
}
