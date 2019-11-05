<?php

namespace Vikingmaster\TampereJourneysApiSdk\Utilities;

use Vikingmaster\TampereJourneysApiSdk\Dto\BaseDto;

trait HasData
{
    protected $data = [];

    /**
     * @param string $key Attribute name, or path using dotted notation. E.g. data.body.element
     * @param null $default
     * @return mixed
     */
    public function getAttribute($key, $default = null)
    {
        return Arr::get($this->data, $key, $default);
    }

    /**
     * @param string $key
     * @param string|BaseDto $class
     * @return array
     */
    protected function typedAttributeArray(string $key, string $class)
    {
        $array = [];
        foreach ($this->getAttribute($key, []) as $data) {
            $array[] = $class::fromData($data);
        }
        return $array;
    }

    public function getData()
    {
        return $this->data;
    }
}
