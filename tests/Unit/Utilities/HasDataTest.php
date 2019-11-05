<?php

namespace Vikingmaster\TampereJourneysApiSdk\Tests\Unit\Utilities;

use Vikingmaster\TampereJourneysApiSdk\Dto\BaseDto;
use Vikingmaster\TampereJourneysApiSdk\Tests\TestCase;
use Vikingmaster\TampereJourneysApiSdk\Utilities\HasData;

/**
 * @coversDefaultClass \Vikingmaster\TampereJourneysApiSdk\Utilities\HasData
 */
class HasDataTest extends TestCase
{
    /**
     * @covers ::getAttribute
     */
    public function test_get_attribute()
    {
        $object = new DummyHasData([
            'a' => 1,
            'b' => ['c' => 2]
        ]);
        $this->assertEquals(1, $object->getTheAttribute('a'));
    }

    /**
     * @covers ::typedAttributeArray
     */
    public function test_typed_attribute_array()
    {
        $object = new DummyHasData([
            'data' => [
                ['name' => 'a'],
                ['name' => 'b']
            ]
        ]);
        $dtos = $object->getDummyDtos('data', DummyDto::class);
        $this->assertIsArray($dtos);
        $this->assertCount(2, $dtos);
        $this->assertInstanceOf(DummyDto::class, $dtos[0]);
    }

    /**
     * @covers ::getData
     */
    public function test_get_data()
    {
        $object = new DummyHasData($data = [
            'data' => [
                ['name' => 'a'],
                ['name' => 'b']
            ]
        ]);
        $this->assertEquals($data, $object->getData());
    }
}

class DummyHasData {

    use HasData;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function getTheAttribute($key, $default = null)
    {
        return $this->getAttribute($key, $default);
    }

    public function getDummyDtos($key, $class)
    {
        return $this->typedAttributeArray($key, $class);
    }
}

class DummyDto extends BaseDto {

}
