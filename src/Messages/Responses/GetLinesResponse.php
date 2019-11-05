<?php

namespace Vikingmaster\TampereJourneysApiSdk\Messages\Responses;

use Vikingmaster\TampereJourneysApiSdk\Dto\Line;
use Vikingmaster\TampereJourneysApiSdk\Messages\Responses\BaseResponse;
use Vikingmaster\TampereJourneysApiSdk\Utilities\Arr;

class GetLinesResponse extends BaseResponse
{
    /**
     * @return array|Line[]
     */
    public function getLines()
    {
        $lines = [];
        foreach ($this->getAttribute('body', []) as $data) {
            $lines[] = Line::fromData($data);
        }
        return $lines;
    }
}
