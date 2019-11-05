<?php

namespace Vikingmaster\TampereJourneysApiSdk\Dto;

use Vikingmaster\TampereJourneysApiSdk\Utilities\Arr;
use Vikingmaster\TampereJourneysApiSdk\Utilities\HasData;

abstract class BaseDto
{
    use HasData;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public static function fromData(array $data)
    {
        return new static($data);
    }
}
